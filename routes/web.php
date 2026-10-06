<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalogo/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

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
Route::prefix('administracion')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categorias', CategoryController::class)
        ->parameters(['categorias' => 'category'])
        ->names('categories');
    Route::resource('productos', ProductController::class)
        ->parameters(['productos' => 'product'])
        ->names('products');
});
