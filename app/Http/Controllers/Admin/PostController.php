<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    public function show(Post $post)
    {
        return view('frontend.posts.show', compact('post'));
    }
}
