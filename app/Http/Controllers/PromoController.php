<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PromoController extends Controller
{
    /**
     * Daftar kode promo beserta syaratnya.
     *
     * Dipakai KODE_PROMO di bagian/skrip.blade.php untuk menghitung potongan
     * di layar. Pindahkan ke tabel diskon begitu dasbor pengelola dikerjakan,
     * dan hitung ulang potongannya di server saat pesanan dikirim.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function kodePromo(): array
    {
        return [
            'HEMAT14' => ['potongan' => 2000, 'belanjaMinimal' => 14000, 'label' => 'Paket hemat'],
            'ROTI21' => ['potongan' => 9000, 'belanjaMinimal' => 18000, 'label' => 'Beli 2 gratis 1'],
            'SEGAR5' => ['potongan' => 1000, 'belanjaMinimal' => 5000, 'label' => 'Promo minuman'],
        ];
    }

    /**
     * Halaman promo bulan ini.
     */
    public function tampilkan(): View
    {
        $kelompokPromo = [
            [
                'label' => 'Paket Hemat',
                'judul' => 'Nasi Goreng + Es Teh',
                'teks' => 'Satu paket makan siang lengkap dari Stan Bu Rina. Lebih murah Rp 2.000 dibanding beli terpisah.',
                'harga' => 'Rp 14.000',
                'hargaAsli' => 'Rp 16.000',
                'kode' => 'HEMAT14',
                'gambar' => 'images/food/photos/nasi-goreng-kampung.jpg',
                'alt' => 'Sepiring nasi goreng kampung lengkap dengan kerupuk',
                'sematan' => 'Paling laris',
            ],
            [
                'label' => 'Beli 2 Gratis 1',
                'judul' => 'Roti Bakar Coklat',
                'teks' => 'Pesan dua roti bakar dari Stan Camilan, dapat satu gratis untuk teman sebangku.',
                'harga' => 'Rp 18.000',
                'hargaAsli' => 'Rp 27.000',
                'kode' => 'ROTI21',
                'gambar' => 'images/food/photos/roti-bakar-coklat.jpg',
                'alt' => 'Roti bakar isi coklat yang sudah dipanggang',
                'sematan' => 'Menu baru',
            ],
            [
                'label' => 'Promo Minuman',
                'judul' => 'Es Cendol Dingin',
                'teks' => 'Diskon khusus jam istirahat kedua, selama persediaan di Stan Minuman masih ada.',
                'harga' => 'Rp 5.000',
                'hargaAsli' => 'Rp 6.000',
                'kode' => 'SEGAR5',
                'gambar' => 'images/food/photos/es-cendol.jpg',
                'alt' => 'Semangkuk es cendol dengan serutan es',
                'sematan' => null,
            ],
        ];

        $ketentuan = [
            'Satu kode promo hanya berlaku untuk satu transaksi per siswa per hari.',
            'Promo tidak bisa digabung dengan potongan harga lain di stan yang sama.',
            'Penawaran berhenti lebih awal bila porsi hari itu sudah habis.',
        ];

        return view('promo', [
            'kelompokPromo' => $kelompokPromo,
            'ketentuan' => $ketentuan,
        ]);
    }
}
