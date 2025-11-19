<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WebsiteBlogPostController extends Controller
{
    /**
     * Display a listing of the blog posts.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now());

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Sort functionality
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('view_count', 'desc');
                break;
            case 'featured':
                $query->where('is_featured', true)->orderBy('published_at', 'desc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        $posts = $query->paginate(12);

        // Get categories with post counts
        $categories = BlogCategory::withCount(['blogPosts' => function ($query) {
            $query->where('status', 'published')
                ->where('published_at', '<=', now());
        }])->get();

        // Get popular posts
        $popularPosts = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        // Get tags (you might need to adjust this based on your tags implementation)
        $tags = BlogPost::where('status', 'published')
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->take(15);

        return view('website.blog.index', compact('posts', 'categories', 'popularPosts', 'tags'));
    }

    /**
     * Display the specified blog post.
     */
    public function show($slug)
    {
        $post = BlogPost::with(['user', 'category', 'comments'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->firstOrFail();

        // Increment view count
        $post->increment('view_count');

        // Get related posts
        $relatedPosts = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->where(function ($query) use ($post) {
                $query->where('blog_category_id', $post->category_id)
                    ->orWhere(function ($q) use ($post) {
                        if ($post->tags) {
                            foreach ($post->tags as $tag) {
                                $q->orWhereJsonContains('tags', $tag);
                            }
                        }
                    });
            })
            ->limit(3)
            ->get();

        // Get popular posts for sidebar
        $popularPosts = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        // Get categories
        $categories = BlogCategory::withCount(['blogPosts' => function ($query) {
            $query->where('status', 'published')
                ->where('published_at', '<=', now());
        }])->get();

        // Get tags
        $tags = BlogPost::where('status', 'published')
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->take(15);

        return view('website.blog.show', compact('post', 'relatedPosts', 'popularPosts', 'categories', 'tags'));
    }

    /**
     * Display posts by category.
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)->firstOrFail();

        $posts = BlogPost::with(['author', 'category'])
            ->where('blog_category_id', $category->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get categories with post counts
        $categories = BlogCategory::withCount(['posts' => function ($query) {
            $query->where('status', 'published')
                ->where('published_at', '<=', now());
        }])->get();

        // Get popular posts
        $popularPosts = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        // Get tags
        $tags = BlogPost::where('status', 'published')
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->take(15);

        return view('website.blog.category', compact('category', 'posts', 'categories', 'popularPosts', 'tags'));
    }

    /**
     * Display posts by tag.
     */
    public function tag($tag)
    {
        $posts = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereJsonContains('tags', $tag)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        // Get categories with post counts
        $categories = BlogCategory::withCount(['posts' => function ($query) {
            $query->where('status', 'published')
                ->where('published_at', '<=', now());
        }])->get();

        // Get popular posts
        $popularPosts = BlogPost::with(['author', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        // Get tags
        $tags = BlogPost::where('status', 'published')
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->unique()
            ->take(15);

        return view('website.blog.tag', compact('tag', 'posts', 'categories', 'popularPosts', 'tags'));
    }
}
