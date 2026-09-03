<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

/*
 * Halaman aplikasi baru bisa dibuka setelah masuk. Selama belum ada tabel
 * pengguna, status masuk hanya ditandai di session (lihat EnsureStudentLoggedIn).
 */
Route::middleware('student')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('/promo', [PromoController::class, 'index'])->name('promo');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.submit');
    Route::get('/checkout/selesai', [CheckoutController::class, 'done'])->name('checkout.done');
});

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
