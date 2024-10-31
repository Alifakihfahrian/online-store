<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PulsaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [PageController::class, 'showLoginForm'])->name('login');
Route::get('/register', [PageController::class, 'showRegisterForm'])->name('register');
Route::get('/admin/login', [PageController::class, 'showAdminLoginForm'])->name('admin.login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/admin/login', [AuthController::class, 'adminLogin']);

Route::middleware(['auth', 'check.role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/products/create', [AdminDashboardController::class, 'createProduct'])->name('admin.create-product');
    Route::post('/admin/products', [AdminDashboardController::class, 'storeProduct'])->name('admin.store-product');
    Route::get('/admin/products/{id}/edit', [AdminDashboardController::class, 'editProduct'])->name('admin.edit-product');
    Route::put('/admin/products/{id}', [AdminDashboardController::class, 'updateProduct'])->name('admin.update-product');
    Route::delete('/admin/products/{product}', [AdminDashboardController::class, 'deleteProduct'])->name('admin.delete-product');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard customer
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    
    // Route untuk keranjang
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/pulsa', [PulsaController::class, 'index'])->name('pulsa.index');
    Route::post('/pulsa/purchase', [PulsaController::class, 'purchase'])->name('pulsa.purchase');
    Route::post('/pulsa/detail', [PulsaController::class, 'showDetail'])->name('pulsa.detail');
    Route::post('/pulsa/pay', [PulsaController::class, 'pay'])->name('pulsa.pay');
    Route::post('/pulsa/redirect', [PulsaController::class, 'redirect'])->name('pulsa.redirect');
    Route::post('/cart/remove/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
