<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\AccountController;
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
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MetalController;
use App\Http\Controllers\Admin\GemstoneController;
use App\Http\Controllers\Admin\RingSizeController;
use App\Http\Controllers\Admin\ColourController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\MenuItemController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\BlogController;

require __DIR__ . '/auth.php';

// ── Homepage — explicit top-level route, must come before Larkon catch-all ────
Route::get('/', [ShopController::class, 'home'])->name('shop.home');

// Sitemap (public, no auth, outside shop prefix so URL is /sitemap.xml)
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// ── Shop (customer-facing) routes ─────────────────────────────────────────────
Route::prefix('/')->name('shop.')->group(function () {

    // Jewellery browse (homepage is registered above as a standalone route)
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

    // Account (auth-protected — middleware applied in AccountController constructor)
    Route::get('/account',                         [AccountController::class, 'index'])->name('account.index');
    Route::get('/account/orders',                  [AccountController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}',          [AccountController::class, 'orderShow'])->name('account.order');
    Route::get('/account/addresses',               [AccountController::class, 'addresses'])->name('account.addresses');
    Route::post('/account/addresses',              [AccountController::class, 'addressStore'])->name('account.addresses.store');
    Route::patch('/account/addresses/{address}',   [AccountController::class, 'addressUpdate'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}',  [AccountController::class, 'addressDestroy'])->name('account.addresses.destroy');
    Route::get('/account/profile',                 [AccountController::class, 'profile'])->name('account.profile');
    Route::patch('/account/profile',               [AccountController::class, 'profileUpdate'])->name('account.profile.update');
    Route::post('/account/avatar',                 [AccountController::class, 'avatarUpload'])->name('account.avatar.upload');
    Route::delete('/account/avatar',               [AccountController::class, 'avatarDelete'])->name('account.avatar.delete');

    // Search
    Route::get('/search', [ShopController::class, 'search'])->name('search');

    // Static pages
    Route::get('/faq', [ShopController::class, 'faq'])->name('faq');
    Route::get('/about', [ShopController::class, 'about'])->name('about');
    Route::get('/contact', [ShopController::class, 'contact'])->name('contact');
    Route::post('/contact', [ShopController::class, 'contactStore'])->name('contact.store')->middleware('throttle:contact');
    Route::get('/privacy', [ShopController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [ShopController::class, 'terms'])->name('terms');

    // Newsletter subscribe (POST)
    Route::post('/newsletter', [ShopController::class, 'newsletterSubscribe'])->name('newsletter.subscribe');

    // Currency switcher (POST)
    Route::post('/currency', [ShopController::class, 'switchCurrency'])->name('currency.switch');
});

// ── Blog (public, outside shop.* name prefix so routes are blog.index / blog.show)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

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

    // Content management
    Route::resource('sliders', SliderController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::post('faqs/settings', [FaqController::class, 'updateSettings'])->name('faqs.settings');
    Route::resource('faqs', FaqController::class);

    // Attribute management
    Route::resource('metals', MetalController::class);
    Route::resource('gemstones', GemstoneController::class);
    Route::get('ring-sizes', [RingSizeController::class, 'index'])->name('ring-sizes.index');
    Route::resource('colours', ColourController::class);

    // Page content editors
    Route::prefix('pages')->name('pages.')->group(function () {
        Route::get('home', [PageController::class, 'home'])->name('home');
        Route::post('home', [PageController::class, 'updateHome'])->name('home.update');
        Route::get('about', [PageController::class, 'about'])->name('about');
        Route::post('about', [PageController::class, 'updateAbout'])->name('about.update');
        Route::get('contact', [PageController::class, 'contact'])->name('contact');
        Route::post('contact', [PageController::class, 'updateContact'])->name('contact.update');
        Route::get('terms', [PageController::class, 'terms'])->name('terms');
        Route::post('terms', [PageController::class, 'updateTerms'])->name('terms.update');
        Route::get('privacy', [PageController::class, 'privacy'])->name('privacy');
        Route::post('privacy', [PageController::class, 'updatePrivacy'])->name('privacy.update');
    });

    // Blog posts management
    Route::resource('posts', PostController::class);

    // Menu management
    Route::resource('menus', MenuController::class);
    Route::post('menus/{menu}/items', [MenuItemController::class, 'store'])->name('menu-items.store');
    Route::put('menus/{menu}/items/{item}', [MenuItemController::class, 'update'])->name('menu-items.update');
    Route::delete('menus/{menu}/items/{item}', [MenuItemController::class, 'destroy'])->name('menu-items.destroy');
    Route::post('menus/{menu}/items/reorder', [MenuItemController::class, 'reorder'])->name('menu-items.reorder');

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

// Larkon catch-all — renders Blade views by path segment for admin panel pages.
// Route::get('') (empty string → URI '/') is intentionally removed; the shop
// homepage handles '/' explicitly above and must never be caught here.
Route::group(['prefix' => '/', 'middleware' => ['auth', 'admin']], function () {
    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');
});
