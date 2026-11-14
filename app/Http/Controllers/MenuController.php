<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Sajian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MenuController extends Controller
{
    /**
     * Seluruh kategori menu beserta sajian yang sedang dijual.
     *
     * Sumbernya tabel kategori & sajian yang dikelola dari dasbor penjual.
     * Bentuk array-nya sama dengan config/menu.php lama, jadi view tidak berubah.
     *
     * @return Collection<int, array{id: int, label: string, catatan: ?string, sajian: array<int, array<string, mixed>>}>
     */
    public static function kategori(): Collection
    {
        return Kategori::query()
            ->orderBy('urutan')
            ->with(['sajian' => fn ($kueri) => $kueri->where('tersedia', true)])
            ->get()
            ->map(fn (Kategori $kategori) => [
                'id' => $kategori->id,
                'label' => $kategori->label,
                'catatan' => $kategori->catatan,
                'sajian' => $kategori->sajian->map(fn (Sajian $sajian) => $sajian->keArrayKatalog())->all(),
            ]);
    }

    /**
     * Semua sajian tanpa pengelompokan kategori.
     */
    public static function semuaSajian(): Collection
    {
        return self::kategori()->flatMap(fn (array $kategori) => $kategori['sajian']);
    }

    /**
     * Nama stan yang berjualan, untuk daftar pilihan di bilah atas.
     *
     * @return Collection<int, string>
     */
    public static function daftarStan(): Collection
    {
        return self::semuaSajian()->pluck('stan')->unique()->sort()->values();
    }

    /**
     * Halaman menu: korsel promo + sajian per kategori.
     */
    public function daftar(Request $permintaan): View
    {
        // 'slug' menentukan tujuan tombol "Lihat Menu" di tiap bingkai.
        $daftarPromo = [
            [
                'slug' => 'mie-goreng-jawa',
                'kelompok' => 'Dimasak Dadakan',
                'judul' => 'Mie Goreng Jawa',
                'teks' => 'Digoreng begitu pesananmu masuk, jadi masih panas saat kamu ambil di loket.',
                'harga' => 'Rp 11.000',
                'gambar' => 'gambar/sajian/mie-goreng-jawa.jpg',
                'alt' => 'Mie goreng jawa sedang dimasak di atas wajan besar',
            ],
            [
                'slug' => 'roti-bakar-coklat',
                'kelompok' => 'Menu Baru',
                'judul' => 'Roti Bakar Coklat',
                'teks' => 'Roti panggang isi coklat dari Stan Camilan, pas untuk istirahat kedua.',
                'harga' => 'Rp 9.000',
                'gambar' => 'gambar/sajian/roti-bakar-coklat.jpg',
                'alt' => 'Roti bakar isi coklat yang sudah dipanggang',
            ],
            [
                'slug' => 'es-cendol',
                'kelompok' => 'Paling Segar',
                'judul' => 'Es Cendol',
                'teks' => 'Cendol dengan santan dan gula merah, penyegar setelah jam olahraga.',
                'harga' => 'Rp 6.000',
                'gambar' => 'gambar/sajian/es-cendol.jpg',
                'alt' => 'Semangkuk es cendol dengan serutan es',
            ],
        ];

        // Penyaring stan dari bilah atas: kategori yang jadi kosong tidak ditampilkan.
        $stan = $permintaan->string('stan')->trim()->value();

        $daftarKategori = self::kategori()
            ->map(function (array $kategori) use ($stan) {
                if ($stan !== '') {
                    $kategori['sajian'] = array_values(array_filter(
                        $kategori['sajian'],
                        fn (array $sajian) => $sajian['stan'] === $stan
                    ));
                }

                return $kategori;
            })
            ->reject(fn (array $kategori) => $kategori['sajian'] === [])
            ->values();

        return view('menu', [
            'daftarPromo' => $daftarPromo,
            'daftarKategori' => $daftarKategori->all(),
            'stan' => $stan,
            'jumlahSajian' => $daftarKategori->sum(fn (array $kategori) => count($kategori['sajian'])),
        ]);
    }

    /**
     * Halaman deskripsi satu sajian.
     */
    public function tampilkan(string $slug): View
    {
        $kategori = self::kategori()->first(
            fn (array $kategori) => collect($kategori['sajian'])->contains('slug', $slug)
        );

        abort_if($kategori === null, 404);

        return view('menu-rincian', [
            'sajian' => collect($kategori['sajian'])->firstWhere('slug', $slug),
            'kategori' => $kategori,
            // Sajian lain dari kategori yang sama, sebagai saran.
            'sajianLain' => collect($kategori['sajian'])
                ->reject(fn (array $lain) => $lain['slug'] === $slug)
                ->take(3)
                ->values()
                ->all(),
        ]);
    }
}
