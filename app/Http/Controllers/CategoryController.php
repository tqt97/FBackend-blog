<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function posts(Request $request, Category $category)
    {
        $posts = $category->load(['posts.user', 'posts.categories', 'posts.tags', 'posts.comments'])
            ->posts()
            ->published()
            ->paginate(25);

        return view('frontend.posts.category-post', [
            'posts' => $posts,
            'category' => $category,
        ]);
    }
}
