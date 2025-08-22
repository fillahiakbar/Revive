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
use Filament\Facades\Filament;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\AnimeDisplayController;





// di routes/web.php

// ===============================
// ROUTES UNTUK FORGET PASSWORD
// ===============================

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
});


// ===============================
// ROUTES UNTUK VERIFIKASI EMAIL
// ===============================

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/revive');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function () {
    Auth::user()->sendEmailVerificationNotification();

    return back()->with('message', 'Link verifikasi telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// ===============================
// ROUTES UTAMA APLIKASI
// ===============================

// Landing redirect ke user
Route::get('/', function () {
    return redirect()->route('revive');
});

// RSS
Route::get('/rss/{slug}.xml', [RssController::class, 'show']);

// Route hanya untuk user yang login dan email terverifikasi
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/revive', [WelcomeController::class, 'index'])->name('revive');
    Route::get('/about', [WelcomeController::class, 'about'])->name('about');
    Route::get('/terms', [WelcomeController::class, 'terms'])->name('terms');
    Route::get('/cookies', [WelcomeController::class, 'cookies'])->name('cookies');
    Route::get('/privacy', [WelcomeController::class, 'privacy'])->name('privacy');
    

    // routes/web.php
    Route::post('/comments/{id}/like', [CommentController::class, 'like'])->name('comments.like');
    Route::post('/comments/{id}/reply', [CommentController::class, 'reply'])->name('comments.reply');


    Route::get('/list', [AnimeListController::class, 'list'])->name('anime.list');
    Route::get('/anime/mal/{mal_id}', [AnimeDetailController::class, 'show'])->name('anime.show');
    
    
    Route::get('/genres/{slug}', [GenreController::class, 'show'])->name('genre.show');

    Route::get('/genres', [GenreController::class, 'genres'])->name('anime.genres');
    Route::get('/anime/genre/{genre_id}', [AnimeGenreController::class, 'byGenre'])->name('anime.by-genre');
    Route::get('/advanced-search', [AnimeGenreController::class, 'genreMulti'])->name('anime.genre.multi');
    Route::get('/ongoing', [AnimeOngoingController::class, 'index'])->name('anime.ongoing');
    Route::get('/completed', [AnimeCompletedController::class, 'index'])->name('anime.completed');

Route::get('/anime/2569', function () {
    return redirect('/anime/mal/2569', 301);
});
    Route::get('/search', [AnimeController::class, 'search'])->name('anime.search');
    Route::post('/anime/{anime_link}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::get('/autocomplete', [AnimeController::class, 'autocomplete'])->name('anime.autocomplete');

Route::prefix('anime-display')->group(function () {
    Route::get('/anime-tab', [AnimeDisplayController::class, 'showTabbed'])->name('anime.tabbed');

});




    
});

// ===============================
// ROUTE LOGIN ADMIN FILAMENT
// ===============================



// Redirect admin/login ke custom login path
// Custom login URL (tampilkan login Filament)
Route::get('/admin', function () {
    abort(403, 'Access to /admin is not allowed.');
});

// Blok akses ke /admin/login
Route::get('/admin/login', function () {
    abort(403, 'Access denied.');
});