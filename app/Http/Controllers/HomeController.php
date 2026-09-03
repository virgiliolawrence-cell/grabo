<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Beranda: hero, statistik, keunggulan, menu populer, dan cara pesan.
     */
    public function index(): View
    {
        $stats = [
            ['value' => '500+', 'label' => 'Siswa terdaftar'],
            ['value' => '30', 'label' => 'Stan kantin'],
            ['value' => '0 Menit', 'label' => 'Waktu antre'],
        ];

        $values = [
            [
                'title' => 'Pesan dari mana saja',
                'text' => 'Dari kelas, perpustakaan, atau lapangan &mdash; cukup lewat ponsel.',
            ],
            [
                'title' => 'Pembayaran non-tunai',
                'text' => 'Terhubung dengan saldo kartu pelajar, tanpa repot uang kembalian.',
            ],
            [
                'title' => 'Notifikasi siap ambil',
                'text' => 'Datang ke loket hanya ketika pesananmu benar-benar sudah siap.',
            ],
        ];

        $steps = [
            [
                'title' => 'Browse Menu',
                'text' => 'Telusuri menu dari seluruh stan kantin, lengkap dengan harga dan sisa porsi hari ini.',
            ],
            [
                'title' => 'Place Order',
                'text' => 'Masukkan pilihanmu ke keranjang, bayar dengan saldo pelajar, lalu pesanan diteruskan ke stan.',
            ],
            [
                'title' => 'Pick Up Food',
                'text' => 'Tunggu notifikasi siap diambil, tunjukkan kode pesanan di loket, dan makanan langsung diserahkan.',
            ],
        ];

        // Menu populer diambil dari katalog yang sama dengan halaman menu.
        $menu = array_values(array_filter(
            MenuController::decorate(MenuController::catalog()),
            static fn (array $item): bool => $item['popular'] === true,
        ));

        return view('home', [
            'stats' => $stats,
            'values' => $values,
            'steps' => $steps,
            'menu' => $menu,
        ]);
    }
}
