<?php

use Illuminate\Support\Facades\Route;
use Modules\BankAccountTransactions\Http\Controllers\BankAccountTransactionsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('bankaccounttransactions', BankAccountTransactionsController::class)->names('bankaccounttransactions');
});
