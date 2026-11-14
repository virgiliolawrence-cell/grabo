<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\ProfilKantin;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProfilPenjualController extends Controller
{
    /**
     * Halaman Profil Penjual.
     */
    public function tampilkan(): View
    {
        return view('penjual.profil', ['profil' => ProfilKantin::milikKantin()]);
    }

    /**
     * Simpan perubahan profil.
     */
    public function simpan(Request $permintaan): RedirectResponse
    {
        $isian = $permintaan->validate([
            'nama' => ['required', 'string', 'max:60'],
            'pengelola' => ['required', 'string', 'max:60'],
            'email' => ['required', 'email'],
            'telepon' => ['required', 'string', 'max:20'],
            'lokasi' => ['required', 'string', 'max:80'],
            'jam_buka' => ['required', 'date_format:H.i'],
            'jam_tutup' => ['required', 'date_format:H.i', 'after:jam_buka'],
            'deskripsi' => ['nullable', 'string', 'max:300'],
        ], attributes: ['nama' => 'Nama kantin', 'email' => 'Email kantin']);

        ProfilKantin::milikKantin()->update($isian);

        return redirect()->route('penjual.profil')->with('status', 'Profil penjual diperbarui.');
    }
}
