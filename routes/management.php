<?php

use App\Http\Controllers\Management\DashboardController;
use App\Http\Controllers\Management\ReportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:management'])->prefix('management')->name('management.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/products', [ReportController::class, 'products'])->name('reports.products');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/customers', [ReportController::class, 'customers'])->name('reports.customers');
});
