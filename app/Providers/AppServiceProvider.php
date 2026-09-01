<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /*
         * Logo dipakai di navbar, footer, dan halaman auth. Dibagikan ke semua
         * view karena @section pada view anak dievaluasi sebelum layout-nya,
         * sehingga variabel yang dibuat di layout tidak terlihat dari sana.
         */
        View::share('graboLogo', asset('images/grabo-logo.png'));
    }
}
