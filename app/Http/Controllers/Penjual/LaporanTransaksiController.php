<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanTransaksiController extends Controller
{
    /**
     * Halaman Laporan Transaksi.
     *
     * Pendapatan hanya menghitung pesanan yang tidak batal.
     */
    public function tampilkan(Request $permintaan): View
    {
        $saringan = $permintaan->validate([
            'dari' => ['nullable', 'date_format:Y-m-d'],
            'sampai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:dari'],
            'metode' => ['nullable', Rule::in(array_keys(Pesanan::METODE))],
        ]);

        $kueri = Pesanan::query()
            ->when($saringan['dari'] ?? null, fn ($kueri, string $dari) => $kueri->whereDate('created_at', '>=', $dari))
            ->when($saringan['sampai'] ?? null, fn ($kueri, string $sampai) => $kueri->whereDate('created_at', '<=', $sampai))
            ->when($saringan['metode'] ?? null, fn ($kueri, string $metode) => $kueri->where('metode', $metode));

        $berhasil = (clone $kueri)->where('status', '!=', 'batal');

        return view('penjual.laporan', [
            'daftarTransaksi' => (clone $kueri)->latest()->paginate(10)->withQueryString(),
            'ringkasan' => [
                'jumlahTransaksi' => (clone $berhasil)->count(),
                'jumlahBatal' => (clone $kueri)->where('status', 'batal')->count(),
                'totalPotongan' => (int) (clone $berhasil)->sum('potongan'),
                'totalPendapatan' => (int) (clone $berhasil)->sum('total'),
            ],
        ]);
    }

    /**
     * Hapus satu transaksi beserta isinya (item_pesanan ikut terhapus lewat cascade).
     *
     * Transaksi dan pesanan adalah data yang sama, jadi pesanan ini juga hilang
     * dari Manajemen Pembelian, Data Pembeli, dan Pesanan Saya milik murid.
     */
    public function hapus(Pesanan $pesanan): RedirectResponse
    {
        $pesanan->delete();

        return back()->with('status', "Transaksi {$pesanan->kode} dihapus dari laporan.");
    }
}
