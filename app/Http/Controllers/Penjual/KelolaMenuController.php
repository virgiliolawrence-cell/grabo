<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Sajian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KelolaMenuController extends Controller
{
    /**
     * Halaman Manajemen Menu.
     */
    public function daftar(Request $permintaan): View
    {
        $cari = $permintaan->string('cari')->trim()->value();
        $kategori = $permintaan->integer('kategori');

        $daftarMenu = Sajian::query()
            ->with('kategori')
            ->when($cari !== '', fn ($kueri) => $kueri->where(
                fn ($kueri) => $kueri->where('nama', 'like', "%{$cari}%")->orWhere('stan', 'like', "%{$cari}%")
            ))
            ->when($kategori > 0, fn ($kueri) => $kueri->where('kategori_id', $kategori))
            ->orderBy('kategori_id')
            ->orderBy('urutan')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('penjual.menu', [
            'daftarMenu' => $daftarMenu,
            'daftarKategori' => Kategori::query()->orderBy('urutan')->get(),
        ]);
    }

    /**
     * Formulir tambah menu.
     */
    public function form(): View
    {
        return view('penjual.menu-form', $this->dataFormulir(new Sajian(['tersedia' => true])));
    }

    /**
     * Simpan menu baru. Menu yang ditandai tersedia langsung tampil di halaman siswa.
     */
    public function simpan(Request $permintaan): RedirectResponse
    {
        $isian = $this->validasi($permintaan);

        $sajian = Sajian::query()->create([
            ...$isian,
            'slug' => $this->slugUnik($isian['nama']),
            'gambar' => $this->simpanGambar($permintaan),
            'urutan' => (int) Sajian::query()->where('kategori_id', $isian['kategori_id'])->max('urutan') + 1,
        ]);

        return redirect()->route('penjual.menu')->with('status', "Menu {$sajian->nama} ditambahkan.");
    }

    /**
     * Formulir ubah menu.
     */
    public function formUbah(Sajian $sajian): View
    {
        return view('penjual.menu-form', $this->dataFormulir($sajian));
    }

    /**
     * Simpan perubahan menu.
     */
    public function perbarui(Request $permintaan, Sajian $sajian): RedirectResponse
    {
        $isian = $this->validasi($permintaan);

        $sajian->update([
            ...$isian,
            'gambar' => $this->simpanGambar($permintaan) ?? $sajian->gambar,
        ]);

        return redirect()->route('penjual.menu')->with('status', "Menu {$sajian->nama} diperbarui.");
    }

    /**
     * Tandai menu habis / dijual lagi.
     */
    public function alihKetersediaan(Sajian $sajian): RedirectResponse
    {
        $sajian->update(['tersedia' => ! $sajian->tersedia]);

        return back()->with('status', $sajian->tersedia
            ? "{$sajian->nama} dijual lagi."
            : "{$sajian->nama} ditandai habis dan disembunyikan dari halaman siswa.");
    }

    /**
     * @return array{sajian: Sajian, daftarKategori: mixed, daftarStan: mixed}
     */
    private function dataFormulir(Sajian $sajian): array
    {
        return [
            'sajian' => $sajian,
            'daftarKategori' => Kategori::query()->orderBy('urutan')->get(),
            'daftarStan' => Sajian::query()->distinct()->orderBy('stan')->pluck('stan'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validasi(Request $permintaan): array
    {
        $isian = $permintaan->validate([
            'nama' => ['required', 'string', 'max:60'],
            'kategori_id' => ['required', 'exists:kategori,id'],
            'stan' => ['required', 'string', 'max:40'],
            'harga' => ['required', 'integer', 'min:1000', 'max:100000'],
            'jenis' => ['required', 'in:makanan,minuman'],
            'ringkasan' => ['nullable', 'string', 'max:200'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'gambar' => ['nullable', 'image', 'max:2048'],
        ], attributes: ['nama' => 'Nama menu']);

        unset($isian['gambar']);

        return [...$isian, 'tersedia' => $permintaan->boolean('tersedia')];
    }

    /**
     * Simpan foto unggahan ke disk publik; null kalau tidak ada unggahan.
     */
    private function simpanGambar(Request $permintaan): ?string
    {
        if (! $permintaan->hasFile('gambar')) {
            return null;
        }

        return 'storage/'.$permintaan->file('gambar')->store('menu', 'public');
    }

    private function slugUnik(string $nama): string
    {
        $dasar = Str::slug($nama);
        $slug = $dasar;

        for ($urutan = 2; Sajian::query()->where('slug', $slug)->exists(); $urutan++) {
            $slug = "{$dasar}-{$urutan}";
        }

        return $slug;
    }
}
