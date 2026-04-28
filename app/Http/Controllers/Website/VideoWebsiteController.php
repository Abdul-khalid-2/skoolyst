<?php

namespace App\Http\Controllers\Website;

use App\Enums\VideoPublishStatus;
use App\Http\Controllers\Controller;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\School;
use App\Models\Shop;
use App\Models\VideoComment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoWebsiteController extends Controller
{
    public function index(Request $request)
    {
        $noindex = (bool) $request->attributes->get('video_category_noindex', false);

        $query = Video::with(['category', 'user', 'school.translations', 'shop'])
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

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // Get data
        $videos = $query->paginate(12);
        $categories = VideoCategory::where('status', 'active')->orderBy('name')->get();
        // Cap filter lists (sidebar) — not full table scans; main listing is $videos (paginated)
        $schools = School::where('status', 'active')
            ->with('translations')
            ->orderBy('name')
            ->limit(300)
            ->get();
        $shops = Shop::where('is_active', true)->orderBy('name')->limit(300)->get();

        // Popular videos for sidebar
        $popularVideos = Video::published()
            ->approved()
            ->popular()
            ->limit(5)
            ->get();

        // Featured videos
        $featuredVideos = Video::published()
            ->approved()
            ->featured()
            ->limit(3)
            ->get();

        // Statistics
        $totalVideos = Video::published()->approved()->count();
        $totalViews = Video::published()->approved()->sum('views');

        return view('website.videos.index', array_merge(
            compact(
                'videos',
                'categories',
                'schools',
                'shops',
                'popularVideos',
                'featuredVideos',
                'totalVideos',
                'totalViews',
                'noindex',
            ),
            ['pageSetsOwnCanonical' => true]
        ));
    }

    public function show($slug)
    {
        $video = Video::with(['category', 'user', 'school.translations', 'shop', 'comments.user', 'comments.replies.user'])
            ->where('slug', $slug)
            ->published()
            ->approved()
            ->firstOrFail();

        // Increment views
        $video->increment('views');

        // Get related videos
        $videoEager = ['category', 'user', 'school.translations', 'shop'];

        $relatedVideos = Video::with($videoEager)
            ->where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->published()
            ->approved()
            ->limit(6)
            ->get();

        // Get popular videos for sidebar
        $popularVideos = Video::with($videoEager)
            ->published()
            ->approved()
            ->where('id', '!=', $video->id)
            ->popular()
            ->limit(5)
            ->get();

        // Get featured videos
        $featuredVideos = Video::with($videoEager)
            ->published()
            ->approved()
            ->where('id', '!=', $video->id)
            ->featured()
            ->limit(3)
            ->get();

        return view('website.videos.show', compact(
            'video',
            'relatedVideos',
            'popularVideos',
            'featuredVideos'
        ));
    }

    public function trackWatchTime(Request $request, Video $video): JsonResponse
    {
        if (! $video->is_approved || $video->status !== VideoPublishStatus::Published) {
            abort(404);
        }

        $data = $request->validate([
            'seconds' => 'required|integer|min:1|max:120',
        ]);

        $video->increment('total_tracked_watch_minutes', $data['seconds'] / 60.0);

        $freshVideo = $video->fresh();
        $minutes = round((float) $freshVideo->total_tracked_watch_minutes, 4);
        $freshVideo->total_tracked_watch_minutes = $minutes;
        $freshVideo->saveQuietly();

        return response()->json([
            'ok' => true,
            'total_tracked_watch_minutes' => $minutes,
            'formatted_tracked_watch_time' => $freshVideo->formatted_tracked_watch_time,
        ]);
    }

    public function category(Request $request, string $slug)
    {
        $category = VideoCategory::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $hasPublishedVideos = Video::published()
            ->approved()
            ->where('category_id', $category->id)
            ->exists();

        $query = array_merge($request->query->all(), [
            'category' => (string) $category->id,
        ]);
        $newRequest = $request->duplicate($query);
        $newRequest->attributes->set('video_category_noindex', ! $hasPublishedVideos);

        app()->instance('request', $newRequest);

        return $this->index($newRequest);
    }

    public function storeComment(Request $request, Video $video)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:video_comments,id',
            'name' => 'required_if:user_id,null|string|max:255',
            'email' => 'required_if:user_id,null|email|max:255',
        ]);

        $comment = new VideoComment([
            'message' => $request->message,
            'parent_id' => $request->parent_id,
        ]);

        if (Auth::check()) {
            $comment->user_id = Auth::id();
            $comment->is_approved = true; // Auto-approve logged in users
        } else {
            $comment->name = $request->name;
            $comment->email = $request->email;
            $comment->is_approved = false; // Guest comments need approval
        }

        $video->comments()->save($comment);
        $video->increment('comments_count');

        return redirect()->back()
            ->with('success', Auth::check() ? 'Comment added!' : 'Comment submitted for approval!');
    }
}
