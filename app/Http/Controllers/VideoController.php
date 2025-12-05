<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\School;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::with(['category', 'user', 'school', 'shop'])
            ->published()
            ->approved();

        // Apply filters
        if ($request->has('category') && $request->category != 'all') {
            $query->byCategory($request->category);
        }

        if ($request->has('school') && $request->school != 'all') {
            $query->bySchool($request->school);
        }

        if ($request->has('shop') && $request->shop != 'all') {
            $query->byShop($request->shop);
        }

        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'featured':
                    $query->featured();
                    break;
                case 'popular':
                    $query->popular();
                    break;
                case 'recent':
                    $query->recent();
                    break;
            }
        }

        $videos = $query->paginate(12);
        $categories = VideoCategory::where('status', 'active')->get();
        $schools = School::where('status', 'active')->get();
        $shops = Shop::where('is_active', true)->get();

        // Calculate statistics
        $totalViews = Video::published()->approved()->sum('views');
        $featuredCount = Video::published()->approved()->featured()->count();
        $myVideosCount = auth()->check() ? Video::where('user_id', auth()->id())->count() : 0;

        return view('dashboard.videos.index', compact(
            'videos',
            'categories',
            'schools',
            'shops',
            'totalViews',
            'featuredCount',
            'myVideosCount'
        ));
    }

    public function show($slug)
    {
        $video = Video::with(['category', 'user', 'school', 'shop', 'comments.user', 'comments.replies.user'])
            ->where('slug', $slug)
            ->published()
            ->approved()
            ->firstOrFail();

        // Increment views
        $video->increment('views');

        // Get related videos
        $relatedVideos = Video::where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->published()
            ->approved()
            ->limit(6)
            ->get();

        return view('videos.show', compact('video', 'relatedVideos'));
    }

    public function create()
    {
        Gate::authorize('create', Video::class);

        $categories = VideoCategory::where('status', 'active')->get();
        $schools = School::where('status', 'active')->get();
        $shops = Shop::where('is_active', true)->get();

        return view('videos.create', compact('categories', 'schools', 'shops'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Video::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'embed_video_link' => 'required|url',
            'category_id' => 'nullable|exists:video_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'shop_id' => 'nullable|exists:shops,id',
            'is_featured' => 'boolean',
            'status' => 'in:draft,published,private',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['published_at'] = $request->status === 'published' ? now() : null;
        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(6);

        $video = Video::create($validated);

        return redirect()->route('videos.show', $video->slug)
            ->with('success', 'Video uploaded successfully!');
    }

    public function edit(Video $video)
    {
        Gate::authorize('update', $video);

        $categories = VideoCategory::where('status', 'active')->get();
        $schools = School::where('status', 'active')->get();
        $shops = Shop::where('is_active', true)->get();

        return view('videos.edit', compact('video', 'categories', 'schools', 'shops'));
    }

    public function update(Request $request, Video $video)
    {
        Gate::authorize('update', $video);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'embed_video_link' => 'required|url',
            'category_id' => 'nullable|exists:video_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'shop_id' => 'nullable|exists:shops,id',
            'is_featured' => 'boolean',
            'status' => 'in:draft,published,private',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        if ($video->status === 'draft' && $request->status === 'published') {
            $validated['published_at'] = now();
        }

        $video->update($validated);

        return redirect()->route('videos.show', $video->slug)
            ->with('success', 'Video updated successfully!');
    }

    public function destroy(Video $video)
    {
        Gate::authorize('delete', $video);

        $video->delete();

        return redirect()->route('videos.index')
            ->with('success', 'Video deleted successfully!');
    }

    public function myVideos(Request $request)
    {
        $query = Video::where('user_id', auth()->id())
            ->with(['category', 'school', 'shop']);

        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('featured') && $request->featured != '') {
            $query->where('is_featured', $request->featured);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Apply sorting
        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'views':
                $query->orderBy('views', 'desc');
                break;
            case 'likes':
                $query->orderBy('likes_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $videos = $query->paginate(15);

        // Calculate statistics
        $totalViews = Video::where('user_id', auth()->id())->sum('views');
        $publishedCount = Video::where('user_id', auth()->id())->where('status', 'published')->count();
        $draftCount = Video::where('user_id', auth()->id())->where('status', 'draft')->count();

        return view('dashboard.videos.my-videos', compact(
            'videos',
            'totalViews',
            'publishedCount',
            'draftCount'
        ));
    }
}
