<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class BerandaController extends Controller
{
    /**
     * Beranda: hero, angka ringkas, keunggulan, menu populer, dan cara pesan.
     */
    public function tampilkan(): View
    {
        $angka = [
            ['angka' => '500+', 'label' => 'Siswa terdaftar'],
            ['angka' => '30', 'label' => 'Stan kantin'],
            ['angka' => '0 Menit', 'label' => 'Waktu antre'],
        ];

        $keunggulan = [
            [
                'judul' => 'Pesan dari mana saja',
                'teks' => 'Dari kelas, perpustakaan, atau lapangan &mdash; cukup lewat ponsel.',
            ],
            [
                'judul' => 'Pembayaran non-tunai',
                'teks' => 'Terhubung dengan saldo kartu pelajar, tanpa repot uang kembalian.',
            ],
            [
                'judul' => 'Notifikasi siap ambil',
                'teks' => 'Datang ke loket hanya ketika pesananmu benar-benar sudah siap.',
            ],
        ];

        $langkah = [
            [
                'judul' => 'Pilih Menu',
                'teks' => 'Telusuri menu dari seluruh stan kantin, lengkap dengan harga dan sisa porsi hari ini.',
            ],
            [
                'judul' => 'Kirim Pesanan',
                'teks' => 'Masukkan pilihanmu ke keranjang, bayar dengan saldo pelajar, lalu pesanan diteruskan ke stan.',
            ],
            [
                'judul' => 'Ambil Pesanan',
                'teks' => 'Tunggu notifikasi siap diambil, tunjukkan kode pesanan di loket, dan makanan langsung diserahkan.',
            ],
        ];

        /*
         * Enam menu unggulan diambil dari katalog yang sama dengan halaman
         * menu (config/menu.php), supaya harga dan keterangannya tidak berbeda.
         */
        $slugUnggulan = [
            'nasi-goreng-kampung',
            'mie-ayam-jamur',
            'ayam-geprek',
            'batagor-saus-kacang',
            'roti-bakar-mentega',
            'es-teh-manis',
        ];

        $menuPopuler = MenuController::semuaSajian()
            ->whereIn('slug', $slugUnggulan)
            ->sortBy(fn (array $sajian) => array_search($sajian['slug'], $slugUnggulan))
            ->values();

        return view('beranda', [
            'angka' => $angka,
            'keunggulan' => $keunggulan,
            'langkah' => $langkah,
            'menuPopuler' => $menuPopuler,
        ]);
    }
}
