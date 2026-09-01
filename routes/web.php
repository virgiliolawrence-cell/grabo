<?php

use App\Http\Controllers\SchoolClass\CreateController;
use App\Http\Controllers\SchoolClass\DestroyController;
use App\Http\Controllers\SchoolClass\UpdateController;
use App\Http\Controllers\SchoolClass\ShowController;
use App\Http\Controllers\SchoolClass\IndexController;
use App\Http\Controllers\SchoolClass\EditController;
use App\Http\Controllers\SchoolClass\StoreController;
use App\Http\Controllers\MajorsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
 * Halaman aplikasi baru bisa dibuka setelah masuk. Selama belum ada tabel
 * pengguna, status masuk hanya ditandai di session (lihat EnsureStudentLoggedIn).
 */
Route::middleware('student')->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    Route::get('/menu', function () {
        return view('menu');
    })->name('menu');

    Route::get('/promo', function () {
        return view('promo');
    })->name('promo');

    Route::get('/checkout', function () {
        return view('checkout');
    })->name('checkout');

    /*
     * Belum ada gerbang pembayaran sungguhan: pesanan hanya divalidasi lalu
     * diberi kode, dan totalnya masih dikirim dari sisi klien. Saat backend
     * pesanan dibuat, total wajib dihitung ulang di server.
     */
    Route::post('/checkout', function (Request $request) {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:60'],
            'kelas' => ['required', 'string', 'max:20'],
            'waktu' => ['required', 'in:sekarang,istirahat-1,istirahat-2'],
            'metode' => ['required', 'in:tunai,saldo,qris,transfer,ewallet'],
            'bank' => ['nullable', 'string', 'max:30'],
            'ewallet' => ['nullable', 'string', 'max:30'],
            'catatan' => ['nullable', 'string', 'max:200'],
            'total' => ['required', 'integer', 'min:0'],
        ]);

        $data['kode'] = 'GRB-' . strtoupper(Str::random(6));

        return redirect()->route('checkout.done')->with('pesanan', $data);
    })->name('checkout.submit');

    Route::get('/checkout/selesai', function (Request $request) {
        $pesanan = $request->session()->get('pesanan');

        // Halaman ini hanya berarti tepat setelah pesanan dikirim.
        if (! $pesanan) {
            return redirect()->route('menu');
        }

        return view('checkout-done', ['pesanan' => $pesanan]);
    })->name('checkout.done');
});

Route::get('/login', function (Request $request) {
    // Sudah masuk: tidak perlu melihat formulir lagi.
    if ($request->session()->get('grabo_logged_in')) {
        return redirect()->route('home');
    }

    return view('auth.login');
})->name('login');

/*
 * Belum ada pemeriksaan kredensial ke database: setiap email dan kata sandi
 * yang formatnya benar akan diterima. Ganti bagian ini dengan Auth::attempt()
 * begitu tabel pengguna tersedia.
 */
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'string', 'min:8'],
    ]);

    $request->session()->regenerate();
    $request->session()->put('grabo_logged_in', true);
    $request->session()->put('grabo_user', $credentials['email']);

    return redirect()->intended(route('home'));
})->name('login.attempt');

Route::post('/logout', function (Request $request) {
    $request->session()->forget(['grabo_logged_in', 'grabo_user']);
    $request->session()->regenerate();

    return redirect()->route('login')->with('status', 'Kamu sudah keluar dari akun.');
})->name('logout');

//Student Action Controller
Route::name('students.')->prefix('students')->group(function () {
    Route::get('/', [StudentController::class, 'index'])->name('index');

    Route::get('/create', [StudentController::class, 'create'])->name('create');

    Route::post('/', [StudentController::class, 'store'])->name('store');

    Route::get('/{id}', [StudentController::class, 'show'])->name('show');

    Route::get('/{id}/edit', [StudentController::class, 'edit'])->name('edit');

    Route::put('/{id}', [StudentController::class, 'update'])->name('update');

    Route::delete('/{id}', [StudentController::class, 'destroy'])->name('destroy');
});

//Teacher Action Controller
Route::name('teachers.')->prefix('teachers')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');

    Route::get('/create', [TeacherController::class, 'create'])->name('create');

    Route::post('/', [TeacherController::class, 'store'])->name('store');

    Route::get('/{id}', [TeacherController::class, 'show'])->name('show');

    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('edit');

    Route::put('/{id}', [TeacherController::class, 'update'])->name('update');

    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
});

//SchoolClass Invokable
Route::name('classes.')->prefix('classes')->group(function () {
    Route::get('/', IndexController::class)->name('index');

    Route::get('/create', CreateController::class, 'create')->name('create');

        Route::post('/', StoreController::class, 'store')->name('store');

    Route::get('/{id}', ShowController::class, 'show')->name('show');

    Route::get('/{id}/edit', EditController::class, 'edit')->name('edit');

    Route::put('/{id}', UpdateController::class, 'update')->name('update');

    Route::delete('/{id}', DestroyController::class, 'destroy')->name('destroy');
});

Route::resource('majors', MajorsController::class);

