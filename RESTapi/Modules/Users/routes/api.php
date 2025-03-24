<?php

use Illuminate\Support\Facades\Route;
use Modules\Users\Http\Controllers\UsersController;

Route::middleware(['throttle:60,1'])->group(function () {
    // POST REQUESTS
    ROUTE::post('login-user', [UsersController::class, 'loginUser']);
    ROUTE::post('register-user', [UsersController::class, 'registerUser']);
});
