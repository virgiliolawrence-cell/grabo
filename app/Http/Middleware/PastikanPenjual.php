<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Dasbor penjual hanya untuk akun berperan 'penjual'.
 *
 * Murid yang membuka dasbor dikeluarkan dulu lalu diarahkan ke halaman
 * masuk, supaya bisa langsung berganti ke akun penjual tanpa buntu di 403.
 * Dipasang setelah middleware 'auth', jadi pengguna pasti sudah masuk.
 */
class PastikanPenjual
{
    public function handle(Request $permintaan, Closure $lanjut): Response
    {
        if ($permintaan->user()?->adalahPenjual()) {
            return $lanjut($permintaan);
        }

        Auth::logout();
        $permintaan->session()->invalidate();
        $permintaan->session()->regenerateToken();

        return redirect()->route('masuk')
            ->with('status', 'Dasbor penjual hanya untuk akun penjual. Silakan masuk dengan akun penjual.');
    }
}
