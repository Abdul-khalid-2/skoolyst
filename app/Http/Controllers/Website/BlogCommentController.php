<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogCommentController extends Controller
{
    public function store(Request $request, $postSlug)
    {
        $request->validate([
            'name' => 'required_if:user_id,null|string|max:255',
            'email' => 'required_if:user_id,null|email|max:255',
            'comment' => 'required|string|min:5|max:1000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $post = BlogPost::where('slug', $postSlug)->where('status', 'published')->firstOrFail();

        try {
            DB::beginTransaction();

            $commentData = [
                'blog_post_id' => $post->id,
                'comment' => $request->comment,
                'parent_id' => $request->parent_id,
                'status' => auth()->check() ? 'approved' : 'pending' // Auto-approve logged-in users
            ];

            if (auth()->check()) {
                $commentData['user_id'] = auth()->id();
                $commentData['name'] = auth()->user()->name;
                $commentData['email'] = auth()->user()->email;
            } else {
                $commentData['name'] = $request->name;
                $commentData['email'] = $request->email;
            }

            Comment::create($commentData);

            DB::commit();

            return back()->with(
                'success',
                auth()->check()
                    ? 'Comment posted successfully!'
                    : 'Comment submitted for review!'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to post comment. Please try again.');
        }
    }
}
