<?php

use Illuminate\Support\Facades\Route;
use Modules\BankCards\Http\Controllers\BankCardsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('bankcards', BankCardsController::class)->names('bankcards');
});
