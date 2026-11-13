<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::with(['category', 'user', 'school'])
            ->published()
            ->approved();

        // Apply role-based filters
        $user = auth()->user();

        if ($user->hasRole('school-admin')) {
            // School admin can only see videos from their school
            $query->where('school_id', $user->school_id);
        } elseif (!$user->hasRole('super-admin')) {
            // For other roles (like regular users), only show their own videos
            $query->where('user_id', $user->id);
        }

        // Super-admin sees all videos (no additional filter)

        // Apply filters
        if ($request->has('category') && $request->category != 'all') {
            $query->byCategory($request->category);
        }

        if ($request->has('school') && $request->school != 'all') {
            $query->bySchool($request->school);
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

        // Add search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        $videos = $query->paginate(12);
        $categories = VideoCategory::where('status', 'active')->get();

        // Get schools based on role
        if ($user->hasRole('super-admin')) {
            $schools = School::where('status', 'active')->get();
        } elseif ($user->hasRole('school-admin')) {
            $schools = School::where('id', $user->school_id)->where('status', 'active')->get();
        } else {
            // Regular users see videos from all active schools
            $schools = School::where('status', 'active')->get();
        }

        // Calculate statistics based on role
        $statsQuery = Video::published()->approved();

        if ($user->hasRole('school-admin')) {
            $statsQuery->where('school_id', $user->school_id);
        } elseif (!$user->hasRole('super-admin')) {
            $statsQuery->where('user_id', $user->id);
        }

        $totalViews = $statsQuery->sum('views');
        $featuredCount = $statsQuery->featured()->count();
        $myVideosCount = Video::where('user_id', $user->id)->count();

        return view('dashboard.videos.index', compact(
            'videos',
            'categories',
            'schools',
            'totalViews',
            'featuredCount',
            'myVideosCount'
        ));
    }

    public function show($slug)
    {
        $user = auth()->user();
        $video = Video::with(['category', 'user', 'school', 'comments.user', 'comments.replies.user'])
            ->where('slug', $slug)
            ->published()
            ->approved()
            ->firstOrFail();

        // Check if user has permission to view this video
        if (!$this->canViewVideo($user, $video)) {
            abort(403, 'You do not have permission to view this video.');
        }

        // Increment views
        // $video->increment('views');

        // Get related videos
        $relatedQuery = Video::where('category_id', $video->category_id)
            ->where('id', '!=', $video->id)
            ->published()
            ->approved();

        if ($user->hasRole('school-admin')) {
            $relatedQuery->where('school_id', $user->school_id);
        } elseif (!$user->hasRole('super-admin')) {
            $relatedQuery->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere('visibility', 'public');
            });
        }

        $relatedVideos = $relatedQuery
            ->with(['category', 'user', 'school'])
            ->limit(6)
            ->get();

        return view('dashboard.videos.show', compact('video', 'relatedVideos'));
    }

    public function create()
    {
        // Gate::authorize('create', Video::class);

        $user = auth()->user();
        $categories = VideoCategory::where('status', 'active')->get();

        // Get schools based on role
        if ($user->hasRole('super-admin')) {
            $schools = School::where('status', 'active')->get();
        } elseif ($user->hasRole('school-admin')) {
            // School admin can only upload to their school
            $schools = School::where('id', $user->school_id)->where('status', 'active')->get();
        } else {
            // Regular users can upload without a school association
            $schools = School::where('status', 'active')->get();
        }

        return view('dashboard.videos.create', compact('categories', 'schools'));
    }

    public function store(Request $request)
    {
        // Gate::authorize('create', Video::class);


        $user = auth()->user();

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'embed_video_link' => ['required', 'regex:/^<iframe.*<\/iframe>$/s'],
            'category_id' => 'nullable|exists:video_categories,id',
            'is_featured' => 'boolean',
            'status' => 'in:draft,published,private',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Apply role-based restrictions
        if ($user->hasRole('school-admin')) {
            // School admin can only upload to their school
            $validated['school_id'] = $user->school_id;
        }

        // dd($validated);
        $validated['user_id'] = $user->id;
        $validated['published_at'] = $request->status === 'published' ? now() : null;
        $validated['slug'] = Str::slug($request->title) . '-' . Str::random(6);

        // Set default visibility based on role
        $validated['visibility'] = 'public'; // Default, can be changed if needed

        $video = Video::create($validated);

        return redirect()->route('admin.videos.show', $video->slug)
            ->with('success', 'Video uploaded successfully!');
    }

    public function edit(Video $video)
    {
        // Gate::authorize('update', $video);

        $user = auth()->user();

        // Check if user has permission to edit this video
        if (!$this->canEditVideo($user, $video)) {
            abort(403, 'You do not have permission to edit this video.');
        }

        $categories = VideoCategory::where('status', 'active')->get();

        // Get schools based on role
        if ($user->hasRole('super-admin')) {
            $schools = School::where('status', 'active')->get();
        } elseif ($user->hasRole('school-admin')) {
            $schools = School::where('id', $user->school_id)->where('status', 'active')->get();
        } else {
            // Regular users
            $schools = School::where('status', 'active')->get();
        }

        return view('dashboard.videos.edit', compact('video', 'categories', 'schools'));
    }

    public function update(Request $request, Video $video)
    {
        // Gate::authorize('update', $video);

        $user = auth()->user();

        // Check if user has permission to update this video
        if (!$this->canEditVideo($user, $video)) {
            abort(403, 'You do not have permission to update this video.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'embed_video_link' => ['required', 'regex:/^<iframe.*<\/iframe>$/s'],
            'category_id' => 'nullable|exists:video_categories,id',
            'school_id' => 'nullable|exists:schools,id',
            'is_featured' => 'boolean',
            'status' => 'in:draft,published,private',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
        ]);

        // Apply role-based restrictions for school_id
        if ($user->hasRole('school-admin')) {
            // School admin can only update school_id to their school
            $validated['school_id'] = $user->school_id;
        }

        if ($video->status === 'draft' && $request->status === 'published') {
            $validated['published_at'] = now();
        }

        $video->update($validated);

        return redirect()->route('admin.videos.show', $video->slug)
            ->with('success', 'Video updated successfully!');
    }

    public function destroy(Video $video)
    {
        // Gate::authorize('delete', $video);

        $user = auth()->user();

        // Check if user has permission to delete this video
        if (!$this->canEditVideo($user, $video)) {
            abort(403, 'You do not have permission to delete this video.');
        }

        $video->delete();

        return redirect()->route('admin.videos.index')
            ->with('success', 'Video deleted successfully!');
    }

    public function myVideos(Request $request)
    {
        $user = auth()->user();

        $query = Video::where('user_id', $user->id)
            ->with(['category', 'school']);

        // Apply role-based filters
        if ($user->hasRole('school-admin')) {
            $query->where('school_id', $user->school_id);
        }

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
        $statsQuery = Video::where('user_id', $user->id);

        if ($user->hasRole('school-admin')) {
            $statsQuery->where('school_id', $user->school_id);
        }

        $totalViews = $statsQuery->sum('views');
        $publishedCount = $statsQuery->where('status', 'published')->count();
        $draftCount = $statsQuery->where('status', 'draft')->count();

        return view('dashboard.videos.my-videos', compact(
            'videos',
            'totalViews',
            'publishedCount',
            'draftCount'
        ));
    }

    /**
     * Check if user can view a specific video
     */
    private function canViewVideo($user, $video)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        if ($video->user_id === $user->id) {
            return true;
        }

        if ($user->hasRole('school-admin') && $video->school_id === $user->school_id) {
            return true;
        }

        // Check if video is public
        if ($video->visibility === 'public') {
            return true;
        }

        return false;
    }

    /**
     * Check if user can edit/delete a specific video
     */
    private function canEditVideo($user, $video)
    {
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // User can edit their own videos
        if ($video->user_id === $user->id) {
            return true;
        }

        // School admin can edit videos from their school
        if ($user->hasRole('school-admin') && $video->school_id === $user->school_id) {
            return true;
        }

        return false;
    }
}
