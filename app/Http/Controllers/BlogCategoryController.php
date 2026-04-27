<?php

// app/Http/Controllers/Admin/BlogCategoryController.php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogCategory::withCount('blogPosts');

        $search = $request->string('search')->trim()->toString();
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('slug', 'like', '%'.$search.'%');
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = strtolower((string) $request->get('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSort = ['name', 'slug', 'icon', 'is_active', 'blog_posts_count', 'created_at', 'id'];
        if (! in_array($sortBy, $allowedSort, true)) {
            $sortBy = 'created_at';
        }

        $query->orderBy($sortBy, $sortDir);

        $categories = $query->paginate(10)->withQueryString();

        return view('dashboard.blog.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        BlogCategory::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(BlogCategory $blogCategory)
    {
        $blogCategory->loadCount('blogPosts');

        return view('dashboard.blog.edit', compact('blogCategory'));
    }

    public function update(Request $request, BlogCategory $blogCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $blogCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'icon' => $request->icon,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(BlogCategory $blogCategory)
    {
        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
