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
class PastikanSiswaSudahMasuk
{
    public function handle(Request $permintaan, Closure $lanjut): Response
    {
        if (! $permintaan->session()->get('grabo_sudah_masuk')) {
            // Simpan tujuan awal supaya bisa dilanjutkan setelah masuk.
            $permintaan->session()->put('url.intended', $permintaan->fullUrl());

            return redirect()->route('masuk');
        }

        return $lanjut($permintaan);
    }
}
