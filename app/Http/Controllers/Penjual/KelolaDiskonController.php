<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Diskon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class KelolaDiskonController extends Controller
{
    /**
     * Halaman Manajemen Diskon.
     */
    public function daftar(): View
    {
        return view('penjual.diskon', [
            'daftarDiskon' => Diskon::query()
                ->withCount(['pesanan as dipakai' => fn ($kueri) => $kueri->where('status', '!=', 'batal')])
                ->orderByDesc('aktif')
                ->orderBy('kode')
                ->paginate(10),
        ]);
    }

    /**
     * Formulir tambah diskon.
     */
    public function form(): View
    {
        return view('penjual.diskon-tambah');
    }

    /**
     * Simpan kode diskon baru. Langsung aktif di keranjang murid.
     */
    public function simpan(Request $permintaan): RedirectResponse
    {
        $permintaan->merge(['kode' => strtoupper((string) $permintaan->input('kode'))]);

        $isian = $permintaan->validate([
            'kode' => ['required', 'alpha_num:ascii', 'min:4', 'max:12', 'unique:diskon,kode'],
            'label' => ['required', 'string', 'max:40'],
            'potongan' => ['required', 'integer', 'min:500'],
            'belanja_minimal' => ['required', 'integer', 'min:0', 'gte:potongan'],
        ], [
            'kode.unique' => 'Kode ini sudah dipakai diskon lain.',
        ]);

        $diskon = Diskon::query()->create([...$isian, 'aktif' => true]);

        return redirect()->route('penjual.diskon')->with('status', "Kode diskon {$diskon->kode} ditambahkan dan langsung aktif.");
    }

    /**
     * Aktifkan / nonaktifkan kode diskon.
     */
    public function alihAktif(Diskon $diskon): RedirectResponse
    {
        $diskon->update(['aktif' => ! $diskon->aktif]);

        return back()->with('status', "Kode {$diskon->kode} ".($diskon->aktif ? 'diaktifkan.' : 'dinonaktifkan.'));
    }
}
