<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PesananSayaController extends Controller
{
    /**
     * Riwayat pesanan milik murid yang sedang masuk, beserta statusnya.
     */
    public function daftar(Request $permintaan): View
    {
        return view('pesanan-saya', [
            'daftarPesanan' => $permintaan->user()->pesanan()->with('item')->latest()->paginate(8),
        ]);
    }
}
