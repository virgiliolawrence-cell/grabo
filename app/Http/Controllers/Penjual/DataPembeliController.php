<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\ItemPesanan;
use App\Models\Pengguna;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Password;

class DataPembeliController extends Controller
{
    /**
     * Daftar akun murid beserta ringkasan belanjanya.
     */
    public function daftar(Request $permintaan): View
    {
        $cari = $permintaan->string('cari')->trim()->value();

        $daftarMurid = Pengguna::query()
            ->murid()
            ->when($cari !== '', fn ($kueri) => $kueri->where(
                fn ($kueri) => $kueri->where('nama', 'like', "%{$cari}%")
                    ->orWhere('nis', 'like', "%{$cari}%")
                    ->orWhere('kelas', 'like', "%{$cari}%")
            ))
            ->withCount([
                'pesanan as jumlah_pesanan',
                'pesanan as jumlah_pesanan_berjalan' => fn ($kueri) => $kueri->whereIn('status', ['menunggu', 'diproses', 'siap']),
            ])
            ->withSum(['pesanan as total_belanja' => fn ($kueri) => $kueri->where('status', '!=', 'batal')], 'total')
            ->withMax('pesanan as pesanan_terakhir', 'created_at')
            ->orderByDesc('jumlah_pesanan_berjalan')
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('penjual.pembeli', ['daftarMurid' => $daftarMurid]);
    }

    /**
     * Semua yang pernah dibeli satu murid, dengan centang untuk pesanan berjalan.
     */
    public function tampilkan(Pengguna $pengguna): View
    {
        abort_if($pengguna->adalahPenjual(), 404);

        $daftarPesanan = $pengguna->pesanan()->with('item')->latest()->get();
        $pesananSah = $daftarPesanan->where('status', '!=', 'batal');

        $menuFavorit = ItemPesanan::query()
            ->whereIn('pesanan_id', $pesananSah->pluck('id'))
            ->selectRaw('nama, sum(jumlah) as jumlah')
            ->groupBy('nama')
            ->orderByDesc('jumlah')
            ->limit(5)
            ->get();

        return view('penjual.pembeli-rincian', [
            'murid' => $pengguna,
            'pesananBerjalan' => $daftarPesanan->filter(fn ($pesanan) => $pesanan->masihBerjalan())->values(),
            'riwayatPesanan' => $daftarPesanan->reject(fn ($pesanan) => $pesanan->masihBerjalan())->values(),
            'menuFavorit' => $menuFavorit,
            'ringkasan' => [
                'jumlahPesanan' => $pesananSah->count(),
                'totalBelanja' => $pesananSah->sum('total'),
                'jumlahItem' => $pesananSah->sum(fn ($pesanan) => $pesanan->item->sum('jumlah')),
            ],
        ]);
    }

    /**
     * Formulir pendaftaran akun murid.
     */
    public function form(): View
    {
        return view('penjual.pembeli-tambah');
    }

    /**
     * Daftarkan akun murid baru.
     */
    public function simpan(Request $permintaan): RedirectResponse
    {
        $isian = $permintaan->validate([
            'nama' => ['required', 'string', 'max:60'],
            'nis' => ['required', 'string', 'max:20', 'unique:pengguna,nis'],
            'kelas' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:120', 'unique:pengguna,email'],
            'kata_sandi' => ['required', Password::min(8)],
            'ulangi_kata_sandi' => ['required', 'same:kata_sandi'],
        ], attributes: ['nama' => 'Nama murid']);

        $murid = Pengguna::query()->create([
            ...Arr::except($isian, 'ulangi_kata_sandi'),
            'peran' => Pengguna::PERAN_MURID,
        ]);

        return redirect()->route('penjual.pembeli.rincian', $murid)->with('status', "Akun {$murid->nama} berhasil dibuat.");
    }
}
