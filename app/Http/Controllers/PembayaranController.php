<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use App\Models\Pesanan;
use App\Models\Sajian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
     * Simpan pesanan murid ke database lalu berikan kodenya.
     *
     * Harga dan potongan dihitung ulang dari katalog dan tabel diskon,
     * jadi nilai kiriman browser tidak dipercaya bulat-bulat.
     */
    public function simpan(Request $permintaan): RedirectResponse
    {
        $pengguna = $permintaan->user();

        if ($pengguna->adalahPenjual()) {
            return back()->withErrors(['keranjang' => 'Akun penjual tidak bisa membuat pesanan. Masuk dengan akun murid.']);
        }

        $isian = $permintaan->validate([
            'nama' => ['required', 'string', 'max:60'],
            'kelas' => ['required', 'string', 'max:20'],
            'waktu' => ['required', Rule::in(array_keys(Pesanan::WAKTU_AMBIL))],
            'metode' => ['required', Rule::in(array_keys(Pesanan::METODE))],
            'bank' => ['nullable', 'string', 'max:30'],
            'dompet' => ['nullable', 'string', 'max:30'],
            'catatan' => ['nullable', 'string', 'max:200'],
            'promo' => ['nullable', 'string', 'max:30'],
            'keranjang' => ['required', 'json'],
            'total' => ['required', 'integer', 'min:0'],
        ]);

        $barisKeranjang = $this->hitungUlangHarga($isian['keranjang']);

        if ($barisKeranjang->isEmpty()) {
            return back()->withErrors(['keranjang' => 'Keranjangmu kosong atau menunya sudah tidak dijual.']);
        }

        $subtotal = (int) $barisKeranjang->sum(fn (array $baris) => $baris['harga'] * $baris['jumlah']);
        $diskon = Diskon::query()
            ->where('aktif', true)
            ->where('kode', strtoupper((string) ($isian['promo'] ?? '')))
            ->first();
        $potongan = $diskon?->potonganUntuk($subtotal) ?? 0;

        $pesanan = DB::transaction(function () use ($pengguna, $isian, $barisKeranjang, $subtotal, $diskon, $potongan) {
            $pesanan = Pesanan::query()->create([
                'kode' => Pesanan::buatKode(),
                'pengguna_id' => $pengguna->id,
                'nama' => $isian['nama'],
                'kelas' => $isian['kelas'],
                'waktu_ambil' => $isian['waktu'],
                'metode' => $isian['metode'],
                'bank' => $isian['metode'] === 'transfer' ? ($isian['bank'] ?? null) : null,
                'dompet' => $isian['metode'] === 'dompet' ? ($isian['dompet'] ?? null) : null,
                'catatan' => $isian['catatan'] ?? null,
                'kode_promo' => $potongan > 0 ? $diskon->kode : null,
                'subtotal' => $subtotal,
                'potongan' => $potongan,
                'total' => $subtotal - $potongan,
                'status' => 'menunggu',
            ]);

            $pesanan->item()->createMany($barisKeranjang->all());

            return $pesanan;
        });

        return redirect()->route('pembayaran.selesai')->with('kodePesanan', $pesanan->kode);
    }

    /**
     * Baca ulang harga tiap baris keranjang dari katalog.
     *
     * Baris yang slug-nya tidak dikenal atau menunya sedang tidak dijual
     * dibuang, jadi pesanan tidak bisa diisi menu karangan sendiri.
     *
     * @return Collection<int, array<string, mixed>>
     */
    private function hitungUlangHarga(string $json): Collection
    {
        $keranjang = collect(json_decode($json, true) ?: [])->filter(fn ($baris) => is_array($baris));
        $katalog = Sajian::query()
            ->where('tersedia', true)
            ->whereIn('slug', $keranjang->pluck('slug')->filter()->all())
            ->get()
            ->keyBy('slug');

        return $keranjang
            ->filter(fn (array $baris) => isset($katalog[$baris['slug'] ?? '']))
            ->map(function (array $baris) use ($katalog) {
                $sajian = $katalog[$baris['slug']];

                return [
                    'sajian_id' => $sajian->id,
                    'nama' => $sajian->nama,
                    'stan' => $sajian->stan,
                    'harga' => $sajian->harga,
                    'jumlah' => max(1, min(20, (int) ($baris['jumlah'] ?? 1))),
                    'pilihan' => Str::limit(trim((string) ($baris['pilihan'] ?? '')), 250, '') ?: null,
                    'catatan' => Str::limit(trim((string) ($baris['catatan'] ?? '')), 200, '') ?: null,
                ];
            })
            ->values();
    }

    /**
     * Halaman konfirmasi setelah pesanan dikirim.
     */
    public function selesai(Request $permintaan): View|RedirectResponse
    {
        $pesanan = Pesanan::query()
            ->where('pengguna_id', $permintaan->user()->id)
            ->where('kode', $permintaan->session()->get('kodePesanan'))
            ->first();

        // Halaman ini hanya berarti tepat setelah pesanan dikirim.
        if (! $pesanan) {
            return redirect()->route('menu');
        }

        return view('pembayaran-selesai', [
            'pesanan' => [
                'kode' => $pesanan->kode,
                'nama' => $pesanan->nama,
                'kelas' => $pesanan->kelas,
                'waktu' => $pesanan->waktu_ambil,
                'metode' => $pesanan->metode,
                'bank' => $pesanan->bank,
                'dompet' => $pesanan->dompet,
                'catatan' => $pesanan->catatan,
                'total' => $pesanan->total,
            ],
            'labelMetode' => [
                'tunai' => 'Tunai di loket',
                'saldo' => 'Saldo kartu pelajar',
                'qris' => 'QRIS',
                'transfer' => 'Transfer bank '.($pesanan->bank ?? ''),
                'dompet' => 'Dompet digital '.($pesanan->dompet ?? ''),
            ],
            'labelWaktu' => [
                'sekarang' => 'Secepatnya',
                'istirahat-1' => 'Istirahat 1 &middot; pukul 09.30',
                'istirahat-2' => 'Istirahat 2 &middot; pukul 12.00',
            ],
            // Pembayaran daring masih menunggu konfirmasi; tunai/saldo langsung disiapkan.
            'daring' => in_array($pesanan->metode, ['qris', 'transfer', 'dompet'], true),
        ]);
    }
}
