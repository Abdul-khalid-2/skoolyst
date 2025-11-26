<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\School;
use App\Models\ProductCategory;
use App\Models\ShopSchoolAssociation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request)
    {
        $query = Product::with(['shop', 'category', 'school', 'association', 'attributes']);

        if (Auth::user()->hasRole('shop-owner')) {
            // Get shops owned by the user
            $shopIds = Shop::where('user_id', Auth::id())->pluck('id');
            if ($shopIds->isNotEmpty()) {
                $query->whereIn('shop_id', $shopIds);
            } else {
                // If user has no shops, return empty results
                $query->where('shop_id', 0);
            }
        } elseif (Auth::user()->hasRole('school-admin')) {
            // Get the school ID from user's school_id
            $schoolId = Auth::user()->school_id;
            if ($schoolId) {
                $query->where('school_id', $schoolId);
            } else {
                // If user has no school, return empty results
                $query->where('school_id', 0);
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%");
            });
        }

        if ($request->has('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products = $query->latest()->paginate(20);

        return view('dashboard.products.index', compact('products'));
    }

    public function create()
    {
        $shops = Shop::where('user_id', Auth::id())->get();
        $categories = ProductCategory::where('is_active', true)->get();

        // Check if user has shops before allowing creation
        if ($shops->isEmpty() && Auth::user()->hasRole('shop-owner')) {
            return redirect()->route('shops.create')
                ->with('error', 'You need to create a shop first before adding products.');
        }

        return view('dashboard.products.create', compact('shops', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:product_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'association_id' => 'nullable|exists:shop_school_associations,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'product_type' => 'required|in:book,copy,stationery,bag,uniform,other',
            'brand' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'manage_stock' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'attributes' => 'nullable|array',
        ]);

        // Check if user owns the shop they're trying to add product to
        if (Auth::user()->hasRole('shop-owner')) {
            $userShopIds = Shop::where('user_id', Auth::id())->pluck('id');
            if (!$userShopIds->contains($validated['shop_id'])) {
                return back()->with('error', 'You can only add products to your own shops.');
            }
        }

        // Check if school-admin is trying to add product to their school
        if (Auth::user()->hasRole('school-admin') && isset($validated['school_id'])) {
            if ($validated['school_id'] != Auth::user()->school_id) {
                return back()->with('error', 'You can only add products to your own school.');
            }
        }

        if (isset($validated['school_id']) && isset($validated['association_id'])) {
            $association = ShopSchoolAssociation::where('id', $validated['association_id'])
                ->where('shop_id', $validated['shop_id'])
                ->where('school_id', $validated['school_id'])
                ->where('status', 'approved')
                ->where('is_active', true)
                ->first();

            if (!$association) {
                return back()->with('error', 'Invalid school association.');
            }
        }

        DB::beginTransaction();

        try {
            $validated['slug'] = $this->generateSlug($validated['name'], $validated['shop_id']);
            $validated['sku'] = $request->sku ?? $this->generateSKU();

            if (isset($validated['cost_price']) && $validated['cost_price'] > 0) {
                $validated['profit_margin'] = (($validated['base_price'] - $validated['cost_price']) / $validated['cost_price']) * 100;
            }

            // Set default values for checkboxes
            $validated['manage_stock'] = $request->has('manage_stock') ? true : false;
            $validated['is_featured'] = $request->has('is_featured') ? true : false;
            $validated['is_active'] = $request->has('is_active') ? true : false;

            // Auto-approve for shop owners and school admins
            if (Auth::user()->hasRole('shop-owner') || Auth::user()->hasRole('school-admin')) {
                $validated['is_approved'] = true;
            }

            $product = Product::create($validated);

            // Handle main image upload
            if ($request->hasFile('main_image')) {
                $mainImagePath = Storage::disk('website')
                    ->putFile("products/{$product->id}/main", $request->file('main_image'));
                $product->update(['main_image_url' => $mainImagePath]);
            }

            // Handle image gallery upload
            if ($request->hasFile('image_gallery')) {
                $galleryPaths = [];
                foreach ($request->file('image_gallery') as $image) {
                    $galleryPath = Storage::disk('website')
                        ->putFile("products/{$product->id}/gallery", $image);
                    $galleryPaths[] = $galleryPath;
                }
                $product->update(['image_gallery' => $galleryPaths]);
            }

            if (isset($validated['attributes'])) {
                $product->attributes()->create($validated['attributes']);
            }

            DB::commit();

            return redirect()->route('products.show', $product)->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function show(Product $product)
    {
        // Authorization check
        $this->authorizeProductAccess($product);

        $product->load(['shop', 'category', 'school', 'association', 'attributes']);
        return view('dashboard.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Authorization check
        $this->authorizeProductAccess($product);

        $shops = Shop::where('user_id', Auth::id())->get();
        $categories = ProductCategory::where('is_active', true)->get();
        return view('dashboard.products.edit', compact('product', 'shops', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Authorization check
        $this->authorizeProductAccess($product);

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:product_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'association_id' => 'nullable|exists:shop_school_associations,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'product_type' => 'required|in:book,copy,stationery,bag,uniform,other',
            'brand' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'manage_stock' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'is_active' => 'sometimes|boolean',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_main_image' => 'sometimes|boolean',
            'remove_gallery_images' => 'sometimes|boolean',
            // 'meta_title' => 'nullable|string|max:255',
            // 'meta_description' => 'nullable|string|max:500',
            // 'meta_keywords' => 'nullable|string|max:255',
            'attributes' => 'nullable|array',
        ]);

        // Check if school-admin is trying to update product for their school
        if (Auth::user()->hasRole('school-admin') && isset($validated['school_id'])) {
            if ($validated['school_id'] != Auth::user()->school_id) {
                return back()->with('error', 'You can only update products for your own school.');
            }
        }

        DB::beginTransaction();

        try {
            if (isset($validated['name']) && $validated['name'] !== $product->name) {
                $validated['slug'] = $this->generateSlug($validated['name'], $product->shop_id);
            }

            if ((isset($validated['cost_price']) || isset($validated['base_price'])) &&
                ($validated['cost_price'] ?? $product->cost_price) > 0
            ) {
                $costPrice = $validated['cost_price'] ?? $product->cost_price;
                $basePrice = $validated['base_price'] ?? $product->base_price;
                $validated['profit_margin'] = (($basePrice - $costPrice) / $costPrice) * 100;
            }

            // Set checkbox values
            $validated['manage_stock'] = $request->has('manage_stock');
            $validated['is_featured'] = $request->has('is_featured');
            $validated['is_active'] = $request->has('is_active');

            // Prepare data for update (exclude files and special fields)
            $updateData = [
                'shop_id' => $validated['shop_id'],
                'category_id' => $validated['category_id'],
                'school_id' => $validated['school_id'] ?? null,
                'association_id' => $validated['association_id'] ?? null,
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'product_type' => $validated['product_type'],
                'brand' => $validated['brand'] ?? null,
                'material' => $validated['material'] ?? null,
                'color' => $validated['color'] ?? null,
                'size' => $validated['size'] ?? null,
                'base_price' => $validated['base_price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'cost_price' => $validated['cost_price'] ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'low_stock_threshold' => $validated['low_stock_threshold'] ?? 5,
                'manage_stock' => $validated['manage_stock'],
                'is_featured' => $validated['is_featured'],
                'is_active' => $validated['is_active'],
                // 'meta_title' => $validated['meta_title'] ?? null,
                // 'meta_description' => $validated['meta_description'] ?? null,
                // 'meta_keywords' => $validated['meta_keywords'] ?? null,
            ];

            // Add slug if it was generated
            if (isset($validated['slug'])) {
                $updateData['slug'] = $validated['slug'];
            }

            // Add profit margin if it was calculated
            if (isset($validated['profit_margin'])) {
                $updateData['profit_margin'] = $validated['profit_margin'];
            }

            // Update the product with basic data first
            $product->update($updateData);

            // Handle main image removal
            if ($request->has('remove_main_image') && $product->main_image_url) {
                if (Storage::disk('website')->exists($product->main_image_url)) {
                    Storage::disk('website')->delete($product->main_image_url);
                }
                $product->update(['main_image_url' => null]);
            }

            // Handle new main image upload
            if ($request->hasFile('main_image')) {
                // Delete old main image if exists
                if ($product->main_image_url && Storage::disk('website')->exists($product->main_image_url)) {
                    Storage::disk('website')->delete($product->main_image_url);
                }

                $mainImagePath = Storage::disk('website')
                    ->putFile("products/{$product->id}/main", $request->file('main_image'));
                $product->update(['main_image_url' => $mainImagePath]);
            }

            // Handle gallery images removal
            if ($request->has('remove_gallery_images') && $product->image_gallery) {
                foreach ($product->image_gallery as $galleryImage) {
                    if (Storage::disk('website')->exists($galleryImage)) {
                        Storage::disk('website')->delete($galleryImage);
                    }
                }
                $product->update(['image_gallery' => null]);
            }

            // Handle new gallery images upload
            if ($request->hasFile('image_gallery')) {
                $galleryPaths = $product->image_gallery ?? [];

                foreach ($request->file('image_gallery') as $image) {
                    if ($image->isValid()) {
                        $galleryPath = Storage::disk('website')
                            ->putFile("products/{$product->id}/gallery", $image);
                        $galleryPaths[] = $galleryPath;
                    }
                }

                // Filter out any empty values and ensure unique paths
                $galleryPaths = array_values(array_unique(array_filter($galleryPaths)));

                $product->update(['image_gallery' => $galleryPaths]);
            }

            // Handle product attributes
            if (isset($validated['attributes'])) {
                if ($product->attributes) {
                    $product->attributes()->update($validated['attributes']);
                } else {
                    $product->attributes()->create($validated['attributes']);
                }
            }

            DB::commit();

            return redirect()->route('products.show', $product)->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        // Authorization check
        $this->authorizeProductAccess($product);

        DB::beginTransaction();

        try {
            // Delete main image if exists
            if ($product->main_image_url && Storage::disk('website')->exists($product->main_image_url)) {
                Storage::disk('website')->delete($product->main_image_url);
            }

            // Delete gallery images if exist
            if ($product->image_gallery) {
                foreach ($product->image_gallery as $galleryImage) {
                    if (Storage::disk('website')->exists($galleryImage)) {
                        Storage::disk('website')->delete($galleryImage);
                    }
                }
            }

            // Delete product directory if exists
            $productDirectory = "products/{$product->id}";
            if (Storage::disk('website')->exists($productDirectory)) {
                Storage::disk('website')->deleteDirectory($productDirectory);
            }

            if ($product->attributes) {
                $product->attributes()->delete();
            }

            $product->delete();

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }

    public function updateStock(Request $request, Product $product)
    {
        // Authorization check
        $this->authorizeProductAccess($product);

        $validated = $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'action' => 'sometimes|in:add,subtract,set',
        ]);

        DB::beginTransaction();

        try {
            $currentStock = $product->stock_quantity;

            switch ($validated['action'] ?? 'set') {
                case 'add':
                    $newStock = $currentStock + $validated['stock_quantity'];
                    break;
                case 'subtract':
                    $newStock = max(0, $currentStock - $validated['stock_quantity']);
                    break;
                case 'set':
                default:
                    $newStock = $validated['stock_quantity'];
                    break;
            }

            $product->update([
                'stock_quantity' => $newStock,
                'is_in_stock' => $newStock > 0
            ]);

            DB::commit();

            return back()->with('success', 'Stock updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update stock: ' . $e->getMessage());
        }
    }

    /**
     * Authorize product access based on user role
     */
    private function authorizeProductAccess(Product $product)
    {
        if (Auth::user()->hasRole('shop-owner')) {
            $userShopIds = Shop::where('user_id', Auth::id())->pluck('id');
            if (!$userShopIds->contains($product->shop_id)) {
                abort(403, 'Unauthorized action.');
            }
        } elseif (Auth::user()->hasRole('school-admin')) {
            // School admin can only access products for their school
            if ($product->school_id != Auth::user()->school_id) {
                abort(403, 'Unauthorized action.');
            }
        }
        // Super admin has access to all products
    }

    private function generateSlug($name, $shopId)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = Product::where('shop_id', $shopId)
            ->where('slug', 'LIKE', "{$slug}%")
            ->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    private function generateSKU()
    {
        return 'SKU-' . strtoupper(uniqid());
    }
}
