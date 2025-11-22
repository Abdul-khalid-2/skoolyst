<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::with(['parent', 'children'])
            ->withCount('products')
            ->latest()
            ->paginate(15);

        return view('dashboard.shop.categorys.index', compact('categories'));
    }

    public function create()
    {

        $parentCategories = ProductCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->get();

        return view('dashboard.shop.categorys.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['uuid'] = Str::uuid();
        // Generate slug from name
        $validated['slug'] = Str::slug($validated['name']);

        // Ensure slug is unique
        $counter = 1;
        $originalSlug = $validated['slug'];
        while (ProductCategory::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Handle image upload with website disk
        if ($request->hasFile('image')) {
            $imagePath = Storage::disk('website')
                ->putFile("product-categories", $request->file('image'));
            $validated['image_url'] = $imagePath;
        }

        // Set default sort order if not provided
        if (!isset($validated['sort_order'])) {
            $maxOrder = ProductCategory::where('parent_id', $validated['parent_id'])->max('sort_order');
            $validated['sort_order'] = $maxOrder ? $maxOrder + 1 : 0;
        }

        ProductCategory::create($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category created successfully!');
    }

    public function show(ProductCategory $productCategory)
    {
        $productCategory->load(['parent', 'children', 'products.shop']);

        return view('dashboard.shop.categorys.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        $parentCategories = ProductCategory::whereNull('parent_id')
            ->where('is_active', true)
            ->where('id', '!=', $productCategory->id)
            ->get();

        return view('dashboard.shop.categorys.edit', compact('productCategory', 'parentCategories'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name,' . $productCategory->id,
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Prevent category from being its own parent
        if ($validated['parent_id'] == $productCategory->id) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['parent_id' => 'A category cannot be its own parent.']);
        }

        // Update slug if name changed
        if ($productCategory->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);

            // Ensure slug is unique
            $counter = 1;
            $originalSlug = $validated['slug'];
            while (ProductCategory::where('slug', $validated['slug'])->where('id', '!=', $productCategory->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Handle image upload with website disk
        if ($request->hasFile('image')) {
            // Delete old image
            if ($productCategory->image_url) {
                Storage::disk('website')->delete($productCategory->image_url);
            }

            $imagePath = Storage::disk('website')
                ->putFile("product-categories", $request->file('image'));
            $validated['image_url'] = $imagePath;
        }

        $productCategory->update($validated);

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category updated successfully!');
    }

    public function destroy(ProductCategory $productCategory)
    {
        // Check if category has products
        if ($productCategory->products()->exists()) {
            return redirect()->route('admin.product-categories.index')
                ->with('error', 'Cannot delete category that has products. Please reassign or delete the products first.');
        }

        // Check if category has subcategories
        if ($productCategory->children()->exists()) {
            return redirect()->route('admin.product-categories.index')
                ->with('error', 'Cannot delete category that has subcategories. Please delete or reassign the subcategories first.');
        }

        // Delete image if exists
        if ($productCategory->image_url) {
            Storage::disk('website')->delete($productCategory->image_url);
        }

        $productCategory->delete();

        return redirect()->route('admin.product-categories.index')
            ->with('success', 'Product category deleted successfully!');
    }

    public function toggleStatus(ProductCategory $productCategory)
    {
        $productCategory->update([
            'is_active' => !$productCategory->is_active
        ]);

        $status = $productCategory->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Product category {$status} successfully!");
    }
}
