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

// Route admin
Route::prefix('admin')->group(function () {
    // Login
    Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

    // Register
    Route::get('register', [AdminRegisterController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('register', [AdminRegisterController::class, 'register'])->name('admin.register.submit');

    // Dashboard admin (butuh auth:admin guard)
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('downloads/{mal_id}', [AdminController::class, 'manageDownloadLinks'])->name('admin.download.manage');
        Route::post('downloads/{mal_id}', [AdminController::class, 'storeDownloadLink'])->name('admin.download.store');
        Route::delete('downloads/{link}', [AdminController::class, 'deleteDownloadLink'])->name('admin.download.delete');
        Route::post('spotlight', [AdminController::class, 'createSpotlight'])->name('admin.spotlight.store');
        Route::delete('spotlight/{spotlight}', [AdminController::class, 'deleteSpotlight'])->name('admin.spotlight.delete');
        Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');
    });


    Route::middleware(['auth:admin'])->prefix('admin/episodes')->name('admin.episodes.')->group(function () {
        Route::get('/{mal_id}', [AdminEpisodeController::class, 'index'])->name('index');
        Route::get('/{mal_id}/create', [AdminEpisodeController::class, 'create'])->name('create');
        Route::post('/', [AdminEpisodeController::class, 'store'])->name('store');
        Route::get('/edit/{episode}', [AdminEpisodeController::class, 'edit'])->name('edit');
        Route::put('/{episode}', [AdminEpisodeController::class, 'update'])->name('update');
        Route::delete('/{episode}', [AdminEpisodeController::class, 'destroy'])->name('destroy');
    });
});
