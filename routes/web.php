<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CatalogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CatalogController::class, 'home'])->name('home');
Route::get('/catalogo', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalogo/{product:slug}', [CatalogController::class, 'show'])->name('catalog.show');

Route::view('/iniciar-sesion', 'auth.login')->name('login');
Route::get('/registrarse', [RegisteredUserController::class, 'create'])
    ->middleware('guest')
    ->name('register');
Route::post('/registrarse', [RegisteredUserController::class, 'store'])
    ->middleware(['guest', 'throttle:register'])
    ->name('register.store');
Route::get('/administracion', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');
