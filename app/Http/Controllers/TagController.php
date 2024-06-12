<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function posts(Tag $tag)
    {
        $posts = $tag->load(['posts.user'])
            ->posts()
            ->published()
            ->paginate(25);

        return view('frontend.posts.tag-post', [
            'posts' => $posts,
            'tag' => $tag,
        ]);
    }
}
