<?php

namespace Database\Factories;

use App\Models\ItemPesanan;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\Sajian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pesanan>
 */
class PesananFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => 'GRB-'.strtoupper(fake()->unique()->bothify('??##??')),
            'pengguna_id' => Pengguna::factory(),
            'nama' => fake()->name(),
            'kelas' => 'XI RPL 1',
            'waktu_ambil' => 'istirahat-1',
            'metode' => 'tunai',
            'subtotal' => 0,
            'potongan' => 0,
            'total' => 0,
            'status' => 'menunggu',
        ];
    }

    /**
     * Isi pesanan dengan sajian lalu hitung totalnya.
     *
     * @param  array<int, Sajian>  $daftarSajian
     */
    public function berisi(array $daftarSajian, int $jumlah = 1): static
    {
        return $this->afterCreating(function (Pesanan $pesanan) use ($daftarSajian, $jumlah) {
            foreach ($daftarSajian as $sajian) {
                ItemPesanan::query()->create([
                    'pesanan_id' => $pesanan->id,
                    'sajian_id' => $sajian->id,
                    'nama' => $sajian->nama,
                    'stan' => $sajian->stan,
                    'harga' => $sajian->harga,
                    'jumlah' => $jumlah,
                ]);
            }

            $subtotal = $pesanan->item()->get()->sum(fn (ItemPesanan $item) => $item->harga * $item->jumlah);
            $pesanan->update(['subtotal' => $subtotal, 'total' => $subtotal - $pesanan->potongan]);
        });
    }

    public function berstatus(string $status): static
    {
        return $this->state(fn (array $atribut) => ['status' => $status]);
    }
}
