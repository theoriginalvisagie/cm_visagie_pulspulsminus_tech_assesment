<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('users', UsersController::class)->names('users');
});

//VerifyApiToken::class,
Route::middleware(['throttle:60,1'])->group(function () {
    // GET REQUESTS
    ROUTE::post('login-user', [UsersController::class, 'loginUser']);
    ROUTE::post('register-user', [UsersController::class, 'registerUser']);
//    Route::get('game-new',[GameScoreController::class, 'newGame']);
//    Route::get('game-score',[GameScoreController::class, 'index']);
//    Route::get('game-scoreboard',[GameScoreController::class, 'index']);
//
//    // POST REQUESTS
//    Route::post('game-score-p1',[GameScoreController::class, 'player1Point']);
//    Route::post('game-score-p2',[GameScoreController::class, 'player2Point']);
//    Route::post('game-complete',[GameScoreController::class, 'completeGame']);
});
