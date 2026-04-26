<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementComment;
use App\Models\School;
use App\Services\ImageWebpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnnouncementController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $announcements = Announcement::with(['school', 'branch'])
            ->forSchool($schoolId)
            ->latest()
            ->paginate(10);

        return view('dashboard.announcements.index', compact('announcements'));
    }

    public function create()
    {
        $school = School::findOrFail(auth()->user()->school_id);
        $branches = $school->branches()->where('status', 'active')->get();

        return view('dashboard.announcements.create', compact('branches'));
    }

    public function store(Request $request, ImageWebpService $imageWebp)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'publish_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after:publish_at',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $school = auth()->user()->school; // assuming relation exists
        $folderName = \Str::slug($school->name) ?? 'school-' . $school->id;

        $data = $request->except('feature_image');
        $data['school_id'] = $school->id;
        $data['content'] = $this->sanitizeAnnouncementContent($request->content);

        // ✅ Handle feature image upload (stored under website disk)
        if ($request->hasFile('feature_image')) {
            $imagePath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/announcements", $request->file('feature_image'));
            $data['feature_image'] = $imagePath;
        }

        Announcement::create($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully.');
    }

    public function show($uuid)
    {
        $announcement = Announcement::with(['school', 'branch', 'comments.user'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Increment view count
        // $announcement->incrementViewCount();

        return view('dashboard.announcements.show', compact('announcement'));
    }

    public function edit($uuid)
    {
        $announcement = Announcement::where('uuid', $uuid)->firstOrFail();
        $school = School::findOrFail(auth()->user()->school_id);
        $branches = $school->branches()->where('status', 'active')->get();

        return view('dashboard.announcements.edit', compact('announcement', 'branches'));
    }

    public function update(Request $request, $uuid, ImageWebpService $imageWebp)
    {
        $announcement = Announcement::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'feature_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'publish_at' => 'nullable|date',
            'expire_at' => 'nullable|date|after:publish_at',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ]);

        $school = auth()->user()->school;
        $folderName = \Str::slug($school->name) ?? 'school-' . $school->id;

        $data = $request->except('feature_image');
        $data['content'] = $this->sanitizeAnnouncementContent($request->content);

        if ($request->hasFile('feature_image')) {
            // Delete old image
            if ($announcement->feature_image && Storage::disk('website')->exists($announcement->feature_image)) {
                Storage::disk('website')->delete($announcement->feature_image);
            }

            $imagePath = $imageWebp->putUploadedAsWebp('website', "school/{$folderName}/announcements", $request->file('feature_image'));
            $data['feature_image'] = $imagePath;
        }

        $announcement->update($data);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    /**
     * Sanitize CKEditor content without requiring Mews/Purifier.
     */
    private function sanitizeAnnouncementContent($content)
    {
        // Allow common markup used by CKEditor while stripping dangerous tags
        $allowedTags = '<p><br><strong><b><em><i><u><ul><ol><li><a><img><h1><h2><h3><h4><h5><h6><blockquote><pre><code>';

        $clean = strip_tags($content, $allowedTags);

        // Remove XSS vectors in attributes and javascript: URLs.
        $clean = preg_replace('/(<[^>]+)(on\w+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+))/i', '$1', $clean);
        $clean = preg_replace('/(href|src)\s*=\s*("|\')?\s*javascript:[^"\'>\s]*/i', '$1=""', $clean);

        // Remove any remaining <script> and <style> blocks.
        $clean = preg_replace('/<script[^>]*>.*?<\\/script>/is', '', $clean);
        $clean = preg_replace('/<style[^>]*>.*?<\\/style>/is', '', $clean);

        return $clean;
    }

    public function destroy($uuid)
    {
        $announcement = Announcement::where('uuid', $uuid)->firstOrFail();

        // Delete feature image
        if ($announcement->feature_image) {
            Storage::disk('public')->delete($announcement->feature_image);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }

    public function storeComment(Request $request, $uuid)
    {
        $announcement = Announcement::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'name' => 'required_if:user_id,null|string|max:255',
            'email' => 'required_if:user_id,null|email|max:255',
            'comment' => 'required|string|max:1000',
        ]);

        $data = [
            'announcement_id' => $announcement->id,
            'comment' => $request->comment,
            'status' => auth()->check() ? 'approved' : 'pending',
        ];

        if (auth()->check()) {
            $data['user_id'] = auth()->id();
            $data['name'] = auth()->user()->name;
            $data['email'] = auth()->user()->email;
        } else {
            $data['name'] = $request->name;
            $data['email'] = $request->email;
        }

        AnnouncementComment::create($data);

        return redirect()->back()
            ->with('success', auth()->check() ? 'Comment added successfully.' : 'Comment submitted for review.');
    }
}
