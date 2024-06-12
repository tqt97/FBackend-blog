<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'comment' => 'required|min:3|max:500',
        ]);

        $post->comments()->create([
            'comment' => $request->comment,
            'user_id' => $request->user()->id,
            'approved' => false,
        ]);

        return redirect()
            ->route('post.show', $post)
            ->with('success', 'Comment submitted for approval.');
    }
}
