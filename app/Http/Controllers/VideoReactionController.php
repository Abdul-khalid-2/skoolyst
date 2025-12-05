<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoReaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoReactionController extends Controller
{
    public function store(Request $request, Video $video)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $request->validate([
            'reaction' => 'required|in:like,love,haha,wow,sad,angry',
        ]);

        $existingReaction = $video->reactions()
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReaction) {
            if ($existingReaction->reaction === $request->reaction) {
                // Remove reaction if same
                $existingReaction->delete();
                $video->decrement('likes_count');
                $reaction = null;
            } else {
                // Update reaction
                $existingReaction->update(['reaction' => $request->reaction]);
                $reaction = $request->reaction;
            }
        } else {
            // Create new reaction
            $video->reactions()->create([
                'user_id' => Auth::id(),
                'reaction' => $request->reaction,
            ]);
            $video->increment('likes_count');
            $reaction = $request->reaction;
        }

        return response()->json([
            'reaction' => $reaction,
            'likes_count' => $video->likes_count,
        ]);
    }
}
