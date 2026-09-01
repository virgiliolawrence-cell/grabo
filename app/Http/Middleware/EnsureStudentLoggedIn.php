<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Menjaga halaman aplikasi supaya hanya bisa dibuka setelah masuk.
 *
 * Belum ada tabel pengguna, jadi status masuk hanya ditandai di session.
 * Ganti pengecekan ini dengan Auth::check() begitu autentikasi asli dipasang.
 */
class EnsureStudentLoggedIn
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('grabo_logged_in')) {
            // Simpan tujuan awal supaya bisa dilanjutkan setelah masuk.
            $request->session()->put('url.intended', $request->fullUrl());

            return redirect()->route('login');
        }

        return $next($request);
    }
}
