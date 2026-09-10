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
         * Logo dipakai di navbar, footer, dan halaman auth. Dibagikan ke semua
         * view karena @section pada view anak dievaluasi sebelum layout-nya,
         * sehingga variabel yang dibuat di layout tidak terlihat dari sana.
         */
        View::share('graboLogo', asset('images/grabo-logo.png'));

        /*
         * Daftar stan untuk tombol "Cari stan kantin" di bilah atas. Dipasang
         * lewat composer, bukan View::share, supaya query-nya hanya jalan saat
         * navbar benar-benar dirender (halaman admin tidak memakainya).
         */
        View::composer('partials.nav', function ($view) {
            $view->with('daftarStan', MenuController::stalls());
        });
    }
}
