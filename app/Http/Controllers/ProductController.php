<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use App\Models\ProductCategory;
use App\Models\ShopSchoolAssociation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request)
    {
        $query = Product::with(['shop', 'category', 'school', 'association', 'attributes']);

        if (Auth::user()->hasRole('shop_owner')) {
            $shopIds = Auth::user()->shops->pluck('id');
            $query->whereIn('shop_id', $shopIds);
        } elseif (Auth::user()->hasRole('school_admin')) {
            $schoolIds = Auth::user()->schools->pluck('id');
            $query->whereIn('school_id', $schoolIds);
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

        $products = $query->paginate(20);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $shops = Shop::where('user_id', Auth::id())->get();
        $categories = ProductCategory::where('is_active', true)->get();
        return view('products.create', compact('shops', 'categories'));
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
            'manage_stock' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'attributes' => 'nullable|array',
        ]);

        $shop = Shop::findOrFail($validated['shop_id']);
        Gate::authorize('manage-products', $shop);

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
            $validated['sku'] = $request->sku ?? null;

            if (isset($validated['cost_price']) && $validated['cost_price'] > 0) {
                $validated['profit_margin'] = (($validated['base_price'] - $validated['cost_price']) / $validated['cost_price']) * 100;
            }

            $product = Product::create($validated);

            if (isset($validated['attributes'])) {
                $product->attributes()->create($validated['attributes']);
            }

            DB::commit();

            return redirect()->route('products.show', $product)->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create product.');
        }
    }

    public function show(Product $product)
    {
        $product->load(['shop', 'category', 'school', 'association', 'attributes']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $shops = Shop::where('user_id', Auth::id())->get();
        $categories = ProductCategory::where('is_active', true)->get();
        return view('products.edit', compact('product', 'shops', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'sometimes|exists:product_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'association_id' => 'nullable|exists:shop_school_associations,id',
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'product_type' => 'sometimes|in:book,copy,stationery,bag,uniform,other',
            'brand' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'base_price' => 'sometimes|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'sometimes|integer|min:0',
            'low_stock_threshold' => 'nullable|integer|min:0',
            'manage_stock' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'attributes' => 'nullable|array',
        ]);

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

            $product->update($validated);

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
            return back()->with('error', 'Failed to update product.');
        }
    }

    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            if ($product->attributes) {
                $product->attributes()->delete();
            }

            $product->delete();

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete product.');
        }
    }

    public function updateStock(Request $request, Product $product)
    {
        Gate::authorize('update-stock', $product);

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
            return back()->with('error', 'Failed to update stock.');
        }
    }

    private function generateSlug($name, $shopId)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = Product::where('shop_id', $shopId)
            ->where('slug', 'LIKE', "{$slug}%")
            ->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
