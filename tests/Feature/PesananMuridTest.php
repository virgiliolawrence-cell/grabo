<?php

use App\Models\Pengguna;
use App\Models\Pesanan;

/*
 * Alur murid: masuk dengan akun asli, kirim pesanan, lalu pesanannya
 * tersimpan dan terlihat di halaman Pesanan Saya serta dasbor penjual.
 */

function isianPesanan(array $tambahan = []): array
{
    return [
        'nama' => 'Alya Putri',
        'kelas' => 'XI RPL 1',
        'waktu' => 'istirahat-1',
        'metode' => 'tunai',
        'keranjang' => json_encode([
            ['slug' => 'nasi-goreng-kampung', 'jumlah' => 1, 'pilihan' => 'Pedas sedang', 'catatan' => 'Tanpa timun', 'harga' => 1],
            ['slug' => 'es-teh-manis', 'jumlah' => 2],
            ['slug' => 'menu-karangan', 'jumlah' => 5],
        ]),
        'promo' => 'hemat14',
        'total' => 1,
        ...$tambahan,
    ];
}

test('murid bisa masuk dengan akunnya dan penjual diarahkan ke dasbor', function () {
    $murid = Pengguna::factory()->create();
    $penjual = Pengguna::factory()->penjual()->create();

    $this->post(route('masuk.proses'), ['email' => $murid->email, 'kata_sandi' => 'password'])
        ->assertRedirect(route('beranda'));
    $this->post(route('keluar'));

    $this->post(route('masuk.proses'), ['email' => $penjual->email, 'kata_sandi' => 'password'])
        ->assertRedirect(route('penjual.pembelian'));
});

test('sandi yang salah ditolak', function () {
    $murid = Pengguna::factory()->create();

    $this->from(route('masuk'))
        ->post(route('masuk.proses'), ['email' => $murid->email, 'kata_sandi' => 'salah-sekali'])
        ->assertSessionHasErrors('email');
    $this->assertGuest();

    // Pesan galatnya benar-benar tampil di halaman masuk.
    $this->from(route('masuk'))
        ->followingRedirects()
        ->post(route('masuk.proses'), ['email' => $murid->email, 'kata_sandi' => 'salah-sekali'])
        ->assertSee('Email atau kata sandi tidak cocok.');
});

test('pesanan murid tersimpan dengan harga dari katalog', function () {
    $murid = Pengguna::factory()->create();

    $this->actingAs($murid)->post(route('pembayaran.kirim'), isianPesanan())
        ->assertRedirect(route('pembayaran.selesai'));

    $pesanan = Pesanan::query()->with('item')->sole();

    // 12.000 + 2 × 4.000 = 20.000, HEMAT14 memotong 2.000. Menu karangan dibuang.
    expect($pesanan)
        ->pengguna_id->toBe($murid->id)
        ->subtotal->toBe(20000)
        ->potongan->toBe(2000)
        ->total->toBe(18000)
        ->kode_promo->toBe('HEMAT14')
        ->status->toBe('menunggu')
        ->and($pesanan->item)->toHaveCount(2)
        ->and($pesanan->item->first())
        ->pilihan->toBe('Pedas sedang')
        ->catatan->toBe('Tanpa timun');

    $this->get(route('pembayaran.selesai'))->assertOk()->assertSee($pesanan->kode);
    $this->get(route('pesanan-saya'))->assertOk()->assertSee($pesanan->kode)->assertSee('Nasi Goreng Kampung');
});

test('pesanan baru muncul di dasbor penjual', function () {
    $murid = Pengguna::factory()->create(['nama' => 'Bima Saputra']);
    $this->actingAs($murid)->post(route('pembayaran.kirim'), isianPesanan(['nama' => 'Bima Saputra']));
    $kode = Pesanan::query()->value('kode');

    $this->actingAs(Pengguna::factory()->penjual()->create())
        ->get(route('penjual.pembelian'))
        ->assertSee($kode)
        ->assertSee('Bima Saputra')
        ->assertSee('Tanpa timun');
});

test('pesanan saya hanya menampilkan pesanan milik sendiri', function () {
    $milikLain = Pesanan::factory()->create();

    $this->actingAs(Pengguna::factory()->create())
        ->get(route('pesanan-saya'))
        ->assertDontSee($milikLain->kode);
});

test('akun penjual tidak bisa membuat pesanan', function () {
    $this->actingAs(Pengguna::factory()->penjual()->create())
        ->post(route('pembayaran.kirim'), isianPesanan())
        ->assertSessionHasErrors('keranjang');

    expect(Pesanan::query()->count())->toBe(0);
});
