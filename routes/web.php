<?php

use App\Enums\Feeling;
use App\Livewire\Home;
use App\Livewire\Profile;
use App\Livewire\SavedPostIndex;
use App\Livewire\ShowPost;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
    Route::get('/', Home::class)->name('home');
    Route::get('/{username}/post/{slug}', ShowPost::class)->name('post.show');
    Route::get('/saved-posts', SavedPostIndex::class)->name('savedPost.show');
    Route::get('/profile/{username}',Profile::class)->name('profile.show');
});

// Authentication routes
require __DIR__.'/auth.php';