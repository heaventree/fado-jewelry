<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\ProductController;

require __DIR__ . '/auth.php';

// Admin routes — real controllers, must come before the catch-all below
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('collections', CollectionController::class);

    // Currency management — custom routes (not a standard CRUD resource)
    Route::get('currencies', [CurrencyController::class, 'index'])->name('currencies.index');
    Route::post('currencies', [CurrencyController::class, 'store'])->name('currencies.store');
    Route::patch('currencies/{currency}/rate', [CurrencyController::class, 'updateRate'])->name('currencies.update-rate');
    Route::delete('currencies/{currency}', [CurrencyController::class, 'destroy'])->name('currencies.destroy');
});

// Larkon catch-all — renders Blade views by path segment (unauthenticated views excluded)
Route::group(['prefix' => '/', 'middleware' => 'auth'], function () {
    Route::get('', [RoutingController::class, 'index'])->name('root');
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
