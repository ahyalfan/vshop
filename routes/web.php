<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\Users\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// user route
Route::get('/', [UserController::class,'index'])->name('user.home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('checkout')->controller(CheckoutController::class)->group(function () {
    Route::post('order','store')->name('checkout.store');
});


// add to cart
Route::prefix('cart')->controller(CartController::class)->group(function () {
    Route::get('/view','view')->name('cart.view');
    Route::post('/store/{product}','store')->name('cart.store');
    Route::patch('/update/{product}','update')->name('cart.update');
    Route::delete('/delete/{product}','delete')->name('cart.delete');
});

// list Product user
Route::prefix('products')->controller(ProductListController::class)->group(function (){
    Route::get('/','index')->name('product.index');
});


// end 

// admin route

// ini untuk login admin
Route::group(['prefix'=> 'admin','middleware' => 'redirectAdmin'], function () {
    Route::get('/login',[AdminAuthController::class,'showLoginForm'])->name('admin.login');
    Route::post('/login',[AdminAuthController::class,'login'])->name('admin.login.post');
});
Route::post('/admin/logout',[AdminAuthController::class,'logout'])->name('admin.logout');

Route::middleware(['auth','admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class,'index'])->name('admin.dashboard');

    // product route
    Route::get('/product', [ProductController::class,'index'])->name('admin.product.index');
    Route::post('/product/store', [ProductController::class,'store'])->name('admin.product.store');
    Route::put('/product/update/{id}', [ProductController::class,'update'])->name('admin.product.update');
    Route::delete('/product/image/{id}', [ProductController::class,'deleteImage'])->name('admin.product.image.delete');
    Route::delete('/product/destroy/{id}', [ProductController::class,'destroy'])->name('admin.product.destroy');
    // brand dan categories belum nanti
});

// end

require __DIR__.'/auth.php';
