<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AnimeDetailController;
use App\Http\Controllers\AnimeListController;
use App\Http\Controllers\AnimeGenreController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\RssController;
use App\Http\Controllers\AnimeOngoingController;
use App\Http\Controllers\AnimeCompletedController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\RegisterController;


// Landing redirect ke user
Route::get('/', function () {
    return redirect()->route('revive');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/revive', [WelcomeController::class, 'index'])->name('revive');
    Route::get('/about', [WelcomeController::class, 'about'])->name('about');
    Route::get('/terms', [WelcomeController::class, 'terms'])->name('terms');
    Route::get('/cookies', [WelcomeController::class, 'cookies'])->name('cookies');
    Route::get('/privacy', [WelcomeController::class, 'privacy'])->name('privacy');

    Route::get('/list', [AnimeListController::class, 'list'])->name('anime.list');
    Route::get('/anime/{id}', [AnimeDetailController::class, 'show'])->name('anime.show');
    Route::get('/genres/{slug}', [GenreController::class, 'show'])->name('genre.show');

    Route::get('/genres', [GenreController::class, 'genres'])->name('anime.genres');
    Route::get('/anime/genre/{genre_id}', [AnimeGenreController::class, 'byGenre'])->name('anime.by-genre');
    Route::get('/advanced-search', [AnimeGenreController::class, 'genreMulti'])->name('anime.genre.multi');
    Route::get('/ongoing', [AnimeOngoingController::class, 'index'])->name('anime.ongoing');
    Route::get('/completed', [AnimeCompletedController::class, 'index'])->name('anime.completed');

    Route::get('/search', [AnimeController::class, 'search'])->name('anime.search');
    Route::post('/anime/{anime_link}/comments', [CommentController::class, 'store'])->name('comments.store');

    Route::get('/rss/{slug}.xml', [RssController::class, 'show']);
    

});

