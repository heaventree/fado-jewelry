<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\WishlistController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CollectionController;
use App\Http\Controllers\Admin\ConsultationController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;

require __DIR__ . '/auth.php';

// ── Shop (customer-facing) routes ─────────────────────────────────────────────
Route::prefix('/')->name('shop.')->group(function () {

    // Homepage
    Route::get('/', [ShopController::class, 'home'])->name('home');

    // Jewellery browse
    Route::get('/jewellery', [ShopController::class, 'jewellery'])->name('jewellery');
    Route::get('/jewellery/{category}', [ShopController::class, 'category'])->name('category');

    // Collections
    Route::get('/collections', [ShopController::class, 'collections'])->name('collections');
    Route::get('/collections/{slug}', [ShopController::class, 'collection'])->name('collection');

    // Individual product
    Route::get('/products/{product:slug}', [ShopController::class, 'product'])->name('product');

    // Cart
    Route::get('/cart', [CartController::class, 'show'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout (no auth middleware — guest checkout supported)
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'place'])->name('checkout.place');
    Route::get('/order/{order}/confirmation', [CheckoutController::class, 'confirmation'])->name('order.confirmation');

    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'show'])->name('wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/remove', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Account
    Route::get('/account', [ShopController::class, 'account'])->name('account.index')->middleware('auth');

    // Static pages
    Route::get('/about', [ShopController::class, 'about'])->name('about');
    Route::get('/contact', [ShopController::class, 'contact'])->name('contact');
    Route::get('/privacy', [ShopController::class, 'privacy'])->name('privacy');

    // Search
    Route::get('/shop/search', [ShopController::class, 'search'])->name('search');

    // Newsletter subscribe (POST)
    Route::post('/newsletter', [ShopController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

    // Currency switcher (POST)
    Route::post('/currency', [ShopController::class, 'switchCurrency'])->name('currency.switch');
});

// ── Admin routes — real controllers, must come before the Larkon catch-all ───
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

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::patch('settings', [SettingController::class, 'update'])->name('settings.update');

    // Coupon management
    Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create');
    Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
    Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
    Route::patch('coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
    Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::patch('coupons/{coupon}/toggle', [CouponController::class, 'toggleActive'])->name('coupons.toggle');

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
