<?php

use Illuminate\Support\Facades\Route;
use Modules\BankAccounts\Http\Controllers\BankAccountsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // GET REQUESTS
    //  List all bank accounts owned by the authenticated customer.
    Route::get('accounts/', [BankAccountsController::class, 'getAllUserBankAccounts'])->name('bankcards.getAllUserBankAccounts');
    //  List a bank account owned by the authenticated customer.
    Route::get('accounts/{id}', [BankAccountsController::class, 'getBankAccountWithId'])->name('bankcards.getBankAccountWithId');

});
