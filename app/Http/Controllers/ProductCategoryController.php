<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductCategory::class, 'product-category');
    }

    public function index()
    {
        $categories = ProductCategory::with('parent')->get();
        return view('product-categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = ProductCategory::whereNull('parent_id')->get();
        return view('product-categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = $this->generateSlug($validated['name']);

        ProductCategory::create($validated);

        return redirect()->route('product-categories.index')->with('success', 'Category created successfully.');
    }

    public function show(ProductCategory $productCategory)
    {
        return view('product-categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        $parentCategories = ProductCategory::whereNull('parent_id')->where('id', '!=', $productCategory->id)->get();
        return view('product-categories.edit', compact('productCategory', 'parentCategories'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name']) && $validated['name'] !== $productCategory->name) {
            $validated['slug'] = $this->generateSlug($validated['name']);
        }

        $productCategory->update($validated);

        return redirect()->route('product-categories.show', $productCategory)->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->exists()) {
            return back()->with('error', 'Cannot delete category with associated products.');
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')->with('success', 'Category deleted successfully.');
    }

    private function generateSlug($name)
    {
        $slug = \Illuminate\Support\Str::slug($name);
        $count = ProductCategory::where('slug', 'LIKE', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }
}
