<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

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
    Route::get('/admin/products/{product}/edit', [AdminDashboardController::class, 'editProduct'])->name('admin.edit-product');
    Route::put('/admin/products/{product}', [AdminDashboardController::class, 'updateProduct'])->name('admin.update-product');
    Route::delete('/admin/products/{product}', [AdminDashboardController::class, 'deleteProduct'])->name('admin.delete-product');
});

Route::middleware(['auth', 'check.role:customer'])->group(function () {
    Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])->name('customer.dashboard');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{product}', [CartController::class, 'updateCartItem'])->name('cart.update');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
