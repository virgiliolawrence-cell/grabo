<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MasukController extends Controller
{
    /**
     * Formulir masuk. Yang sudah masuk tidak dihadang middleware 'guest'
     * di rute, jadi dialihkan dari sini.
     */
    public function form(Request $permintaan): View|RedirectResponse
    {
        if ($permintaan->user()) {
            return redirect()->to($this->halamanAwal($permintaan));
        }

        return view('autentikasi.masuk');
    }

    /**
     * Proses masuk dengan akun di tabel pengguna.
     */
    public function proses(Request $permintaan): RedirectResponse
    {
        $kredensial = $permintaan->validate([
            'email' => ['required', 'email'],
            'kata_sandi' => ['required', 'string', 'min:8'],
        ]);

        $berhasil = Auth::attempt(
            // Kunci 'password' wajib bagi Auth::attempt; nilainya dicocokkan ke kolom kata_sandi.
            ['email' => $kredensial['email'], 'password' => $kredensial['kata_sandi']],
            $permintaan->boolean('ingat_saya'),
        );

        if (! $berhasil) {
            throw ValidationException::withMessages([
                'email' => 'Email atau kata sandi tidak cocok.',
            ]);
        }

        $permintaan->session()->regenerate();

        // Penjual langsung ke dasbor; murid kembali ke halaman yang tadi dituju.
        if ($permintaan->user()->adalahPenjual()) {
            return redirect()->route('penjual.pembelian');
        }

        return redirect()->intended(route('beranda'));
    }

    /**
     * Keluar dari akun.
     */
    public function keluar(Request $permintaan): RedirectResponse
    {
        Auth::logout();

        $permintaan->session()->invalidate();
        $permintaan->session()->regenerateToken();

        return redirect()->route('masuk')->with('status', 'Kamu sudah keluar dari akun.');
    }

    private function halamanAwal(Request $permintaan): string
    {
        return $permintaan->user()->adalahPenjual() ? route('penjual.pembelian') : route('beranda');
    }
}
