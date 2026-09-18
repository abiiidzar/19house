<?php

use App\Http\Controllers\Cashier\PosController;
use App\Http\Controllers\Cashier\TransactionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:cashier'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::view('/dashboard', 'cashier.dashboard')->name('dashboard');
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}/receipt', [TransactionController::class, 'show'])->name('receipt.show');
});
