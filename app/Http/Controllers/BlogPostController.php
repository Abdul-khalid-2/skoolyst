<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\School;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['category', 'user', 'school'])
            ->latest()
            ->paginate(10);

        return view('dashboard.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::where('is_active', true)->get();
        $schools = School::where('status', 'active')->get();

        return view('dashboard.posts.create', compact('categories', 'schools'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string'
        ]);

        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $featuredImagePath = $request->file('featured_image')->store('blog/featured-images', 'public');
        }

        $tags = $request->tags ? explode(',', $request->tags) : [];

        $blogPost = BlogPost::create([
            'uuid' => Str::uuid(),
            'user_id' => auth()->id(),
            'school_id' => $request->school_id,
            'blog_category_id' => $request->blog_category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::random(6),
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImagePath,
            'tags' => $tags,
            'read_time' => $this->calculateReadTime($request->content),
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'published_at' => $request->status === 'published' ? now() : null,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'structure' => $request->structure ? json_decode($request->structure, true) : null,
            'canvas_elements' => $request->canvas_elements ? json_decode($request->canvas_elements, true) : null,
        ]);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function show(BlogPost $blogPost)
    {
        $blogPost->load(['category', 'user', 'school', 'comments' => function ($query) {
            $query->where('status', 'approved')->with('replies');
        }]);

        return view('dashboard.posts.show', compact('blogPost'));
    }

    public function edit(BlogPost $blogPost)
    {
        $categories = BlogCategory::where('is_active', true)->get();
        $schools = School::where('status', 'active')->get();

        return view('dashboard.posts.edit', compact('blogPost', 'categories', 'schools'));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'tags' => 'nullable|string'
        ]);

        $featuredImagePath = $blogPost->featured_image;
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blogPost->featured_image) {
                Storage::disk('public')->delete($blogPost->featured_image);
            }
            $featuredImagePath = $request->file('featured_image')->store('blog/featured-images', 'public');
        }

        $tags = $request->tags ? explode(',', $request->tags) : [];

        $updateData = [
            'school_id' => $request->school_id,
            'blog_category_id' => $request->blog_category_id,
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'featured_image' => $featuredImagePath,
            'tags' => $tags,
            'read_time' => $this->calculateReadTime($request->content),
            'status' => $request->status,
            'is_featured' => $request->is_featured ?? false,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'structure' => $request->structure ? json_decode($request->structure, true) : null,
            'canvas_elements' => $request->canvas_elements ? json_decode($request->canvas_elements, true) : null,
        ];

        if ($request->status === 'published' && !$blogPost->published_at) {
            $updateData['published_at'] = now();
        }

        $blogPost->update($updateData);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        if ($blogPost->featured_image) {
            Storage::disk('public')->delete($blogPost->featured_image);
        }

        $blogPost->delete();

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    private function calculateReadTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / 200)); // 200 words per minute
    }
}
