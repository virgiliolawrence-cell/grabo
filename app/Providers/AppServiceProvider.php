<?php

namespace App\Providers;

use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Daftarkan layanan aplikasi.
     */
    public function register(): void
    {
        //
    }

    /**
     * Siapkan layanan aplikasi saat aplikasi dijalankan.
     */
    public function boot(): void
    {
        /*
         * Logo dipakai di bilah navigasi, kaki halaman, dan halaman masuk. Dibagikan ke semua
         * view karena @section pada view anak dievaluasi sebelum tata letaknya,
         * sehingga variabel yang dibuat di tata letak tidak terlihat dari sana.
         */
        View::share('graboLogo', asset('gambar/logo-grabo.png'));

        /*
         * Daftar stan untuk tombol "Cari stan kantin" di bilah atas. Dipasang
         * lewat composer, bukan View::share, supaya query-nya hanya jalan saat
         * bilah navigasi benar-benar dirender (dasbor penjual tidak memakainya).
         */
        View::composer('bagian.navigasi', function ($tampilan) {
            $tampilan->with('daftarStan', MenuController::daftarStan());
        });
    }
}
