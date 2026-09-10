<?php

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\MenuManagementController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TransactionController;
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
    Route::get('/menu/{slug}', [MenuController::class, 'show'])->name('menu.show');

    Route::get('/promo', [PromoController::class, 'index'])->name('promo');

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.submit');
    Route::get('/checkout/selesai', [CheckoutController::class, 'done'])->name('checkout.done');
});

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

/*
 * Dasbor pengelola kantin. Terpisah dari halaman siswa: masuknya lewat
 * tabel users (Auth::attempt + hash), dan dijaga middleware 'admin'.
 */
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/masuk', [AdminLoginController::class, 'create'])->name('login');
    Route::post('/masuk', [AdminLoginController::class, 'store'])->name('login.attempt');
    Route::post('/keluar', [AdminLoginController::class, 'destroy'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Manajemen menu / pembelian
        Route::get('/menu', [MenuManagementController::class, 'index'])->name('menu.index');
        Route::get('/menu/baru', [MenuManagementController::class, 'create'])->name('menu.create');
        Route::post('/menu', [MenuManagementController::class, 'store'])->name('menu.store');
        Route::get('/menu/{menu}/ubah', [MenuManagementController::class, 'edit'])->name('menu.edit');
        Route::put('/menu/{menu}', [MenuManagementController::class, 'update'])->name('menu.update');
        Route::patch('/menu/{menu}/tampil', [MenuManagementController::class, 'toggle'])->name('menu.toggle');
        Route::delete('/menu/{menu}', [MenuManagementController::class, 'destroy'])->name('menu.destroy');

        // Daftar siswa
        Route::get('/siswa', [StudentController::class, 'index'])->name('students.index');
        Route::get('/siswa/baru', [StudentController::class, 'create'])->name('students.create');
        Route::post('/siswa', [StudentController::class, 'store'])->name('students.store');
        Route::get('/siswa/{student}/ubah', [StudentController::class, 'edit'])->name('students.edit');
        Route::put('/siswa/{student}', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/siswa/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

        // Laporan transaksi
        Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transaksi/{order}', [TransactionController::class, 'show'])->name('transactions.show');
        Route::patch('/transaksi/{order}/status', [TransactionController::class, 'updateStatus'])->name('transactions.status');

        // Manajemen diskon
        Route::get('/diskon', [DiscountController::class, 'index'])->name('discounts.index');
        Route::get('/diskon/baru', [DiscountController::class, 'create'])->name('discounts.create');
        Route::post('/diskon', [DiscountController::class, 'store'])->name('discounts.store');
        Route::get('/diskon/{discount}/ubah', [DiscountController::class, 'edit'])->name('discounts.edit');
        Route::put('/diskon/{discount}', [DiscountController::class, 'update'])->name('discounts.update');
        Route::patch('/diskon/{discount}/aktif', [DiscountController::class, 'toggle'])->name('discounts.toggle');
        Route::delete('/diskon/{discount}', [DiscountController::class, 'destroy'])->name('discounts.destroy');
    });
});
