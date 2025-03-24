<?php

use Illuminate\Support\Facades\Route;
use Modules\BankCards\Http\Controllers\BankCardsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bankcards', BankCardsController::class)->names('bankcards');
});
