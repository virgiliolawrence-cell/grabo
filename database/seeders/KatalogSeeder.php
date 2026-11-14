<?php

namespace Database\Seeders;

use App\Models\Diskon;
use App\Models\Kategori;
use App\Models\ProfilKantin;
use App\Models\Sajian;
use Illuminate\Database\Seeder;

/**
 * Isi awal katalog: kategori dan sajian dari config/menu.php, kode diskon,
 * dan profil kantin. Setelah ini semuanya dikelola lewat dasbor penjual.
 */
class KatalogSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('menu.kategori') as $urutanKategori => $isiKategori) {
            $kategori = Kategori::query()->updateOrCreate(
                ['label' => $isiKategori['label']],
                ['catatan' => $isiKategori['catatan'], 'urutan' => $urutanKategori],
            );

            foreach ($isiKategori['sajian'] as $urutan => $sajian) {
                Sajian::query()->updateOrCreate(['slug' => $sajian['slug']], [
                    'kategori_id' => $kategori->id,
                    'nama' => $sajian['nama'],
                    'stan' => $sajian['stan'],
                    'harga' => $sajian['harga'],
                    'jenis' => $sajian['jenis'],
                    'sematan' => $sajian['sematan'],
                    'nilai' => $sajian['nilai'],
                    'ulasan' => $sajian['ulasan'],
                    'terjual' => $sajian['terjual'],
                    'waktu_siap' => $sajian['waktuSiap'],
                    'ringkasan' => $sajian['ringkasan'],
                    'deskripsi' => $sajian['deskripsi'],
                    'gambar' => $sajian['gambar'],
                    'foto' => $sajian['foto'],
                    'galeri' => $sajian['galeri'],
                    'spesifikasi' => $sajian['spesifikasi'],
                    'tersedia' => true,
                    'urutan' => $urutan,
                ]);
            }
        }

        $daftarDiskon = [
            ['kode' => 'HEMAT14', 'label' => 'Paket hemat', 'potongan' => 2000, 'belanja_minimal' => 14000],
            ['kode' => 'ROTI21', 'label' => 'Beli 2 gratis 1', 'potongan' => 9000, 'belanja_minimal' => 18000],
            ['kode' => 'SEGAR5', 'label' => 'Promo minuman', 'potongan' => 1000, 'belanja_minimal' => 5000],
        ];

        foreach ($daftarDiskon as $diskon) {
            Diskon::query()->updateOrCreate(['kode' => $diskon['kode']], $diskon);
        }

        ProfilKantin::milikKantin();
    }
}
