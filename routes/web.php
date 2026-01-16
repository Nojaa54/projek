<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\BuyerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Landing Page (Buyer Interface)
Route::get('/', [BuyerController::class, 'index'])->name('buyer.index');

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
});

// Buyer Routes (Protected actions)
Route::middleware(['auth', 'role:pembeli'])->prefix('buyer')->name('buyer.')->group(function () {
    Route::post('/cart/{product}', [BuyerController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [BuyerController::class, 'cart'])->name('cart.index');
    Route::post('/checkout', [BuyerController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [BuyerController::class, 'orders'])->name('orders');
});

require __DIR__.'/auth.php';
