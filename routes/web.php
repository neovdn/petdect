<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MasterDataController;

// Public Routes
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        // Master Data
        Route::get('/waste-prices', [MasterDataController::class, 'wastePrices'])->name('admin.waste-prices');
        Route::post('/waste-prices/{waste_type}', [MasterDataController::class, 'updateWastePrice'])->name('admin.waste-prices.update');
        
        Route::get('/customers', [MasterDataController::class, 'customers'])->name('admin.customers');
        Route::post('/customers', [MasterDataController::class, 'storeCustomer'])->name('admin.customers.store');
        Route::put('/customers/{id}', [MasterDataController::class, 'updateCustomer'])->name('admin.customers.update');
        Route::delete('/customers/{id}', [MasterDataController::class, 'deleteCustomer'])->name('admin.customers.delete');
        
        Route::get('/users', [MasterDataController::class, 'users'])->name('admin.users');
        Route::post('/users', [MasterDataController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/users/{id}', [MasterDataController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [MasterDataController::class, 'deleteUser'])->name('admin.users.delete');
    });

    // Cashier Routes (Admin juga bisa akses)
    Route::prefix('cashier')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('cashier.index');
        Route::get('/history', [TransactionController::class, 'history'])->name('cashier.history');
        
        // API Routes untuk AJAX
        Route::get('/api/reading', [TransactionController::class, 'getCurrentReading'])->name('cashier.api.reading');
        Route::post('/api/simulate', [TransactionController::class, 'simulateReading'])->name('cashier.api.simulate');
        Route::post('/api/lock-add', [TransactionController::class, 'lockAndAddToCart'])->name('cashier.api.lock-add');
        Route::get('/api/cart', [TransactionController::class, 'getCart'])->name('cashier.api.cart');
        Route::delete('/api/cart/{index}', [TransactionController::class, 'removeFromCart'])->name('cashier.api.cart.remove');
        Route::post('/api/checkout', [TransactionController::class, 'checkout'])->name('cashier.api.checkout');
        
        // Print Receipt
        Route::get('/print/{id}', [TransactionController::class, 'printReceipt'])->name('cashier.print');
    });
});