<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoCommentController extends Controller
{
    public function store(Request $request, Video $video)
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
        } else {
            $comment->name = $request->name;
            $comment->email = $request->email;
            // Guest comments may require approval
            $comment->is_approved = false;
        }

        $video->comments()->save($comment);
        $video->increment('comments_count');

        return redirect()->back()
            ->with('success', Auth::check() ? 'Comment added!' : 'Comment submitted for approval!');
    }

    public function like(VideoComment $comment)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $like = $comment->likes()->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $comment->decrement('likes_count');
            $liked = false;
        } else {
            $comment->likes()->create(['user_id' => Auth::id()]);
            $comment->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked' => $liked,
            'likes_count' => $comment->likes_count,
        ]);
    }

    public function destroy(VideoComment $comment)
    {
        if (!Auth::check() || (Auth::id() !== $comment->user_id && !Auth::user()->can('manage-videos'))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Decrement video comment count
        if ($comment->video) {
            $comment->video->decrement('comments_count');
        }

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
