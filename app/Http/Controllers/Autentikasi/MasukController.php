<?php

namespace App\Http\Controllers\Autentikasi;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MasukController extends Controller
{
    /**
     * Formulir masuk.
     */
    public function form(Request $permintaan): View|RedirectResponse
    {
        // Sudah masuk: tidak perlu melihat formulir lagi.
        if ($permintaan->session()->get('grabo_sudah_masuk')) {
            return redirect()->route('beranda');
        }

        return view('autentikasi.masuk');
    }

    /**
     * Proses masuk.
     *
     * Belum ada tabel pengguna, jadi kredensial hanya divalidasi formatnya dan
     * status masuk ditandai di session. Ganti dengan Auth::attempt() begitu
     * model Pengguna dan migrasinya tersedia.
     */
    public function proses(Request $permintaan): RedirectResponse
    {
        $kredensial = $permintaan->validate([
            'email' => ['required', 'email'],
            'sandi' => ['required', 'string', 'min:8'],
        ]);

        $permintaan->session()->regenerate();
        $permintaan->session()->put('grabo_sudah_masuk', true);
        $permintaan->session()->put('grabo_pengguna', $kredensial['email']);

        return redirect()->intended(route('beranda'));
    }

    /**
     * Keluar dari akun.
     */
    public function keluar(Request $permintaan): RedirectResponse
    {
        $permintaan->session()->forget(['grabo_sudah_masuk', 'grabo_pengguna']);
        $permintaan->session()->regenerate();

        return redirect()->route('masuk')->with('status', 'Kamu sudah keluar dari akun.');
    }
}
