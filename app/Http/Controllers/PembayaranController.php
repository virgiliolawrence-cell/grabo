<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PembayaranController extends Controller
{
    /**
     * Formulir pembayaran.
     */
    public function form(): View
    {
        return view('pembayaran', [
            'pilihanWaktu' => [
                ['nilai' => 'sekarang', 'label' => 'Secepatnya', 'catatan' => 'Disiapkan begitu stan menerima pesanan'],
                ['nilai' => 'istirahat-1', 'label' => 'Istirahat 1', 'catatan' => 'Siap diambil pukul 09.30'],
                ['nilai' => 'istirahat-2', 'label' => 'Istirahat 2', 'catatan' => 'Siap diambil pukul 12.00'],
            ],
            'metodeTempat' => [
                [
                    'nilai' => 'tunai',
                    'label' => 'Tunai di loket',
                    'catatan' => 'Bayar langsung ke petugas saat mengambil pesanan.',
                    'sematan' => null,
                ],
                [
                    'nilai' => 'saldo',
                    'label' => 'Saldo kartu pelajar',
                    'catatan' => 'Saldo dipotong otomatis saat pesanan diserahkan.',
                    'sematan' => 'Tanpa uang kembalian',
                ],
            ],
            'metodeDaring' => [
                [
                    'nilai' => 'qris',
                    'label' => 'QRIS',
                    'catatan' => 'Bayar dari aplikasi bank atau dompet digital apa pun.',
                    'sematan' => 'Paling cepat',
                ],
                [
                    'nilai' => 'transfer',
                    'label' => 'Transfer bank',
                    'catatan' => 'Nomor rekening virtual muncul setelah pesanan dikirim.',
                    'sematan' => null,
                ],
                [
                    'nilai' => 'dompet',
                    'label' => 'Dompet digital',
                    'catatan' => 'GoPay, OVO, atau DANA yang terhubung ke akun sekolah.',
                    'sematan' => null,
                ],
            ],
        ]);
    }

    /**
     * Terima pesanan lalu berikan kodenya.
     *
     * Belum ada tabel pesanan; rinciannya hanya dititipkan ke session untuk
     * ditampilkan di halaman berikutnya. Meski begitu totalnya tetap dihitung
     * ulang dari katalog, supaya nilai kiriman browser tidak dipercaya bulat-bulat.
     */
    public function simpan(Request $permintaan): RedirectResponse
    {
        $pesanan = $permintaan->validate([
            'nama' => ['required', 'string', 'max:60'],
            'kelas' => ['required', 'string', 'max:20'],
            'waktu' => ['required', 'in:sekarang,istirahat-1,istirahat-2'],
            'metode' => ['required', 'in:tunai,saldo,qris,transfer,dompet'],
            'bank' => ['nullable', 'string', 'max:30'],
            'dompet' => ['nullable', 'string', 'max:30'],
            'catatan' => ['nullable', 'string', 'max:200'],
            'promo' => ['nullable', 'string', 'max:30'],
            'keranjang' => ['required', 'json'],
            'total' => ['required', 'integer', 'min:0'],
        ]);

        $barisKeranjang = $this->hitungUlangHarga($pesanan['keranjang']);

        if ($barisKeranjang->isEmpty()) {
            return back()->withErrors(['keranjang' => 'Keranjangmu kosong atau menunya sudah tidak dijual.']);
        }

        $subtotal = (int) $barisKeranjang->sum(fn (array $baris) => $baris['harga'] * $baris['jumlah']);
        $promo = PromoController::kodePromo()[strtoupper((string) ($pesanan['promo'] ?? ''))] ?? null;
        $potongan = $promo && $subtotal >= $promo['belanjaMinimal']
            ? min($promo['potongan'], $subtotal)
            : 0;

        $pesanan['kode'] = 'GRB-' . strtoupper(Str::random(6));
        $pesanan['total'] = $subtotal - $potongan;

        return redirect()->route('pembayaran.selesai')->with('pesanan', $pesanan);
    }

    /**
     * Baca ulang harga tiap baris keranjang dari katalog.
     *
     * Baris yang slug-nya tidak dikenal dibuang, jadi pesanan tidak bisa
     * diisi menu karangan sendiri.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function hitungUlangHarga(string $json): Collection
    {
        $keranjang = collect(json_decode($json, true) ?: []);
        $katalog = MenuController::semuaSajian()->keyBy('slug');

        return $keranjang
            ->filter(fn ($baris) => is_array($baris) && isset($katalog[$baris['slug'] ?? '']))
            ->map(function (array $baris) use ($katalog) {
                $sajian = $katalog[$baris['slug']];

                return [
                    'nama' => $sajian['nama'],
                    'harga' => $sajian['harga'],
                    'jumlah' => max(1, min(20, (int) ($baris['jumlah'] ?? 1))),
                ];
            })
            ->values();
    }

    /**
     * Halaman konfirmasi setelah pesanan dikirim.
     */
    public function selesai(Request $permintaan): View|RedirectResponse
    {
        $pesanan = $permintaan->session()->get('pesanan');

        // Halaman ini hanya berarti tepat setelah pesanan dikirim.
        if (! $pesanan) {
            return redirect()->route('menu');
        }

        return view('pembayaran-selesai', [
            'pesanan' => $pesanan,
            'labelMetode' => [
                'tunai' => 'Tunai di loket',
                'saldo' => 'Saldo kartu pelajar',
                'qris' => 'QRIS',
                'transfer' => 'Transfer bank ' . ($pesanan['bank'] ?? ''),
                'dompet' => 'Dompet digital ' . ($pesanan['dompet'] ?? ''),
            ],
            'labelWaktu' => [
                'sekarang' => 'Secepatnya',
                'istirahat-1' => 'Istirahat 1 &middot; pukul 09.30',
                'istirahat-2' => 'Istirahat 2 &middot; pukul 12.00',
            ],
            // Pembayaran daring masih menunggu konfirmasi; tunai/saldo langsung disiapkan.
            'daring' => in_array($pesanan['metode'], ['qris', 'transfer', 'dompet'], true),
        ]);
    }
}
