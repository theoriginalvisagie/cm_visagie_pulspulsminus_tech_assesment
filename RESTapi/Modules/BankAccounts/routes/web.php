<?php

use Illuminate\Support\Facades\Route;
use Modules\BankAccounts\Http\Controllers\BankAccountsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('bankaccounts', BankAccountsController::class)->names('bankaccounts');
});
