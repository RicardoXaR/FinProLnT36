<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route redirects to goods catalog
Route::get('/', [GoodController::class, 'index']);

// Auth routes (provided by Laravel Breeze)
Auth::routes();

// Admin routes with middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('categories', CategoryController::class);
    // Admin-specific routes for goods (create, edit, update, delete)
    Route::get('/goods/create', [GoodController::class, 'create'])->name('goods.create');
    Route::post('/goods', [GoodController::class, 'store'])->name('goods.store');
    Route::get('/goods/{good}/edit', [GoodController::class, 'edit'])->name('goods.edit');
    Route::put('/goods/{good}', [GoodController::class, 'update'])->name('goods.update');
    Route::delete('/goods/{good}', [GoodController::class, 'destroy'])->name('goods.destroy');
});

// User routes with auth middleware
Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/place-order', [CartController::class, 'placeOrder'])->name('cart.placeOrder');
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::get('/invoices/{invoice}/print', [InvoiceController::class, 'print'])->name('invoices.print');
    Route::get('/dashboard', function () {
        return view('dashboard'); // Replace 'dashboard' with the actual view name
    })->name('dashboard');
});

// Public routes
Route::get('/goods', [GoodController::class, 'index'])->name('goods.index');
Route::get('/goods/{good}', [GoodController::class, 'show'])->name('goods.show');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
