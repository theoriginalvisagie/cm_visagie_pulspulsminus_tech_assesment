<?php

use Illuminate\Support\Facades\Route;
use Modules\BankCards\Http\Controllers\BankCardsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // GET REQUESTS
    //  List all debit cards owned by the authenticated customer.
    Route::get('debit-cards/', [BankCardsController::class, 'getAllUserCards'])->name('bankcards.getAllUserCards');
    // Get details of a specific debit card.
    Route::get('debit-cards/{id}', [BankCardsController::class,'getUserCardWithId'])->name('bankcards.getUserCardWithId');

    // POST REQUESTS
    // Creates a new debit card
    Route::post('debit-cards', [BankCardsController::class, 'createNewCard'])->name('bankcards.createNewCard');

    // PUT REQUESTS
    // Update an existing debit card.
    Route::put('debit-cards/{id}', [BankCardsController::class, 'updateBankCard'])->name('destroy.updateBankCard');

    // DELETE REQUESTS
    // Delete a debit card.
    Route::delete('debit-cards/{id}', [BankCardsController::class, 'destroy'])->name('destroy.destroy');
});
