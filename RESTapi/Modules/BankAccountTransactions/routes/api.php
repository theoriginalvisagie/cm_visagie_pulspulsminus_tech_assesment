<?php

use Illuminate\Support\Facades\Route;
use Modules\BankAccountTransactions\Http\Controllers\BankAccountTransactionsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // GET REQUESTS
    // List all transactions for the authenticated customer's debit cards.
    Route::get('debit-card-transactions/', [BankAccountTransactionsController::class, 'getAllUserTransactions'])->name('bankaccounttransactions.getAllUserTransactions');
    //  List a bank account owned by the authenticated customer.
    Route::get('/debit-card-transactions/{id}', [BankAccountTransactionsController::class, 'getTransactionWithID'])->name('bankaccounttransactions.getTransactionWithID');

    // POST REQUESTS
    // Create a transaction for a debit card.
    Route::post('debit-card-transactions/', [BankAccountTransactionsController::class, 'createNewTransaction'])->name('bankaccounttransactions.createNewTransaction');
});
