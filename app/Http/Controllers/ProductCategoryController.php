<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        // $this->authorizeResource(ProductCategory::class, 'product-category');
    }

    public function index()
    {
        $categories = ProductCategory::with('parent')->paginate(10);
        return view('dashboard.product_categories.index', compact('categories'));
    }

    public function create()
    {
        $parentCategories = ProductCategory::whereNull('parent_id')->get();
        return view('dashboard.product_categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $validated['slug'] = $this->generateSlug($validated['name']);

        // Create the category first to get the ID
        $category = ProductCategory::create($validated);

        // Handle image upload after category creation
        if ($request->hasFile('image')) {
            $imagePath = Storage::disk('website')
                ->putFile("product-categories/{$category->id}", $request->file('image'));
            $category->update(['image_url' => $imagePath]);
        }

        return redirect()->route('product-categories.index')->with('success', 'Category created successfully.');
    }

    public function show(ProductCategory $productCategory)
    {
        return view('dashboard.product_categories.show', compact('productCategory'));
    }

    public function edit(ProductCategory $productCategory)
    {
        $parentCategories = ProductCategory::whereNull('parent_id')->where('id', '!=', $productCategory->id)->get();
        return view('dashboard.product_categories.edit', compact('productCategory', 'parentCategories'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'parent_id' => 'nullable|exists:product_categories,id',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'remove_image' => 'boolean',
        ]);

        if (isset($validated['name']) && $validated['name'] !== $productCategory->name) {
            $validated['slug'] = $this->generateSlug($validated['name']);
        }

        // Handle image removal
        if ($request->has('remove_image') && $productCategory->image_url) {
            // Delete the image file from storage
            if (Storage::disk('website')->exists($productCategory->image_url)) {
                Storage::disk('website')->delete($productCategory->image_url);
            }
            $validated['image_url'] = null;
        }

        // Update category data (without image first)
        $productCategory->update($validated);

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($productCategory->image_url && Storage::disk('website')->exists($productCategory->image_url)) {
                Storage::disk('website')->delete($productCategory->image_url);
            }

            // Store new image
            $imagePath = Storage::disk('website')
                ->putFile("product-categories/{$productCategory->id}", $request->file('image'));
            $productCategory->update(['image_url' => $imagePath]);
        }

        return redirect()->route('product-categories.show', $productCategory)->with('success', 'Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        if ($productCategory->products()->exists()) {
            return back()->with('error', 'Cannot delete category with associated products.');
        }

        // Delete image if exists
        if ($productCategory->image_url && Storage::disk('website')->exists($productCategory->image_url)) {
            Storage::disk('website')->delete($productCategory->image_url);

            // Also delete the directory if empty
            $directory = "product-categories/{$productCategory->id}";
            if (Storage::disk('website')->exists($directory)) {
                // Check if directory is empty
                $files = Storage::disk('website')->files($directory);
                if (empty($files)) {
                    Storage::disk('website')->deleteDirectory($directory);
                }
            }
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
