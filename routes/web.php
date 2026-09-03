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

<<<<<<< HEAD
    Route::get('/menu', [MenuController::class, 'index'])->name('menu');

    Route::get('/promo', [PromoController::class, 'index'])->name('promo');
=======
    Route::get('/menu', function () {
        return view('menu', ['categories' => config('menu.categories')]);
    })->name('menu');

    /*
     * Halaman deskripsi produk. Katalognya masih dari config/menu.php,
     * jadi pencarian slug dilakukan di sini; ganti dengan query model
     * begitu tabel menu tersedia.
     */
    Route::get('/menu/{slug}', function (string $slug) {
        $categories = collect(config('menu.categories'));

        $category = $categories->first(
            fn (array $category) => collect($category['items'])->contains('slug', $slug)
        );

        abort_if($category === null, 404);

        $item = collect($category['items'])->firstWhere('slug', $slug);

        return view('menu-detail', [
            'item' => $item,
            'category' => $category,
            // Menu lain dari stan dan kategori yang sama, sebagai saran.
            'related' => collect($category['items'])
                ->reject(fn (array $other) => $other['slug'] === $slug)
                ->take(3)
                ->values()
                ->all(),
        ]);
    })->name('menu.show');

    Route::get('/promo', function () {
        return view('promo');
    })->name('promo');
>>>>>>> d68c612716b7fa724a48288dc471d56784c9d33b

    Route::get('/checkout', [CheckoutController::class, 'create'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.submit');
    Route::get('/checkout/selesai', [CheckoutController::class, 'done'])->name('checkout.done');
});

Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
