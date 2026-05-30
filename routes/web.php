<?php

use App\Livewire\Test;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// spatie media library example
Route::get('/photo', Test::class)->name('test');
