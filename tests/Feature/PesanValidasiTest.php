<?php

use App\Models\Pengguna;
use App\Models\Sajian;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

/*
 * Semua pesan galat formulir harus sudah diterjemahkan. Kalau sebuah aturan
 * belum punya baris di lang/id/validation.php, Laravel menampilkan kuncinya
 * mentah-mentah, misalnya "validation.gte.numeric".
 */

// Galat validasi dibiarkan terlempar supaya pesannya bisa dibaca langsung.
beforeEach(fn () => $this->withoutExceptionHandling());

/**
 * @param  array<string, mixed>  $isian
 * @return array<int, string>
 */
function pesanGalat(string $metode, string $alamat, array $isian, ?Pengguna $pengguna = null): array
{
    $uji = $pengguna ? test()->actingAs($pengguna) : test();

    try {
        $metode === 'get'
            ? $uji->get($alamat.'?'.http_build_query($isian))
            : $uji->{$metode}($alamat, $isian);
    } catch (ValidationException $galat) {
        return Arr::flatten($galat->errors());
    }

    return [];
}

test('tidak ada pesan galat yang masih berupa kunci terjemahan', function (string $metode, string $rute, array $isian, bool $penjual) {
    $pengguna = $penjual ? Pengguna::factory()->penjual()->create() : Pengguna::factory()->create();

    $daftarPesan = pesanGalat($metode, route($rute), $isian, $pengguna);

    expect($daftarPesan)->not->toBeEmpty();

    foreach ($daftarPesan as $pesan) {
        expect($pesan)->not->toStartWith('validation.');
    }
})->with([
    'diskon: belanja minimal < potongan' => ['post', 'penjual.diskon.simpan', ['kode' => 'AB', 'label' => '', 'potongan' => 5000, 'belanja_minimal' => 4000], true],
    'diskon: kosong' => ['post', 'penjual.diskon.simpan', [], true],
    'menu: salah semua' => ['post', 'penjual.menu.simpan', ['nama' => '', 'kategori_id' => 999, 'harga' => 10, 'jenis' => 'lain', 'gambar' => 'bukan-berkas'], true],
    'profil: jam salah' => ['put', 'penjual.profil.simpan', ['nama' => '', 'email' => 'bukan-email', 'jam_buka' => '7 pagi', 'jam_tutup' => '06.00'], true],
    'pembeli: data kurang' => ['post', 'penjual.pembeli.simpan', ['email' => 'x', 'kata_sandi' => 'a', 'ulangi_kata_sandi' => 'b'], true],
    'laporan: tanggal terbalik' => ['get', 'penjual.laporan', ['dari' => '2026-11-13', 'sampai' => '2026-11-01', 'metode' => 'emas'], true],
    'pembayaran: kosong' => ['post', 'pembayaran.kirim', ['waktu' => 'besok', 'metode' => 'emas', 'keranjang' => '{rusak'], false],
]);

test('belanja minimal yang lebih kecil dari potongan dijelaskan dengan jelas', function () {
    $pesan = pesanGalat('post', route('penjual.diskon.simpan'), [
        'kode' => 'HEMAT50', 'label' => 'Uji', 'potongan' => 5000, 'belanja_minimal' => 4000,
    ], Pengguna::factory()->penjual()->create());

    expect($pesan)->toContain('Belanja minimal tidak boleh lebih kecil dari potongannya (Rp 5000). Kalau tidak, diskonnya bisa membuat total menjadi nol.');
});

test('nama di formulir menu disebut nama menu, bukan nama siswa', function () {
    $pesan = pesanGalat('post', route('penjual.menu.simpan'), [
        'nama' => '', 'kategori_id' => Sajian::query()->value('kategori_id'), 'stan' => 'Stan', 'harga' => 5000, 'jenis' => 'makanan',
    ], Pengguna::factory()->penjual()->create());

    expect($pesan)->toBe(['Nama menu wajib diisi.']);
});
