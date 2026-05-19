<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [StoryController::class, 'welcome'])->name('welcome');

Route::get('/dashboard', [StoryController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Cerita (Story)
    Route::get('/stories/create', [StoryController::class, 'create'])->name('stories.create');
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{story}', [StoryController::class, 'show'])->name('stories.show');
    Route::put('/stories/{story}', [StoryController::class, 'update'])->name('stories.update');

    // Rute Komentar
    Route::post('/stories/{story}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Rute Riwayat Versi Cerita (Version History)
    Route::get('/stories/{story}/versions', [StoryController::class, 'getVersions'])->name('stories.versions');
    Route::post('/stories/{story}/versions/{version}/restore', [StoryController::class, 'restoreVersion'])->name('stories.versions.restore');
});

require __DIR__.'/auth.php';
