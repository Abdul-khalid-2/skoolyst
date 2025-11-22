<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['shop', 'category']);

        // Apply filters
        if ($request->has('shop_id') && $request->shop_id) {
            $query->where('shop_id', $request->shop_id);
        }

        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('status') && $request->status) {
            switch ($request->status) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'approved':
                    $query->where('is_approved', true);
                    break;
                case 'pending':
                    $query->where('is_approved', false);
                    break;
            }
        }

        if ($request->has('stock') && $request->stock) {
            switch ($request->stock) {
                case 'in_stock':
                    $query->where('is_in_stock', true);
                    break;
                case 'out_of_stock':
                    $query->where('is_in_stock', false);
                    break;
                case 'low_stock':
                    $query->where('stock_quantity', '<=', \DB::raw('low_stock_threshold'))
                        ->where('is_in_stock', true);
                    break;
            }
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('sku', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // School admin can only see their school's products
        if (auth()->user()->hasRole('school_admin') && auth()->user()->school_id) {
            $query->whereHas('shop', function ($q) {
                $q->where('school_id', auth()->user()->school_id);
            });
        }

        // Shop owner can only see their products
        if (auth()->user()->hasRole('shop_owner')) {
            $query->whereHas('shop', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $products = $query->latest()->paginate(15);

        $shops = Shop::when(auth()->user()->hasRole('school_admin'), function ($q) {
            $q->where('school_id', auth()->user()->school_id);
        })->when(auth()->user()->hasRole('shop_owner'), function ($q) {
            $q->where('user_id', auth()->id());
        })->get();

        $categories = ProductCategory::where('is_active', true)->get();

        return view('dashboard.shop.products.index', compact('products', 'shops', 'categories'));
    }

    public function create()
    {
        $shops = Shop::when(auth()->user()->hasRole('school_admin'), function ($q) {
            $q->where('school_id', auth()->user()->school_id);
        })->when(auth()->user()->hasRole('shop_owner'), function ($q) {
            $q->where('user_id', auth()->id());
        })->get();

        $categories = ProductCategory::where('is_active', true)->get();

        return view('dashboard.shop.products.create', compact('shops', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:products,sku',
            'product_type' => 'required|in:book,copy,stationery,bag,uniform,other',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'manage_stock' => 'boolean',
            'brand' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ]);

        $validated['uuid'] = Str::uuid();
        // Generate SKU if not provided
        if (empty($validated['sku'])) {
            $validated['sku'] = 'PROD-' . Str::upper(Str::random(8));
        }

        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $counter = 1;
        $originalSlug = $validated['slug'];
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $mainImagePath = Storage::disk('website')
                ->putFile("products/main", $request->file('main_image'));
            $validated['main_image_url'] = $mainImagePath;
        }

        // Handle gallery images
        $galleryUrls = [];
        if ($request->hasFile('image_gallery')) {
            foreach ($request->file('image_gallery') as $image) {
                $galleryPath = Storage::disk('website')
                    ->putFile("products/gallery", $image);
                $galleryUrls[] = $galleryPath;
            }
            $validated['image_gallery'] = json_encode($galleryUrls);
        }

        // Set inventory status
        $validated['is_in_stock'] = $validated['stock_quantity'] > 0;

        // Create product
        $product = Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        // $this->authorize('view', $product);

        $product->load(['shop', 'category', 'attributes']);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // $this->authorize('update', $product);

        $shops = Shop::when(auth()->user()->hasRole('school_admin'), function ($q) {
            $q->where('school_id', auth()->user()->school_id);
        })->when(auth()->user()->hasRole('shop_owner'), function ($q) {
            $q->where('user_id', auth()->id());
        })->get();

        $categories = ProductCategory::where('is_active', true)->get();

        return view('admin.products.edit', compact('product', 'shops', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'sku' => 'nullable|string|unique:products,sku,' . $product->id,
            'product_type' => 'required|in:book,copy,stationery,bag,uniform,other',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'manage_stock' => 'boolean',
            'brand' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'image_gallery' => 'nullable|array',
            'image_gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
        ]);

        // Update slug if name changed
        if ($product->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure slug is unique
            $counter = 1;
            $originalSlug = $validated['slug'];
            while (Product::where('slug', $validated['slug'])->where('id', '!=', $product->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle main image upload with website disk
        if ($request->hasFile('main_image')) {
            // Delete old main image
            if ($product->main_image_url) {
                Storage::disk('website')->delete($product->main_image_url);
            }

            $mainImagePath = Storage::disk('website')
                ->putFile("products/main", $request->file('main_image'));
            $validated['main_image_url'] = $mainImagePath;
        }

        // Handle gallery images with website disk
        if ($request->hasFile('image_gallery')) {
            // Delete old gallery images
            if ($product->image_gallery) {
                $oldGallery = json_decode($product->image_gallery, true);
                foreach ($oldGallery as $oldImage) {
                    Storage::disk('website')->delete($oldImage);
                }
            }

            $galleryUrls = [];
            foreach ($request->file('image_gallery') as $image) {
                $galleryPath = Storage::disk('website')
                    ->putFile("products/gallery", $image);
                $galleryUrls[] = $galleryPath;
            }
            $validated['image_gallery'] = json_encode($galleryUrls);
        }

        // Set inventory status
        $validated['is_in_stock'] = $validated['stock_quantity'] > 0;

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // $this->authorize('delete', $product);

        // Delete associated files
        if ($product->main_image_url) {
            Storage::disk('website')->delete($product->main_image_url);
        }

        if ($product->image_gallery) {
            $galleryImages = json_decode($product->image_gallery, true);
            foreach ($galleryImages as $image) {
                Storage::disk('website')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
