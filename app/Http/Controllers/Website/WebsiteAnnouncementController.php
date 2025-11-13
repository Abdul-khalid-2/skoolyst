<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\AnnouncementComment;
use Illuminate\Http\Request;

class WebsiteAnnouncementController extends Controller
{
    public function show($uuid)
    {
        $announcement = Announcement::with([
            'school',
            'branch',
            'comments' => function ($query) {
                $query->where('status', 'approved')
                    ->orderBy('created_at', 'desc');
            },
            'comments.user'
        ])->where('uuid', $uuid)->firstOrFail();

        // Increment view count
        $announcement->incrementViewCount();

        // Get related announcements from the same school
        $relatedAnnouncements = Announcement::where('school_id', $announcement->school_id)
            ->where('id', '!=', $announcement->id)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('publish_at')
                    ->orWhere('publish_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('expire_at')
                    ->orWhere('expire_at', '>=', now());
            })
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        return view('website.announcement_show', compact('announcement', 'relatedAnnouncements'));
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
