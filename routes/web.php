<?php

use App\Http\Controllers\Autentikasi\MasukController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\Penjual\DataPembeliController;
use App\Http\Controllers\Penjual\KelolaDiskonController;
use App\Http\Controllers\Penjual\KelolaMenuController;
use App\Http\Controllers\Penjual\KelolaPembelianController;
use App\Http\Controllers\Penjual\LaporanTransaksiController;
use App\Http\Controllers\Penjual\ProfilPenjualController;
use App\Http\Controllers\PesananSayaController;
use App\Http\Controllers\PromoController;
use Illuminate\Support\Facades\Route;

/*
 * Halaman siswa: bisa dibuka setelah masuk dengan akun apa pun.
 */
Route::middleware('auth')->group(function () {
    Route::get('/', [BerandaController::class, 'tampilkan'])->name('beranda');

    Route::get('/menu', [MenuController::class, 'daftar'])->name('menu');
    Route::get('/menu/{slug}', [MenuController::class, 'tampilkan'])->name('menu.rincian');

    Route::get('/promo', [PromoController::class, 'tampilkan'])->name('promo');

    Route::get('/kontak', [KontakController::class, 'tampilkan'])->name('kontak');

    Route::get('/pembayaran', [PembayaranController::class, 'form'])->name('pembayaran');
    Route::post('/pembayaran', [PembayaranController::class, 'simpan'])->name('pembayaran.kirim');
    Route::get('/pembayaran/selesai', [PembayaranController::class, 'selesai'])->name('pembayaran.selesai');

    Route::get('/pesanan-saya', [PesananSayaController::class, 'daftar'])->name('pesanan-saya');
});

/*
 * Dasbor penjual: hanya akun berperan 'penjual'.
 */
Route::middleware(['auth', 'penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::redirect('/', '/penjual/pembelian');

    Route::get('/menu', [KelolaMenuController::class, 'daftar'])->name('menu');
    Route::get('/menu/tambah', [KelolaMenuController::class, 'form'])->name('menu.tambah');
    Route::post('/menu', [KelolaMenuController::class, 'simpan'])->name('menu.simpan');
    Route::get('/menu/{sajian}/ubah', [KelolaMenuController::class, 'formUbah'])->name('menu.ubah');
    Route::put('/menu/{sajian}', [KelolaMenuController::class, 'perbarui'])->name('menu.perbarui');
    Route::patch('/menu/{sajian}/ketersediaan', [KelolaMenuController::class, 'alihKetersediaan'])->name('menu.ketersediaan');

    Route::get('/pembelian', [KelolaPembelianController::class, 'daftar'])->name('pembelian');
    Route::get('/pembelian/{pesanan}', [KelolaPembelianController::class, 'tampilkan'])->name('pembelian.rincian');
    Route::patch('/pembelian/{pesanan}/status', [KelolaPembelianController::class, 'ubahStatus'])->name('pembelian.status');
    Route::patch('/pembelian/{pesanan}/item/{item}', [KelolaPembelianController::class, 'centangItem'])->name('pembelian.centang');

    Route::get('/pembeli', [DataPembeliController::class, 'daftar'])->name('pembeli');
    Route::get('/pembeli/tambah', [DataPembeliController::class, 'form'])->name('pembeli.tambah');
    Route::post('/pembeli', [DataPembeliController::class, 'simpan'])->name('pembeli.simpan');
    Route::get('/pembeli/{pengguna}', [DataPembeliController::class, 'tampilkan'])->name('pembeli.rincian');

    Route::get('/diskon', [KelolaDiskonController::class, 'daftar'])->name('diskon');
    Route::get('/diskon/tambah', [KelolaDiskonController::class, 'form'])->name('diskon.tambah');
    Route::post('/diskon', [KelolaDiskonController::class, 'simpan'])->name('diskon.simpan');
    Route::patch('/diskon/{diskon}/aktif', [KelolaDiskonController::class, 'alihAktif'])->name('diskon.aktif');

    Route::get('/laporan', [LaporanTransaksiController::class, 'tampilkan'])->name('laporan');
    Route::delete('/laporan/{pesanan}', [LaporanTransaksiController::class, 'hapus'])->name('laporan.hapus');

    Route::get('/profil', [ProfilPenjualController::class, 'tampilkan'])->name('profil');
    Route::put('/profil', [ProfilPenjualController::class, 'simpan'])->name('profil.simpan');
});

Route::get('/masuk', [MasukController::class, 'form'])->name('masuk');
Route::post('/masuk', [MasukController::class, 'proses'])->name('masuk.proses');
Route::post('/keluar', [MasukController::class, 'keluar'])->name('keluar');
