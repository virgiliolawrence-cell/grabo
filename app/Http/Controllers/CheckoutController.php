<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Formulir pembayaran.
     */
    public function create(): View
    {
        return view('checkout', [
            'waktuPilihan' => [
                ['value' => 'sekarang', 'label' => 'Secepatnya', 'note' => 'Disiapkan begitu stan menerima pesanan'],
                ['value' => 'istirahat-1', 'label' => 'Istirahat 1', 'note' => 'Siap diambil pukul 09.30'],
                ['value' => 'istirahat-2', 'label' => 'Istirahat 2', 'note' => 'Siap diambil pukul 12.00'],
            ],
            'metodeTempat' => [
                [
                    'value' => 'tunai',
                    'label' => 'Tunai di loket',
                    'note' => 'Bayar langsung ke petugas saat mengambil pesanan.',
                    'badge' => null,
                ],
                [
                    'value' => 'saldo',
                    'label' => 'Saldo kartu pelajar',
                    'note' => 'Saldo dipotong otomatis saat pesanan diserahkan.',
                    'badge' => 'Tanpa uang kembalian',
                ],
            ],
            'metodeOnline' => [
                [
                    'value' => 'qris',
                    'label' => 'QRIS',
                    'note' => 'Bayar dari aplikasi bank atau e-wallet apa pun.',
                    'badge' => 'Paling cepat',
                ],
                [
                    'value' => 'transfer',
                    'label' => 'Transfer bank',
                    'note' => 'Nomor virtual account muncul setelah pesanan dikirim.',
                    'badge' => null,
                ],
                [
                    'value' => 'ewallet',
                    'label' => 'E-wallet',
                    'note' => 'GoPay, OVO, atau DANA yang terhubung ke akun sekolah.',
                    'badge' => null,
                ],
            ],
        ]);
    }

    /**
     * Terima pesanan lalu berikan kodenya.
     *
     * Belum ada gerbang pembayaran maupun tabel pesanan. Totalnya pun masih
     * dikirim dari sisi klien; begitu keranjang disimpan di server, hitung
     * ulang total dari harga katalog dan jangan percaya nilai dari form.
     */
    public function store(Request $request): RedirectResponse
    {
        $pesanan = $request->validate([
            'nama' => ['required', 'string', 'max:60'],
            'kelas' => ['required', 'string', 'max:20'],
            'waktu' => ['required', 'in:sekarang,istirahat-1,istirahat-2'],
            'metode' => ['required', 'in:tunai,saldo,qris,transfer,ewallet'],
            'bank' => ['nullable', 'string', 'max:30'],
            'ewallet' => ['nullable', 'string', 'max:30'],
            'catatan' => ['nullable', 'string', 'max:200'],
            'total' => ['required', 'integer', 'min:0'],
        ]);

        $pesanan['kode'] = 'GRB-' . strtoupper(Str::random(6));

        return redirect()->route('checkout.done')->with('pesanan', $pesanan);
    }

    /**
     * Halaman konfirmasi setelah pesanan dikirim.
     */
    public function done(Request $request): View|RedirectResponse
    {
        $pesanan = $request->session()->get('pesanan');

        // Halaman ini hanya berarti tepat setelah pesanan dikirim.
        if (! $pesanan) {
            return redirect()->route('menu');
        }

        return view('checkout-done', [
            'pesanan' => $pesanan,
            'labelMetode' => [
                'tunai' => 'Tunai di loket',
                'saldo' => 'Saldo kartu pelajar',
                'qris' => 'QRIS',
                'transfer' => 'Transfer bank ' . ($pesanan['bank'] ?? ''),
                'ewallet' => 'E-wallet ' . ($pesanan['ewallet'] ?? ''),
            ],
            'labelWaktu' => [
                'sekarang' => 'Secepatnya',
                'istirahat-1' => 'Istirahat 1 &middot; pukul 09.30',
                'istirahat-2' => 'Istirahat 2 &middot; pukul 12.00',
            ],
        ]);
    }
}
