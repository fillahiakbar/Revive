<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\AnimeDisplayController;

// ===============================
// ROUTES: AUTH (Forgot/Reset Password)
// ===============================
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// ===============================
// ROUTES: Email Verification
// ===============================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect()->route('revive');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function () {
    Auth::user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// RSS
Route::get('/rss/{slug}.xml', [RssController::class, 'show']);

// ===============================
// ROUTES: App (butuh login + verified)
// ===============================
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/', [WelcomeController::class, 'index'])->name('revive');

    Route::get('/about',   [WelcomeController::class, 'about'])->name('about');
    Route::get('/terms',   [WelcomeController::class, 'terms'])->name('terms');
    Route::get('/cookies', [WelcomeController::class, 'cookies'])->name('cookies');
    Route::get('/privacy', [WelcomeController::class, 'privacy'])->name('privacy');

    // Comments
    Route::post('/comments/{id}/like',  [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{id}/reply', [CommentController::class, 'reply'])->name('comments.reply');

    // Anime basic
    Route::get('/list',                 [AnimeListController::class, 'list'])->name('anime.list');
    Route::get('/anime/mal/{mal_id}',   [AnimeDetailController::class, 'show'])->name('anime.show');

    // Genres
    Route::get('/genres',               [GenreController::class, 'genres'])->name('anime.genres');

    Route::get('/anime/genre/{slug}',   [GenreController::class, 'byGenre'])->name('anime.by-genre');


    // Advanced & statuses
    Route::get('/advanced-search', [AnimeGenreController::class, 'genreMulti'])->name('anime.genre.multi');
    Route::get('/ongoing',   [AnimeOngoingController::class, 'index'])->name('anime.ongoing');
    Route::get('/completed', [AnimeCompletedController::class, 'index'])->name('anime.completed');

    // Legacy redirect
    Route::get('/anime/2569', fn () => redirect('/anime/mal/2569', 301));

    // Search
    Route::get('/search',       [AnimeController::class, 'search'])->name('anime.search');
    Route::get('/autocomplete', [AnimeController::class, 'autocomplete'])->name('anime.autocomplete');

    // Comments create
    Route::post('/anime/{anime_link}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Display
    Route::prefix('anime-display')->group(function () {
        Route::get('/anime-tab', [AnimeDisplayController::class, 'showTabbed'])->name('anime.tabbed');
    });
});

// ===============================
// ROUTES: Admin (blok akses langsung)
// ===============================
Route::get('/admin', fn () => abort(403, 'Access to admin is not allowed.'));
Route::get('/admin/login', fn () => abort(403, 'Access denied.'));
