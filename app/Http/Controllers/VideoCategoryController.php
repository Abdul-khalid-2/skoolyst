<?php

namespace App\Http\Controllers;

use App\Models\VideoCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class VideoCategoryController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', VideoCategory::class);

        $categories = VideoCategory::orderBy('sort_order')->paginate(20);
        return view('videos.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', VideoCategory::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:video_categories',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);

        VideoCategory::create($validated);

        return redirect()->route('video-categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function update(Request $request, VideoCategory $videoCategory)
    {
        Gate::authorize('update', $videoCategory);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:video_categories,name,' . $videoCategory->id,
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'sort_order' => 'integer',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($request->name);

        $videoCategory->update($validated);

        return redirect()->route('video-categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(VideoCategory $videoCategory)
    {
        Gate::authorize('delete', $videoCategory);

        if ($videoCategory->videos()->count() > 0) {
            return redirect()->route('video-categories.index')
                ->with('error', 'Cannot delete category with videos. Please reassign videos first.');
        }

        $videoCategory->delete();

        return redirect()->route('video-categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
