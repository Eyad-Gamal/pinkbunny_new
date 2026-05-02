<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController, ProductController, BrandController,
    CartController, WishlistController, OrderController,
    ProfileController, SettingsController, AboutController,
    NewsletterController, ReviewController, EgyptController,
};
use App\Http\Controllers\Admin;

// ===================================================
// PUBLIC ROUTES
// ===================================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/brands', [BrandController::class, 'index'])->name('brands.index');
Route::get('/brands/{brand:slug}', [BrandController::class, 'show'])->name('brands.show');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/egypt/areas', [EgyptController::class, 'areas'])->name('egypt.areas');

// Dashboard redirect — admin goes to admin panel, customer goes to home
Route::get('/dashboard', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');

// ===================================================
// AUTHENTICATED ROUTES
// ===================================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/',              [CartController::class, 'index'])->name('index');
        Route::post('/add',          [CartController::class, 'add'])->name('add');
        Route::put('/update/{id}',   [CartController::class, 'update'])->name('update');
        Route::delete('/remove/{id}',[CartController::class, 'remove'])->name('remove');
    });

    Route::post('/coupon/apply', [CartController::class, 'applyCoupon'])->name('coupon.apply');

    // Wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        Route::get('/',        [WishlistController::class, 'index'])->name('index');
        Route::post('/toggle', [WishlistController::class, 'toggle'])->name('toggle');
    });

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/',     [OrderController::class, 'index'])->name('index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('show');
    });

    // Checkout
    Route::get('/checkout',  [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('order.place');

    // Notifications
    Route::get('/notifications', fn() => view('notifications.index', [
        'notifications' => auth()->user()->notifications()->paginate(20)
    ]))->name('notifications.index');
    Route::post('/notifications/{id}/read', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');
    Route::post('/notifications/read-all', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('toast', 'All notifications marked as read.');
    })->name('notifications.read-all');

    // Profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/',                        [ProfileController::class, 'index'])->name('index');
        Route::match(['put','patch'], '/',     [ProfileController::class, 'update'])->name('update');
        Route::post('/avatar',                 [ProfileController::class, 'uploadAvatar'])->name('avatar');
        Route::get('/addresses',               [ProfileController::class, 'addresses'])->name('addresses');
        Route::post('/addresses',              [ProfileController::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{id}',          [ProfileController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{id}',       [ProfileController::class, 'deleteAddress'])->name('addresses.delete');
        Route::post('/addresses/{id}/default', [ProfileController::class, 'setDefaultAddress'])->name('addresses.default');
    });

    // Settings
    Route::get('/settings',  [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings',  [SettingsController::class, 'update'])->name('settings.update');

    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Account
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ===================================================
// ADMIN ROUTES
// ===================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/',                    [Admin\ProductController::class, 'index'])->name('index');
        Route::get('/create',              [Admin\ProductController::class, 'create'])->name('create');
        Route::post('/',                   [Admin\ProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit',           [Admin\ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}',                [Admin\ProductController::class, 'update'])->name('update');
        Route::delete('/{id}',             [Admin\ProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore',       [Admin\ProductController::class, 'restore'])->name('restore');
        Route::post('/{id}/toggle-active', [Admin\ProductController::class, 'toggleActive'])->name('toggle-active');
    });

    // Brands
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/',                    [Admin\BrandController::class, 'index'])->name('index');
        Route::post('/',                   [Admin\BrandController::class, 'store'])->name('store');
        Route::put('/{id}',                [Admin\BrandController::class, 'update'])->name('update');
        Route::delete('/{id}',             [Admin\BrandController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle-active', [Admin\BrandController::class, 'toggleActive'])->name('toggle-active');
    });

    // Categories
    Route::resource('categories', Admin\CategoryController::class)->except(['show']);

    // Orders
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/',              [Admin\OrderController::class, 'index'])->name('index');
        Route::get('/export/excel',  [Admin\OrderController::class, 'export'])->name('export');
        Route::get('/{id}',          [Admin\OrderController::class, 'show'])->name('show');
        Route::get('/{id}/invoice',  [Admin\OrderController::class, 'invoice'])->name('invoice');
        Route::put('/{id}/status',   [Admin\OrderController::class, 'updateStatus'])->name('update-status');
    });

    // Coupons
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/',             [Admin\CouponController::class, 'index'])->name('index');
        Route::post('/',            [Admin\CouponController::class, 'store'])->name('store');
        Route::put('/{id}',         [Admin\CouponController::class, 'update'])->name('update');
        Route::delete('/{id}',      [Admin\CouponController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [Admin\CouponController::class, 'toggle'])->name('toggle');
        Route::get('/{id}/usages',  [Admin\CouponController::class, 'usages'])->name('usages');
    });

    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/',                    [Admin\UserController::class, 'index'])->name('index');
        Route::get('/{id}',                [Admin\UserController::class, 'show'])->name('show');
        Route::post('/{id}/toggle-active', [Admin\UserController::class, 'toggleActive'])->name('toggle-active');
    });

    // Slides (Hero Banner)
    Route::prefix('slides')->name('slides.')->group(function () {
        Route::get('/',            [Admin\SlideController::class, 'index'])->name('index');
        Route::get('/create',      [Admin\SlideController::class, 'create'])->name('create');
        Route::post('/',           [Admin\SlideController::class, 'store'])->name('store');
        Route::get('/{id}/edit',   [Admin\SlideController::class, 'edit'])->name('edit');
        Route::put('/{id}',        [Admin\SlideController::class, 'update'])->name('update');
        Route::delete('/{id}',     [Admin\SlideController::class, 'destroy'])->name('destroy');
    });

    // Reviews
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/',              [Admin\ReviewController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [Admin\ReviewController::class, 'approve'])->name('approve');
        Route::delete('/{id}',       [Admin\ReviewController::class, 'destroy'])->name('destroy');
    });
});

require __DIR__.'/auth.php';
