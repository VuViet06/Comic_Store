<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Trang chủ - Danh sách truyện
Route::get('/', [ComicController::class, 'index'])->name('home');

// Chi tiết truyện & API
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

// Thanh toán
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('show');
    Route::post('/', [CheckoutController::class, 'process'])->name('process');
    Route::post('/apply-voucher', [CheckoutController::class, 'applyVoucher'])->name('apply-voucher');
    Route::post('/remove-voucher', [CheckoutController::class, 'removeVoucher'])->name('remove-voucher');
    Route::get('/preview', [CheckoutController::class, 'preview'])->name('preview');
    Route::get('/success', [CheckoutController::class, 'success'])->name('success');
});


Route::get('/orders/track', [OrderController::class, 'track'])->name('orders.track');



// Đơn hàng
Route::middleware('auth')->prefix('my-orders')->name('my-orders.')->group(function () {
    Route::get('/', [OrderController::class, 'myOrders'])->name('index');
    Route::get('/{code}', [OrderController::class, 'show'])->name('show');
    Route::post('/{code}/cancel', [OrderController::class, 'requestCancel'])->name('cancel');
    Route::post('/{code}/return', [OrderController::class, 'requestReturn'])->name('return');
    Route::get('/{code}/status', [OrderController::class, 'getStatus'])->name('status');
});

// Dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role === \App\Models\User::ROLE_ADMIN) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('user.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Panel
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Comics Management
    Route::resource('comics', \App\Http\Controllers\Admin\ComicController::class);
    
    // Orders Management
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{code}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{code}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{code}/cancel', [\App\Http\Controllers\Admin\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{code}/return', [\App\Http\Controllers\Admin\OrderController::class, 'processReturn'])->name('orders.return');
    
    // Categories Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Publishers Management
    Route::resource('publishers', \App\Http\Controllers\Admin\PublisherController::class);
    
    // Vouchers Management
    Route::resource('vouchers', \App\Http\Controllers\Admin\VoucherController::class);
    
    // Inventory Management
    Route::get('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/history', [\App\Http\Controllers\Admin\InventoryController::class, 'history'])->name('inventory.history');
    Route::get('/inventory/{id}/import', [\App\Http\Controllers\Admin\InventoryController::class, 'importForm'])->name('inventory.import-form');
    Route::post('/inventory/{id}/import', [\App\Http\Controllers\Admin\InventoryController::class, 'import'])->name('inventory.import');
    Route::get('/inventory/{id}/adjust', [\App\Http\Controllers\Admin\InventoryController::class, 'adjustForm'])->name('inventory.adjust-form');
    Route::post('/inventory/{id}/adjust', [\App\Http\Controllers\Admin\InventoryController::class, 'adjust'])->name('inventory.adjust');
    Route::get('/inventory/{id}/history', [\App\Http\Controllers\Admin\InventoryController::class, 'history'])->name('inventory.comic-history');
    
    // Users Management
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/users/{id}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    
    // Shipping Management
    Route::get('/shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'index'])->name('shipping.index');
    Route::get('/shipping/create', [\App\Http\Controllers\Admin\ShippingController::class, 'create'])->name('shipping.create');
    Route::post('/shipping', [\App\Http\Controllers\Admin\ShippingController::class, 'store'])->name('shipping.store');
    Route::get('/shipping/{id}/edit', [\App\Http\Controllers\Admin\ShippingController::class, 'edit'])->name('shipping.edit');
    Route::put('/shipping/{id}', [\App\Http\Controllers\Admin\ShippingController::class, 'update'])->name('shipping.update');
    Route::delete('/shipping/{id}', [\App\Http\Controllers\Admin\ShippingController::class, 'destroy'])->name('shipping.destroy');
    Route::get('/shipping/shipments', [\App\Http\Controllers\Admin\ShippingController::class, 'shipments'])->name('shipping.shipments');
});
//user
Route::middleware(['auth', 'verified', 'user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', function () {
        return view('user.dashboard');
    })->name('dashboard');
});
//profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User Addresses (trong Profile)
    Route::prefix('profile/addresses')->name('addresses.')->group(function () {
        Route::get('/create', [\App\Http\Controllers\UserAddressController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\UserAddressController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [\App\Http\Controllers\UserAddressController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\UserAddressController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\UserAddressController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/default', [\App\Http\Controllers\UserAddressController::class, 'setDefault'])->name('set-default');
        Route::get('/api/list', [\App\Http\Controllers\UserAddressController::class, 'getAddresses'])->name('api.list');
    });
});

require __DIR__ . '/auth.php';
