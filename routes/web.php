<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;

require __DIR__ . '/auth.php';

// Admin routes — real controllers, must come before the catch-all below
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('collections', CollectionController::class);

    // Order management
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

    // Consultation enquiry inbox
    Route::get('consultations', [ConsultationController::class, 'index'])->name('consultations.index');
    Route::get('consultations/{consultation}', [ConsultationController::class, 'show'])->name('consultations.show');
    Route::delete('consultations/{consultation}', [ConsultationController::class, 'destroy'])->name('consultations.destroy');

    // Currency management — custom routes (not a standard CRUD resource)
    Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::post('currencies', [CurrencyController::class, 'store'])->name('currencies.store');
    Route::patch('currencies/{currency}/rate', [CurrencyController::class, 'updateRate'])->name('currencies.update-rate');
    Route::delete('currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
});

// Invoices — intercept before the Larkon catch-all
Route::get('general/invoice/list', [InvoiceController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.invoices.index');
Route::get('general/invoice/{order}', [InvoiceController::class, 'show'])->middleware(['auth', 'admin'])->name('admin.invoices.show');

// Inventory — intercept before the Larkon catch-all
Route::get('general/inventory/warehouse', [InventoryController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.inventory.index');
Route::get('general/inventory/received-orders', [InventoryController::class, 'lowStock'])->middleware(['auth', 'admin'])->name('admin.inventory.low-stock');
Route::patch('admin/inventory/{variant}/stock', [InventoryController::class, 'updateStock'])->middleware(['auth', 'admin'])->name('admin.inventory.update-stock');
Route::post('admin/inventory/bulk-stock', [InventoryController::class, 'bulkUpdateStock'])->middleware(['auth', 'admin'])->name('admin.inventory.bulk-stock');

// Customers — intercept before the Larkon catch-all
Route::get('users/customer/list', [CustomerController::class, 'index'])->middleware(['auth', 'admin'])->name('admin.customers.index');
Route::get('users/customer/{customer}', [CustomerController::class, 'show'])->middleware(['auth', 'admin'])->name('admin.customers.show');

// Dashboard — intercepts before the Larkon catch-all
Route::get('dashboards/index', [DashboardController::class, 'index'])->middleware(['auth', 'admin'])->name('dashboard');

// Larkon catch-all — renders Blade views by path segment (unauthenticated views excluded)
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
