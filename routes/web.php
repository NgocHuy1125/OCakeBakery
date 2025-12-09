<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\AuthController;
use App\Http\Controllers\Client\ProfileController as ClientProfileController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\PaymentController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\CheckoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\OfflineOrderController;
use App\Http\Controllers\Admin\RestockController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Client\ForgotPasswordController;
use App\Http\Controllers\Client\PaymentQrController;


/* -------------------- CLIENT ROUTES -------------------- */
Route::get('/', [ClientController::class, 'client_home']);
Route::get('/contact', [ClientController::class, 'client_contact']);
Route::post('/contact/send', [ClientController::class, 'send'])->name('client.contact.send');

Route::get('/about-us', [ClientController::class, 'client_about']);
Route::get('/promotions', [ClientController::class, 'client_promotions']);
Route::get('/store', [ClientController::class, 'client_store']);
Route::get('/promotions', [ClientController::class, 'client_promotions'])->name('client.promotions');

Route::get('/products', [ClientProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ClientProductController::class, 'show'])->name('products.show');
Route::get('/search', [ClientProductController::class, 'search'])->name('products.search');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::match(['post', 'patch'], '/cart/items/{item}', [CartController::class, 'updateQuantity'])->name('cart.items.update');
Route::delete('/cart/items/{item}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

Route::view('/payment/success', 'pages.client.paymentSuccess');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('client.password.forgot');
Route::post('/forgot-password/send', [ForgotPasswordController::class, 'sendOtp'])->name('client.password.sendOtp');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtp'])->name('client.password.verify');

Route::get('/api/hcm/wards', [ClientProfileController::class, 'getHcmWards']);


/* -------------------- ADMIN ROUTES -------------------- */
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        $user = auth()->user();

        if ($user && $user->role === 'staff') {
            return redirect()->route('admin.orders.index');
        }

        return redirect()->route('admin.dashboard');
    })->middleware('role:admin,staff');

    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);
        Route::put('orders/{order}/quick-process', [AdminOrderController::class, 'quickProcess'])->name('orders.quickProcess');

        Route::get('offline-orders', [OfflineOrderController::class, 'index'])->name('offline-orders.index');
        Route::post('offline-orders', [OfflineOrderController::class, 'store'])->name('offline-orders.store');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Danh mục & sản phẩm
        Route::resource('categories', AdminCategoryController::class)->except(['show', 'create', 'edit']);
        Route::resource('products', AdminProductController::class)->except(['show']);

        // Các module khác
        Route::resource('promotions', AdminPromotionController::class)->except(['show']);
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::get('restock', [RestockController::class, 'index'])->name('restock.index');
        Route::post('restock', [RestockController::class, 'store'])->name('restock.store');
        Route::get('restock/{id}', [RestockController::class, 'show'])->name('restock.show');
        Route::resource('users', AdminUserController::class)->except(['show', 'create', 'edit']);

        Route::get('contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::patch('contacts/{contact}/read', [AdminContactController::class, 'markAsRead'])->name('contacts.markAsRead');
        Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
    });
});

/* -------------------- AUTH ROUTES -------------------- */
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('client.auth.register');
Route::post('/logout', [AuthController::class, 'logout'])->name('client.auth.logout');
Route::post('/login', [AuthController::class, 'processLogin'])->name('client.login.process');
Route::post('/register', [AuthController::class, 'processRegister'])->name('client.register.process');

/* -------------------- CLIENT PROFILE ROUTES -------------------- */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ClientProfileController::class, 'home'])->name('profile.home');
    Route::post('/profile', [ClientProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/addresses', [ClientProfileController::class, 'addresses'])->name('profile.addresses');
    Route::post('/profile/addresses', [ClientProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::delete('/profile/addresses/{address}', [ClientProfileController::class, 'deleteAddress'])->name('profile.addresses.delete');
    Route::get('/profile/addresses/wards/{district}', [ClientProfileController::class, 'wards'])->name('profile.addresses.wards');

    Route::get('/profile/orders', [ClientOrderController::class, 'myOrders'])->name('profile.orders');
    Route::get('/orders/{code}', [ClientOrderController::class, 'show'])->name('profile.orders.show');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Route::get('/payment/qr', [PaymentController::class, 'qr'])->name('payment.qr');

    Route::post('/notifications/mark-read/{id}', [NotificationController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all', [NotificationController::class, 'markAll'])->name('notifications.markAll');
    Route::post('/profile/change-password', [ClientProfileController::class, 'changePassword'])->name('profile.password.update');
    Route::get('/payment/qr/{code}', [PaymentQrController::class, 'show'])->name('payment.qr');
    Route::get('/payment/status/{code}', [PaymentQrController::class, 'status'])->name('payment.status');

});
