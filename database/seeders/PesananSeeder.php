<?php

namespace Database\Seeders;

use App\Models\Diskon;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Sajian;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

/**
 * Pesanan contoh supaya dasbor dan laporan tidak kosong.
 */
class PesananSeeder extends Seeder
{
    public function run(): void
    {
        // [kode, hari lalu, jam, nama murid, metode, status, promo, [slug => jumlah]]
        $daftarPesanan = [
            ['GRB-7Q2KXA', 0, '09.12', 'Alya Putri', 'qris', 'menunggu', 'HEMAT14', ['nasi-goreng-kampung' => 1, 'es-teh-manis' => 1]],
            ['GRB-3MFD8P', 0, '09.05', 'Bima Saputra', 'saldo', 'diproses', null, ['ayam-geprek' => 1, 'es-cendol' => 1]],
            ['GRB-K9W1ZT', 0, '08.58', 'Citra Lestari', 'tunai', 'siap', null, ['batagor-saus-kacang' => 2]],
            ['GRB-P4RN6E', 1, '12.04', 'Dimas Pratama', 'transfer', 'selesai', 'ROTI21', ['roti-bakar-coklat' => 3]],
            ['GRB-H2LC5U', 1, '11.56', 'Eka Wulandari', 'dompet', 'selesai', null, ['mie-ayam-jamur' => 1, 'susu-coklat-dingin' => 1]],
            ['GRB-B8YT3M', 1, '09.33', 'Fajar Nugroho', 'qris', 'batal', null, ['mie-goreng-jawa' => 1]],
            ['GRB-N5VJ0Q', 2, '12.10', 'Alya Putri', 'saldo', 'selesai', 'SEGAR5', ['es-cendol' => 1, 'roti-bakar-mentega' => 1]],
            ['GRB-D1XS7K', 2, '09.41', 'Bima Saputra', 'tunai', 'selesai', null, ['nasi-goreng-kampung' => 2, 'es-teh-tawar' => 2]],
            ['GRB-T6GA2R', 3, '12.02', 'Citra Lestari', 'qris', 'selesai', null, ['batagor-kuah-pedas' => 1, 'es-teh-manis' => 1]],
            ['GRB-Z0CE9H', 3, '09.27', 'Dimas Pratama', 'dompet', 'selesai', null, ['ayam-geprek' => 1]],
        ];

        $katalog = Sajian::query()->get()->keyBy('slug');

        foreach ($daftarPesanan as [$kode, $hariLalu, $jam, $nama, $metode, $status, $kodePromo, $isi]) {
            $murid = Pengguna::query()->where('email', PenggunaSeeder::emailMurid($nama))->firstOrFail();
            $waktu = Carbon::today()->subDays($hariLalu)->setTimeFromTimeString(str_replace('.', ':', $jam));

            $subtotal = collect($isi)->sum(fn (int $jumlah, string $slug) => $katalog[$slug]->harga * $jumlah);
            $potongan = $kodePromo ? Diskon::query()->where('kode', $kodePromo)->first()?->potonganUntuk($subtotal) ?? 0 : 0;

            $pesanan = Pesanan::query()->updateOrCreate(['kode' => $kode], [
                'pengguna_id' => $murid->id,
                'nama' => $murid->nama,
                'kelas' => $murid->kelas,
                'waktu_ambil' => 'istirahat-1',
                'metode' => $metode,
                'kode_promo' => $kodePromo,
                'subtotal' => $subtotal,
                'potongan' => $potongan,
                'total' => $subtotal - $potongan,
                'status' => $status,
            ]);
            $pesanan->forceFill(['created_at' => $waktu, 'updated_at' => $waktu])->save();

            $pesanan->item()->delete();

            foreach ($isi as $slug => $jumlah) {
                $pesanan->item()->create([
                    'sajian_id' => $katalog[$slug]->id,
                    'nama' => $katalog[$slug]->nama,
                    'stan' => $katalog[$slug]->stan,
                    'harga' => $katalog[$slug]->harga,
                    'jumlah' => $jumlah,
                    'sudah_disiapkan' => in_array($status, ['siap', 'selesai'], true),
                ]);
            }
        }
    }
}
