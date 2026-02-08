<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\MasterDataController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ====================================================
// PUBLIC ROUTES
// ====================================================

// Redirect root ke login
Route::get('/', function () {
    if (Auth::check()) {
        // Jika user sudah login, arahkan sesuai role
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('cashier.index');
        }
    }
    // Jika belum login, baru lempar ke login
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
});

// ====================================================
// PROTECTED ROUTES (AUTH REQUIRED)
// ====================================================
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ------------------------------------------------
    // ADMIN ROUTES
    // ------------------------------------------------
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/history', [TransactionController::class, 'adminHistory'])->name('admin.history');
        // Master Data: Harga Sampah
        // Halaman Utama
        Route::get('/waste-prices', [MasterDataController::class, 'wastePrices'])->name('admin.waste-prices');
        
        // Action: Update Harga (Semua sekaligus)
        Route::post('/waste-prices/update-batch', [MasterDataController::class, 'updateWastePriceBatch'])->name('admin.waste-prices.update-batch');
        
        // Action: Tambah Modal
        Route::post('/cash-flow/store', [MasterDataController::class, 'storeCashModal'])->name('admin.cash-flow.store');
        
        // Master Data: Nasabah (Customers)
        Route::get('/customers', [MasterDataController::class, 'customers'])->name('admin.customers');
        Route::post('/customers', [MasterDataController::class, 'storeCustomer'])->name('admin.customers.store');
        Route::put('/customers/{id}', [MasterDataController::class, 'updateCustomer'])->name('admin.customers.update');
        Route::delete('/customers/{id}', [MasterDataController::class, 'deleteCustomer'])->name('admin.customers.delete');
        
        // Master Data: Pengguna (Users)
        Route::get('/users', [MasterDataController::class, 'users'])->name('admin.users');
        Route::post('/users', [MasterDataController::class, 'storeUser'])->name('admin.users.store');
        Route::put('/users/{id}', [MasterDataController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{id}', [MasterDataController::class, 'deleteUser'])->name('admin.users.delete');

        // Statistik
        Route::get('/stats', [\App\Http\Controllers\StatsController::class, 'index'])->name('admin.stats');
    });

    // ------------------------------------------------
    // CASHIER / TRANSACTION ROUTES
    // ------------------------------------------------
    // Note: Jika Admin juga boleh akses kasir, pastikan middleware-nya sesuai.
    // Di sini saya biarkan terbuka untuk semua yang auth (admin & cashier), 
    // atau kamu bisa tambahkan middleware khusus jika perlu.
    Route::middleware('role:cashier')->prefix('cashier')->group(function () {
        
        // Main Transaction Page
        Route::get('/', [TransactionController::class, 'index'])->name('cashier.index');
        Route::get('/history', [TransactionController::class, 'history'])->name('cashier.history');
        
        // API Routes (AJAX Operations)
        Route::prefix('api')->group(function () {
            Route::get('/reading', [TransactionController::class, 'getCurrentReading'])->name('cashier.api.reading');
            Route::post('/simulate', [TransactionController::class, 'simulateReading'])->name('cashier.api.simulate');
            Route::post('/lock-add', [TransactionController::class, 'lockAndAddToCart'])->name('cashier.api.lock-add');
            Route::get('/cart', [TransactionController::class, 'getCart'])->name('cashier.api.cart');
            Route::delete('/cart/{index}', [TransactionController::class, 'removeFromCart'])->name('cashier.api.cart.remove');
            Route::post('/checkout', [TransactionController::class, 'checkout'])->name('cashier.api.checkout');
        });
        
        // Print Receipt
        Route::get('/print/{id}', [TransactionController::class, 'printReceipt'])->name('cashier.print');

        Route::get('/customers', [MasterDataController::class, 'customers'])->name('cashier.customers');
        Route::prefix('api')->group(function () {
            // ... copy existing api routes ...
            Route::get('/reading', [TransactionController::class, 'getCurrentReading'])->name('cashier.api.reading');
            Route::post('/checkout', [TransactionController::class, 'checkout'])->name('cashier.api.checkout');
        });
    });

});