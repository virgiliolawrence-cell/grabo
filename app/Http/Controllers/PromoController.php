<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PromoController extends Controller
{
    /**
     * Daftar kode promo beserta syaratnya.
     *
     * Nilainya sengaja disamakan dengan PROMO_CODES di partials/scripts.blade.php.
     * Saat keranjang sudah dihitung di server, keduanya harus membaca sumber ini.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function codes(): array
    {
        return [
            'HEMAT14' => ['discount' => 2000, 'min' => 14000, 'label' => 'Paket hemat'],
            'ROTI21' => ['discount' => 9000, 'min' => 18000, 'label' => 'Beli 2 gratis 1'],
            'SEGAR5' => ['discount' => 1000, 'min' => 5000, 'label' => 'Promo minuman'],
        ];
    }

    /**
     * Halaman promo bulan ini.
     */
    public function index(): View
    {
        $promoGroups = [
            [
                'label' => 'Paket Hemat',
                'title' => 'Nasi Goreng + Es Teh',
                'text' => 'Satu paket makan siang lengkap dari Stan Bu Rina. Lebih murah Rp 2.000 dibanding beli terpisah.',
                'price' => 'Rp 14.000',
                'was' => 'Rp 16.000',
                'code' => 'HEMAT14',
                'image' => 'images/food/photos/nasi-goreng.jpg',
                'alt' => 'Sepiring nasi goreng kampung lengkap dengan kerupuk',
                'badge' => 'Paling laris',
            ],
            [
                'label' => 'Beli 2 Gratis 1',
                'title' => 'Roti Bakar Coklat',
                'text' => 'Pesan dua roti bakar dari Stan Snack Corner, dapat satu gratis untuk teman sebangku.',
                'price' => 'Rp 18.000',
                'was' => 'Rp 27.000',
                'code' => 'ROTI21',
                'image' => 'images/food/photos/promo-roti-coklat.jpg',
                'alt' => 'Roti bakar isi coklat yang sudah dipanggang',
                'badge' => 'Menu baru',
            ],
            [
                'label' => 'Promo Minuman',
                'title' => 'Es Cendol Dingin',
                'text' => 'Diskon khusus jam istirahat kedua, selama persediaan di Stan Minuman masih ada.',
                'price' => 'Rp 5.000',
                'was' => 'Rp 6.000',
                'code' => 'SEGAR5',
                'image' => 'images/food/photos/promo-es-cendol.jpg',
                'alt' => 'Semangkuk es cendol dengan serutan es',
                'badge' => null,
            ],
        ];

        $terms = [
            'Satu kode promo hanya berlaku untuk satu transaksi per siswa per hari.',
            'Promo tidak bisa digabung dengan potongan harga lain di stan yang sama.',
            'Penawaran berhenti lebih awal bila porsi hari itu sudah habis.',
        ];

        return view('promo', [
            'promoGroups' => $promoGroups,
            'terms' => $terms,
        ]);
    }
}
