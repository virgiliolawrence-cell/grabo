<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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
            'metodeDaring' => [
                [
                    'value' => 'qris',
                    'label' => 'QRIS',
                    'note' => 'Bayar dari aplikasi bank atau dompet digital apa pun.',
                    'badge' => 'Paling cepat',
                ],
                [
                    'value' => 'transfer',
                    'label' => 'Transfer bank',
                    'note' => 'Nomor rekening virtual muncul setelah pesanan dikirim.',
                    'badge' => null,
                ],
                [
                    'value' => 'dompet',
                    'label' => 'Dompet digital',
                    'note' => 'GoPay, OVO, atau DANA yang terhubung ke akun sekolah.',
                    'badge' => null,
                ],
            ],
        ]);
    }

    /**
     * Terima pesanan, simpan ke database, lalu berikan kodenya.
     *
     * Angka dari form tidak dipercaya: harga tiap baris dibaca ulang dari
     * tabel menu dan potongan promo dari tabel diskon. Belum ada gerbang
     * pembayaran sungguhan, jadi statusnya berhenti di 'menunggu'.
     */
    public function store(Request $request): RedirectResponse
    {
        $pesanan = $request->validate([
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

        $baris = $this->hargaUlang($pesanan['keranjang']);

        if ($baris->isEmpty()) {
            return back()->withErrors(['keranjang' => 'Keranjangmu kosong atau menunya sudah tidak dijual.']);
        }

        $subtotal = (int) $baris->sum(fn (array $item) => $item['price'] * $item['qty']);
        $diskon = Discount::where('code', strtoupper((string) ($pesanan['promo'] ?? '')))->first();
        $potongan = $diskon?->appliesTo($subtotal) ? min($diskon->amount, $subtotal) : 0;

        $order = DB::transaction(function () use ($pesanan, $baris, $subtotal, $diskon, $potongan) {
            $order = Order::create([
                'code' => 'GRB-' . strtoupper(Str::random(6)),
                'student_id' => Student::where('name', $pesanan['nama'])->value('id'),
                'student_name' => $pesanan['nama'],
                'student_class' => $pesanan['kelas'],
                'pickup_slot' => $pesanan['waktu'],
                'payment_method' => $this->metodeTersimpan($pesanan['metode']),
                'payment_detail' => $pesanan['bank'] ?? $pesanan['dompet'] ?? null,
                'discount_id' => $potongan > 0 ? $diskon->id : null,
                'subtotal' => $subtotal,
                'discount_amount' => $potongan,
                'total' => $subtotal - $potongan,
                'status' => 'menunggu',
                'note' => $pesanan['catatan'] ?? null,
            ]);

            $order->items()->createMany($baris->all());

            if ($potongan > 0) {
                $diskon->increment('used_count');
            }

            return $order;
        });

        $pesanan['kode'] = $order->code;
        $pesanan['total'] = $order->total;

        return redirect()->route('checkout.done')->with('pesanan', $pesanan);
    }

    /**
     * Baca ulang harga tiap baris keranjang dari tabel menu.
     *
     * Baris yang slug-nya tidak dikenal atau menunya sudah disembunyikan
     * dibuang, jadi pesanan tidak bisa diisi menu karangan sendiri.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function hargaUlang(string $json): Collection
    {
        $keranjang = collect(json_decode($json, true) ?: []);
        $menu = MenuItem::available()
            ->whereIn('slug', $keranjang->pluck('slug')->filter()->all())
            ->get()
            ->keyBy('slug');

        return $keranjang
            ->filter(fn ($baris) => is_array($baris) && isset($menu[$baris['slug'] ?? '']))
            ->map(function (array $baris) use ($menu) {
                $item = $menu[$baris['slug']];
                $qty = max(1, min(20, (int) ($baris['qty'] ?? 1)));

                return [
                    'menu_item_id' => $item->id,
                    'name' => $item->name,
                    'stall' => $item->stall,
                    'price' => $item->price,
                    'qty' => $qty,
                    'options' => Str::limit((string) ($baris['options'] ?? ''), 190) ?: null,
                    'note' => Str::limit((string) ($baris['note'] ?? ''), 120) ?: null,
                ];
            })
            ->values();
    }

    /** Dompet digital diproses lewat QRIS, jadi laporannya dicatat sebagai QRIS. */
    private function metodeTersimpan(string $metode): string
    {
        return $metode === 'dompet' ? 'qris' : $metode;
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
