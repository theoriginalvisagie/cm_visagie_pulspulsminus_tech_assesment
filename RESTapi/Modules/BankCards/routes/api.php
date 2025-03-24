<?php

use Illuminate\Support\Facades\Route;
use Modules\BankCards\Http\Controllers\BankCardsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // GET REQUESTS
    Route::get('debit-cards/{id}', [BankCardsController::class, 'getAllUserCards'])->name('bankcards.getAllUserCards');
//    Route::get('debit-cards/{id}', BankCardsController::class)->name('bankcards.getAllUserCards');

    // POST REQUESTS
    Route::post('debit-cards', [BankCardsController::class, 'createNewCard'])->name('bankcards.createNewCard');
//
//    // PUT REQUESTS
//    Route::put('debit-cards/{id}', BankCardsController::class)->name('bankcards');
//
//    // DELETE REQUESTS
//    Route::delete('debit-cards/{id}', BankCardsController::class)->name('bankcards');
});
