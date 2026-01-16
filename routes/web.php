<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Landing Page (Buyer Interface)
Route::get('/', [BuyerController::class, 'index'])->name('buyer.index');
Route::get('/product/{product}', [BuyerController::class, 'show'])->name('buyer.show');

// Auth Dashboard Redirect
Route::get('/dashboard', function () {
    if (Auth::user()->isSeller()) {
        return redirect()->route('seller.dashboard');
    }
    return redirect()->route('buyer.index'); // Buyer stays on store
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Seller Routes
Route::middleware(['auth', 'role:penjual'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'dashboard'])->name('dashboard');
    Route::get('/products/create', [SellerController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [SellerController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [SellerController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [SellerController::class, 'updateProduct'])->name('products.update');

    Route::delete('/products/{product}', [SellerController::class, 'destroyProduct'])->name('products.destroy');
    
    // Order Management
    Route::post('/orders/{order}/verify', [SellerController::class, 'verifyOrder'])->name('orders.verify');
});

// Buyer Routes (Protected actions)
Route::middleware(['auth', 'role:pembeli'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::post('/cart/{product}', [BuyerController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/{id}', [BuyerController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/cart', [BuyerController::class, 'cart'])->name('cart.index');
    
    // Checkout Flow
    Route::get('/checkout', [BuyerController::class, 'showCheckout'])->name('checkout.show');
    Route::post('/checkout', [BuyerController::class, 'processCheckout'])->name('checkout.process');
    
    Route::get('/orders', [BuyerController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [BuyerController::class, 'showOrder'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [BuyerController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/{order}/upload-proof', [BuyerController::class, 'uploadProof'])->name('orders.upload_proof');
});

require __DIR__.'/auth.php';
