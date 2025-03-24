<?php

use Illuminate\Support\Facades\Route;
use Modules\BankAccountTransactions\Http\Controllers\BankAccountTransactionsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('bankaccounttransactions', BankAccountTransactionsController::class)->names('bankaccounttransactions');
});
