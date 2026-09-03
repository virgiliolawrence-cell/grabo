<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Formulir masuk.
     */
    public function create(Request $request): View|RedirectResponse
    {
        // Sudah masuk: tidak perlu melihat formulir lagi.
        if ($request->session()->get('grabo_logged_in')) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Proses masuk.
     *
     * Belum ada tabel pengguna, jadi kredensial hanya divalidasi formatnya dan
     * status masuk ditandai di session. Ganti dengan Auth::attempt() begitu
     * model User dan migrasinya tersedia.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $request->session()->regenerate();
        $request->session()->put('grabo_logged_in', true);
        $request->session()->put('grabo_user', $credentials['email']);

        return redirect()->intended(route('home'));
    }

    /**
     * Keluar dari akun.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->session()->forget(['grabo_logged_in', 'grabo_user']);
        $request->session()->regenerate();

        return redirect()->route('login')->with('status', 'Kamu sudah keluar dari akun.');
    }
}
