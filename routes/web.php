<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ============================================
// KHÁCH HÀNG - PUBLIC ROUTES
// ============================================

// Danh sách & chi tiết truyện
Route::get('/comics', [ComicController::class, 'index'])->name('comics.index');
Route::get('/comics/{slug}', [ComicController::class, 'show'])->name('comics.show');
Route::get('/api/comics/search', [ComicController::class, 'search'])->name('comics.search');
Route::get('/api/categories', [ComicController::class, 'getCategories'])->name('categories.index');
Route::get('/api/publishers', [ComicController::class, 'getPublishers'])->name('publishers.index');

// Giỏ hàng
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::post('/update', [CartController::class, 'update'])->name('update');
    Route::post('/remove', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'getCount'])->name('count');
    Route::get('/mini', [CartController::class, 'getMiniCart'])->name('mini');
});

// Thanh toán (checkout)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('show');
    Route::post('/', [CheckoutController::class, 'process'])->name('process');
    Route::post('/apply-voucher', [CheckoutController::class, 'applyVoucher'])->name('apply-voucher');
    Route::post('/remove-voucher', [CheckoutController::class, 'removeVoucher'])->name('remove-voucher');
    Route::get('/preview', [CheckoutController::class, 'preview'])->name('preview');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
});

// Tra cứu đơn hàng (guest - không cần login)
Route::get('/orders/track', [OrderController::class, 'track'])->name('orders.track');

// ============================================
// KHÁCH HÀNG - AUTHENTICATED ROUTES
// ============================================

// Đơn hàng của tôi (cần đăng nhập)
Route::middleware('auth')->prefix('my-orders')->name('my-orders.')->group(function () {
    Route::get('/', [OrderController::class, 'myOrders'])->name('index');
    Route::get('/{code}', [OrderController::class, 'show'])->name('show');
    Route::post('/{code}/cancel', [OrderController::class, 'requestCancel'])->name('cancel');
    Route::post('/{code}/return', [OrderController::class, 'requestReturn'])->name('return');
    Route::get('/{code}/status', [OrderController::class, 'getStatus'])->name('status');
});

// Dashboard mặc định: redirect theo role
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (! $user) {
        return redirect()->route('login');
    }

    if ($user->role === \App\Models\User::ROLE_ADMIN) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Khu vực admin
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
});

// Khu vực người dùng thường
Route::middleware(['auth', 'verified', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
