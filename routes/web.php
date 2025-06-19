<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnimeDetailController;
use App\Http\Controllers\AnimeListController;
use App\Http\Controllers\AnimeGenreController;
use App\Http\Controllers\GenreController;


// Landing redirect ke user
Route::get('/', function () {
    return redirect()->route('revive');
});

// Route user biasa (Jetstream login)
Route::middleware(['auth'])->group(function () {
    Route::get('/revive', [WelcomeController::class, 'index'])->name('revive');
    Route::get('/about', [WelcomeController::class, 'about'])->name('about');

    Route::get('/list', [AnimeListController::class, 'list'])->name('anime.list');
    Route::get('/anime/{id}', [AnimeDetailController::class, 'show'])->name('anime.show');

    Route::get('/genres', [GenreController::class, 'genres'])->name('anime.genres');
    Route::get('/anime/genre/{genre_id}', [AnimeGenreController::class, 'byGenre'])->name('anime.by-genre');
    Route::get('/advenced-search', [AnimeGenreController::class, 'genreMulti'])->name('anime.genre.multi');

    Route::get('/search', [AnimeController::class, 'search'])->name('anime.search');
});


