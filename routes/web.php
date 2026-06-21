<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialiteController;
use App\Livewire\Test;
use Illuminate\Support\Facades\Route;

Route::get("/", function () {
    return view("welcome");
});

// spatie media library example
Route::get("/photo", Test::class)->name("test");

Route::get("/posts", [PostController::class, "index"])->name("post.index");

// socialite
Route::get("/auth/{provider}/redirect", [
    SocialiteController::class,
    "redirect",
])->name("socialite.redirect");
Route::get("/auth/{provider}/callback", [
    SocialiteController::class,
    "callback",
])->name("socialite.callback");
