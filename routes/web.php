<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalogo/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');
Route::get('/carrito', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrito/{product:slug}', [CartController::class, 'store'])->name('cart.store');
Route::patch('/carrito/{product}', [CartController::class, 'update'])->whereNumber('product')->name('cart.update');
Route::delete('/carrito/{product}', [CartController::class, 'destroy'])->whereNumber('product')->name('cart.destroy');
Route::delete('/carrito', [CartController::class, 'clear'])->name('cart.clear');

Route::get('/iniciar-sesion', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login');
Route::post('/iniciar-sesion', [AuthenticatedSessionController::class, 'store'])
    ->middleware(['guest', 'throttle:login'])
    ->name('login.store');
Route::post('/cerrar-sesion', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');
Route::get('/registrarse', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('/registrarse', [RegisteredUserController::class, 'store'])
    ->middleware(['guest', 'throttle:register'])
    ->name('register.store');
Route::middleware('auth')->group(function () {
    Route::get('/pedido/confirmar', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/pedido/confirmar', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/mis-pedidos', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mis-pedidos/{order:folio}', [OrderController::class, 'show'])->name('orders.show');
});
Route::prefix('administracion')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categorias', CategoryController::class)
        ->parameters(['categorias' => 'category'])
        ->names('categories');
    Route::resource('productos', ProductController::class)
        ->parameters(['productos' => 'product'])
        ->names('products');
});
