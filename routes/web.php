<?php

use App\Http\Controllers\Autentikasi\MasukController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

/*
 * Halaman aplikasi baru bisa dibuka setelah masuk. Selama belum ada tabel
 * pengguna, status masuk hanya ditandai di session (lihat PastikanSiswaSudahMasuk).
 */
Route::middleware('siswa')->group(function () {
    Route::get('/', [BerandaController::class, 'tampilkan'])->name('beranda');

    Route::get('/menu', [MenuController::class, 'daftar'])->name('menu');
    Route::get('/menu/{slug}', [MenuController::class, 'tampilkan'])->name('menu.rincian');

    Route::get('/promo', [PromoController::class, 'tampilkan'])->name('promo');

    Route::get('/kontak', [KontakController::class, 'tampilkan'])->name('kontak');

    Route::get('/pembayaran', [PembayaranController::class, 'form'])->name('pembayaran');
    Route::post('/pembayaran', [PembayaranController::class, 'simpan'])->name('pembayaran.kirim');
    Route::get('/pembayaran/selesai', [PembayaranController::class, 'selesai'])->name('pembayaran.selesai');
});

Route::get('/masuk', [MasukController::class, 'form'])->name('masuk');
Route::post('/masuk', [MasukController::class, 'proses'])->name('masuk.proses');
Route::post('/keluar', [MasukController::class, 'keluar'])->name('keluar');
