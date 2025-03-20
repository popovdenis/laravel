<?php

use Illuminate\Support\Facades\Route;
use TomatoPHP\FilamentCms\Models\Post;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/blog', function () {
    $posts = Post::where('is_published', 1)->latest()->get();
    return view('blog.index', compact('posts'));
});
Route::get('/blog/{post}', function (Post $post) {
    return view('blog.show', compact('post'));
})->name('blog.show');
