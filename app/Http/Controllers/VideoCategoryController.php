<?php

namespace App\Http\Controllers;

use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class VideoCategoryController extends Controller
{
    public function index()
    {
        // Gate::authorize('viewAny', VideoCategory::class);

        $categories = VideoCategory::withCount('videos')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('dashboard.videos.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        // Gate::authorize('create', VideoCategory::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:video_categories,name',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Generate unique slug
        $validated['slug'] = $this->generateUniqueSlug($request->name);

        VideoCategory::create($validated);

        return redirect()->route('video-categories.index')
            ->with('success', 'Video category created successfully!');
    }

    public function edit(VideoCategory $videoCategory)
    {
        // Gate::authorize('update', $videoCategory);

        // Get all categories for parent selection (excluding current category)
        $categories = VideoCategory::where('id', '!=', $videoCategory->id)
            ->orderBy('name')
            ->get();

        return view('dashboard.videos.categories.edit', compact('videoCategory', 'categories'));
    }

    public function update(Request $request, VideoCategory $videoCategory)
    {
        // Gate::authorize('update', $videoCategory);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:video_categories,name,' . $videoCategory->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // Regenerate slug only if name changed
        if ($request->name !== $videoCategory->name) {
            $validated['slug'] = $this->generateUniqueSlug($request->name, $videoCategory->id);
        }

        $videoCategory->update($validated);

        return redirect()->route('video-categories.index')
            ->with('success', 'Video category updated successfully!');
    }

    public function destroy(VideoCategory $videoCategory)
    {
        // Gate::authorize('delete', $videoCategory);

        if ($videoCategory->videos()->count() > 0) {
            return redirect()->route('video-categories.index')
                ->with('error', 'Cannot delete category with videos. Please reassign or delete videos first.');
        }

        $videoCategory->delete();

        return redirect()->route('video-categories.index')
            ->with('success', 'Video category deleted successfully!');
    }

    /**
     * Generate a unique slug for the category
     */
    private function generateUniqueSlug(string $name, int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists
        $query = VideoCategory::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;

            $query = VideoCategory::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    /**
     * Bulk actions (optional)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:video_categories,id',
        ]);

        $action = $request->action;
        $ids = $request->ids;

        switch ($action) {
            case 'activate':
                VideoCategory::whereIn('id', $ids)->update(['status' => 'active']);
                $message = 'Categories activated successfully!';
                break;

            case 'deactivate':
                VideoCategory::whereIn('id', $ids)->update(['status' => 'inactive']);
                $message = 'Categories deactivated successfully!';
                break;

            case 'delete':
                // Check if any category has videos
                $categoriesWithVideos = VideoCategory::whereIn('id', $ids)
                    ->whereHas('videos')
                    ->count();

                if ($categoriesWithVideos > 0) {
                    return redirect()->route('video-categories.index')
                        ->with('error', 'Some categories have videos and cannot be deleted.');
                }

                VideoCategory::whereIn('id', $ids)->delete();
                $message = 'Categories deleted successfully!';
                break;
        }

        return redirect()->route('video-categories.index')
            ->with('success', $message);
    }

    /**
     * Update sort order (for drag & drop functionality)
     */
    public function updateSortOrder(Request $request)
    {
        $request->validate([
            'categories' => 'required|array',
            'categories.*.id' => 'required|exists:video_categories,id',
            'categories.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->categories as $category) {
            VideoCategory::where('id', $category['id'])
                ->update(['sort_order' => $category['sort_order']]);
        }

        return response()->json(['success' => true]);
    }
}
