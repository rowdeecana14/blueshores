<?php

use App\Http\Controllers\AlbumController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AlbumController::class, 'index'])->name('dashboard');
    Route::post('album/{album}/upvote', [AlbumController::class, 'upvote'])->name('album.upvote');
    Route::post('album/{album}/downvote', [AlbumController::class, 'downvote'])->name('album.downvote');
    Route::delete('/albums/{album}', [AlbumController::class, 'destroy'])->name('album.destroy')->middleware('is_admin');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
