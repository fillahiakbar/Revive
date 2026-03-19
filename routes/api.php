<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/referral/track', [\App\Http\Controllers\ReferralController::class, 'trackClick']);
Route::get('/leaderboard', [\App\Http\Controllers\ReferralController::class, 'getLeaderboard']);
