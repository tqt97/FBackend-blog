<?php

use App\Http\Controllers\Frontend\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/{post:slug}', [PostController::class, 'show'])->name('post.show');
