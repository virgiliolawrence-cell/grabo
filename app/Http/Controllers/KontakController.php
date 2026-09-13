<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class KontakController extends Controller
{
    /** Pilihan topik pesan; dipakai formulir kontak. */
    public const TOPIK = [
        'Masalah pesanan',
        'Usulan menu',
        'Akun & kata sandi',
        'Saldo kartu pelajar',
        'Lainnya',
    ];

    /**
     * Halaman kontak: keterangan koperasi, jam layanan, daftar stan,
     * pertanyaan yang sering muncul, dan formulir pesan.
     */
    public function tampilkan(): View
    {
        return view('kontak', [
            'topik' => self::TOPIK,
            'jamLayanan' => [
                ['hari' => 'Senin &ndash; Kamis', 'jam' => '06.30 &ndash; 15.00'],
                ['hari' => 'Jumat', 'jam' => '06.30 &ndash; 11.30'],
                ['hari' => 'Sabtu', 'jam' => '07.00 &ndash; 12.00'],
                ['hari' => 'Minggu & hari libur', 'jam' => 'Tutup'],
            ],
            'istirahat' => [
                ['label' => 'Istirahat 1', 'jam' => '09.30 &ndash; 10.00'],
                ['label' => 'Istirahat 2', 'jam' => '12.00 &ndash; 12.30'],
            ],
            'stan' => MenuController::daftarStan(),
            'tanya' => [
                [
                    'tanya' => 'Pesanan saya belum siap padahal bel sudah berbunyi.',
                    'jawab' => 'Tunjukkan kode pesanan ke petugas stan. Kalau menunya habis, '
                        . 'pesanan dibatalkan dan saldo tidak dipotong.',
                ],
                [
                    'tanya' => 'Saya lupa kata sandi akun Grabo.',
                    'jawab' => 'Kirim pesan lewat formulir di halaman ini dengan topik '
                        . 'Akun & kata sandi, atau datang langsung ke koperasi membawa kartu pelajar.',
                ],
                [
                    'tanya' => 'Bagaimana cara mengisi saldo kartu pelajar?',
                    'jawab' => 'Pengisian saldo dilayani di loket koperasi pada jam istirahat. '
                        . 'Bawa kartu pelajar dan uang tunai.',
                ],
                [
                    'tanya' => 'Kode promo saya tidak bisa dipakai.',
                    'jawab' => 'Periksa lagi syarat belanja minimalnya di halaman Promo. '
                        . 'Satu kode hanya berlaku untuk satu transaksi per hari.',
                ],
            ],
        ]);
    }
}
