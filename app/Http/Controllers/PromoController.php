<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Contracts\View\View;

class PromoController extends Controller
{
    /**
     * Daftar kode promo yang sedang aktif, dari tabel diskon.
     *
     * Dipakai PROMO_CODES di partials/scripts.blade.php untuk menghitung
     * potongan di layar. Angka yang menentukan tetap di CheckoutController,
     * yang membaca tabel yang sama saat pesanan disimpan.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function codes(): array
    {
        return Discount::where('is_active', true)
            ->get()
            ->filter(fn (Discount $d) => $d->isUsableOn(now()))
            ->mapWithKeys(fn (Discount $d) => [
                $d->code => ['discount' => $d->amount, 'min' => $d->min_spend, 'label' => $d->label],
            ])
            ->all();
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
