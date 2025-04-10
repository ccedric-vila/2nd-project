<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ManageReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SalesAnalyticsController;

use App\Mail\WelcomeEmail;
use Illuminate\Support\Facades\Mail;


Route::get('/send-test-email', function () {
    $user = (object) [
        'name' => 'John Doe',
        'email' => 'test@example.com', // Mailtrap will intercept this
    ];

    Mail::to($user->email)->send(new WelcomeEmail($user, '123456'));
    return "Email sent to Mailtrap!";
});
Route::get('/', [LandingController::class, 'index'])->name('welcome');


// Authentication Routes
Auth::routes(['register' => false, 'login' => false]); // Disable default auth routes

// Custom Register Routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);



// Custom Login Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);

// Logout Route
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    //Produc Crud Routes
    Route::resource('products', ProductController::class)
        ->except(['show']) // If you don't want the show route
        ->names([
            'index' => 'product.index',
            'create' => 'product.create',
            'store' => 'product.store',
            'edit' => 'product.edit',
            'update' => 'product.update',
            'destroy' => 'product.destroy'
        ]);

    // Product Routes (Fixed: Now includes create & store)
    Route::get('products', [ProductController::class, 'index'])->name('product.index'); // Note: 
    Route::get('products/import', [ProductController::class, 'showImportForm'])->name('products.import');
    Route::post('products/import', [ProductController::class, 'import'])->name('products.import.submit');

    //Supplier CRUD

    // First resource (with 'suppliers' URL)
    Route::resource('suppliers', SupplierController::class)
        ->names([
            'index' => 'admin.supplier.index',
            'create' => 'admin.supplier.create',
            'store' => 'admin.supplier.store',
            'edit' => 'admin.supplier.edit',
            'update' => 'admin.supplier.update',
            'destroy' => 'admin.supplier.destroy'
        ])
        ->except(['show']); // Exclude 'show'

    // Second resource (with 'supplier' URL)
    Route::resource('supplier', SupplierController::class)
        ->except(['show']); // Also exclude 'show'

    // Import routes (unchanged)
    Route::get('suppliers/import', [SupplierController::class, 'showImportForm'])->name('suppliers.import'); 
    Route::post('suppliers/import', [SupplierController::class, 'import'])->name('suppliers.import.process');
    
    // User Routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('users/{user}/status', [UserController::class, 'updateStatus'])->name('users.updateStatus');
    Route::post('users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/accept', [OrderController::class, 'accept'])->name('orders.accept');
    Route::post('/orders/{order}/deliver', [OrderController::class, 'deliver'])->name('orders.deliver');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Review Routes
    Route::get('reviews', [ManageReviewController::class, 'index'])->name('reviews.index');
    Route::get('reviews/{review}', [ManageReviewController::class, 'show'])->name('reviews.show');
    Route::delete('reviews/{review}', [ManageReviewController::class, 'destroy'])->name('reviews.destroy');

     //SALES
     Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');

     Route::get('/sales-analytics', [SalesAnalyticsController::class, 'index'])->name('sales.analytics');
     Route::get('/sales-analytics/data', [SalesAnalyticsController::class, 'getChartData'])->name('sales.analytics.data');
});

// User Routes (strictly for user role only)
Route::middleware(['auth', 'user'])->group(function () {
    // Home Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    Route::get('/search', [HomeController::class, 'search'])->name('home.search');
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.update.quantity');
    Route::post('/cart/update-all', [CartController::class, 'updateAll'])->name('cart.update.all');
    Route::post('/cart/summary', [CartController::class, 'cartSummary'])->name('cart.summary');
    // Orders
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    
    // Buy Now button route (POST)
    Route::post('/checkout/single', [CheckoutController::class, 'handleSingleCheckout'])
    ->name('checkout.handle-single');
    Route::post('/checkout/update-quantity', [CheckoutController::class, 'updateQuantity'])
    ->name('checkout.update-quantity');
    // Single product checkout page (GET)
    Route::get('/checkout/{product}', [CheckoutController::class, 'single'])
    ->name('checkout.single');

    // Process checkout (POST)
    Route::post('/checkout/process', [CheckoutController::class, 'process'])
    ->name('checkout.process');

    // Checkout success page (GET)
    Route::get('/checkout/success/{order_id}', [CheckoutController::class, 'success'])
    ->name('checkout.success');

    // AJAX quantity update route (POST)
    Route::post('/checkout/update-quantity', [CheckoutController::class, 'updateQuantity'])
    ->name('checkout.update-quantity');
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/check', [ReviewController::class, 'check'])->name('reviews.check');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('user.reviews.destroy');
    Route::get('/reviews/can-review', [ReviewController::class, 'canReview'])->name('reviews.can-review');
});

// Shared authenticated routes (accessible by both roles if needed)
Route::middleware('auth')->group(function () {
    // Add any routes that should be accessible by both roles here
});