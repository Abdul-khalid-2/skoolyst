<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebsiteBlogPostController extends Controller
{
    private function sidebarCategories()
    {
        return BlogCategory::where('is_active', true)
            ->whereHas('blogPosts', function ($query) {
                $query->published();
            })
            ->withCount(['blogPosts as posts_count' => function ($query) {
                $query->published();
            }])
            ->orderBy('name')
            ->get();
    }

    private function popularTags()
    {
        return BlogPost::published()
            ->whereNotNull('tags')
            ->pluck('tags')
            ->flatten()
            ->filter(fn ($tag) => is_string($tag) && trim($tag) !== '')
            ->unique()
            ->take(15)
            ->values();
    }

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

        // Get only active categories that have published posts.
        $categories = $this->sidebarCategories();

        // Get popular posts
        $popularPosts = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        $tags = $this->popularTags();

        return view('website.blog.index', array_merge(
            compact('posts', 'categories', 'popularPosts', 'tags'),
            ['pageSetsOwnCanonical' => true]
        ));
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
                $query->where('blog_category_id', $post->blog_category_id)
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

        $categories = $this->sidebarCategories();

        $tags = $this->popularTags();

        return view('website.blog.show', compact('post', 'relatedPosts', 'popularPosts', 'categories', 'tags'));
    }

    /**
     * Accumulate active-tab time (client sends whole seconds; stored as sum of minutes in DB).
     */
    public function trackReadingTime(Request $request, BlogPost $post): JsonResponse
    {
        if ($post->status !== 'published' || ! $post->published_at || $post->published_at->isFuture()) {
            abort(404);
        }

        $data = $request->validate([
            'seconds' => 'required|integer|min:1|max:120',
        ]);

        $add = $data['seconds'] / 60.0;
        $post->increment('total_tracked_read_minutes', $add);

        $f = $post->fresh();
        $min = round((float) $f->total_tracked_read_minutes, 4);
        $f->total_tracked_read_minutes = $min;
        $f->saveQuietly();

        return response()->json([
            'ok' => true,
            'total_tracked_read_minutes' => $min,
        ]);
    }

    /**
     * Display posts by category.
     */
    public function category($slug)
    {
        $category = BlogCategory::where('slug', $slug)
            ->where('is_active', true)
            ->whereHas('blogPosts', function ($query) {
                $query->published();
            })
            ->firstOrFail();

        $posts = BlogPost::with(['user', 'category'])
            ->where('blog_category_id', $category->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = $this->sidebarCategories();

        // Get popular posts
        $popularPosts = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        $tags = $this->popularTags();

        return view('website.blog.category', compact('category', 'posts', 'categories', 'popularPosts', 'tags'));
    }

    /**
     * Display posts by tag.
     */
    public function tag($tag)
    {
        $posts = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->whereJsonContains('tags', $tag)
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        $categories = $this->sidebarCategories();

        // Get popular posts
        $popularPosts = BlogPost::with(['user', 'category'])
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('view_count', 'desc')
            ->limit(5)
            ->get();

        $tags = $this->popularTags();

        return view('website.blog.tag', compact('tag', 'posts', 'categories', 'popularPosts', 'tags'));
    }
}
