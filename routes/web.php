<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\AnimeController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminRegisterController;
use App\Http\Controllers\Admin\AdminController;

// Landing redirect ke user
Route::get('/', function () {
    return redirect()->route('revive');
});

// Route user biasa (Jetstream login)
Route::middleware(['auth'])->group(function () {
    Route::get('/revive', [WelcomeController::class, 'index'])->name('revive');
    Route::get('/about', [WelcomeController::class, 'about'])->name('about');
    Route::get('/list', [AnimeController::class, 'list'])->name('anime.list');
    Route::get('/genres', [AnimeController::class, 'genres'])->name('anime.genres');
    Route::get('/anime/{id}', [AnimeController::class, 'show'])->name('anime.show');
    Route::get('/anime/genre/{genre_id}', [AnimeController::class, 'byGenre'])->name('anime.by-genre');
    Route::get('/advenced-search', [AnimeController::class, 'genreMulti'])->name('anime.genre.multi');
    Route::get('/api/anime-by-genres', [AnimeController::class, 'animeByGenresJson']);
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
    });
});
