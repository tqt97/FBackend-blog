<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        // SEOMeta::setTitle('Blog | ' . config('app.name'));

        $posts = Post::query()->with(['categories', 'user', 'tags'])
            ->published()
            ->paginate(10);

        // dd($posts);
        return view('frontend.posts.index', [
            'posts' => $posts,
        ]);
    }

    public function allPosts()
    {
        // SEOMeta::setTitle('All posts | ' . config('app.name'));

        $posts = Post::query()->with(['categories', 'user'])
            ->published()
            ->paginate(20);

        return view('frontend.posts.all-post', [
            'posts' => $posts,
        ]);
    }

    public function search(Request $request)
    {
        // SEOMeta::setTitle('Search result for ' . $request->get('query'));

        $request->validate([
            'query' => 'required',
        ]);
        $searchedPosts = Post::query()
            ->with(['categories', 'user'])
            ->published()
            ->whereAny(['title', 'description'], 'like', '%'.$request->get('query').'%')
            ->paginate(10)->withQueryString();

        return view('frontend.posts.search', [
            'posts' => $searchedPosts,
            'searchMessage' => 'Search result for '.$request->get('query'),
        ]);
    }

    public function show(Post $post)
    {
        $post->with(['categories', 'user', 'tags', 'comments']);

        return view('frontend.posts.show', [
            'post' => $post,
        ]);
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:news_letters,email',
        ], [
            'email.unique' => 'You have already subscribed',
        ]);
        NewsLetter::create([
            'email' => $request->email,
        ]);

        return back()->with('success', 'You have successfully subscribed to our news letter');
    }
}
