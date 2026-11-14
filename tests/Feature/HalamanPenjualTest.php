<?php

use App\Http\Controllers\PromoController;
use App\Models\Diskon;
use App\Models\ItemPesanan;
use App\Models\Pengguna;
use App\Models\Pesanan;
use App\Models\ProfilKantin;
use App\Models\Sajian;
use Tests\TestCase;

/*
 * Uji dasbor penjual: akses berdasarkan peran, checklist pesanan,
 * data pembeli, menu, diskon, laporan, dan profil.
 */

function sebagaiPenjual(): TestCase
{
    return test()->actingAs(Pengguna::factory()->penjual()->create());
}

test('tamu dialihkan ke halaman masuk', function () {
    $this->get(route('penjual.pembelian'))->assertRedirect(route('masuk'));
});

test('murid yang membuka dasbor dikeluarkan dan diminta masuk sebagai penjual', function () {
    $this->actingAs(Pengguna::factory()->create())
        ->get(route('penjual.pembelian'))
        ->assertRedirect(route('masuk'))
        ->assertSessionHas('status');

    $this->assertGuest();
});

test('semua halaman penjual terbuka untuk penjual', function (string $namaRute) {
    sebagaiPenjual()->get(route($namaRute))->assertOk();
})->with([
    'penjual.pembelian', 'penjual.pembeli', 'penjual.pembeli.tambah', 'penjual.menu', 'penjual.menu.tambah',
    'penjual.diskon', 'penjual.diskon.tambah', 'penjual.laporan', 'penjual.profil',
]);

test('hanya tab navigasi halaman yang dibuka yang disorot', function () {
    $penjual = Pengguna::factory()->penjual()->create();

    foreach (['penjual.pembelian' => 'Pembelian', 'penjual.pembeli' => 'Pembeli'] as $rute => $label) {
        $html = $this->actingAs($penjual)->get(route($rute))->assertOk()->getContent();

        preg_match_all('/<a[^>]*aria-current="page"[^>]*>\s*(\w+)\s*<\/a>/', $html, $tabAktif);

        expect($tabAktif[1])->toBe([$label]);
    }
});

test('mencentang semua menu membuat pesanan siap diambil', function () {
    $pesanan = Pesanan::factory()->berisi(Sajian::query()->take(2)->get()->all())->create();
    [$pertama, $kedua] = $pesanan->item;

    sebagaiPenjual()->patch(route('penjual.pembelian.centang', [$pesanan, $pertama]))->assertRedirect();
    expect($pertama->fresh()->sudah_disiapkan)->toBeTrue()
        ->and($pesanan->fresh()->status)->toBe('diproses');

    $this->patch(route('penjual.pembelian.centang', [$pesanan, $kedua]));
    expect($pesanan->fresh()->status)->toBe('siap');

    // Centang dibatalkan: kembali diproses.
    $this->patch(route('penjual.pembelian.centang', [$pesanan, $kedua]));
    expect($pesanan->fresh()->status)->toBe('diproses');
});

test('item pesanan lain tidak bisa dicentang lewat pesanan ini', function () {
    $pesanan = Pesanan::factory()->berisi([Sajian::query()->first()])->create();
    $lain = Pesanan::factory()->berisi([Sajian::query()->first()])->create();

    sebagaiPenjual()->patch(route('penjual.pembelian.centang', [$pesanan, $lain->item->first()]))->assertNotFound();
});

test('menyelesaikan pesanan mencentang semua menunya', function () {
    $pesanan = Pesanan::factory()->berisi(Sajian::query()->take(2)->get()->all())->create();

    sebagaiPenjual()->patch(route('penjual.pembelian.status', $pesanan), ['status' => 'selesai'])->assertRedirect();

    expect($pesanan->fresh()->status)->toBe('selesai')
        ->and($pesanan->item()->where('sudah_disiapkan', false)->count())->toBe(0);
});

test('rincian pembeli menampilkan semua yang pernah dibeli murid', function () {
    $murid = Pengguna::factory()->create(['nama' => 'Rina Murid']);
    $nasiGoreng = Sajian::query()->where('slug', 'nasi-goreng-kampung')->first();
    $esCendol = Sajian::query()->where('slug', 'es-cendol')->first();

    Pesanan::factory()->for($murid, 'pengguna')->berisi([$nasiGoreng])->create();
    Pesanan::factory()->for($murid, 'pengguna')->berisi([$esCendol])->berstatus('selesai')->create();

    sebagaiPenjual()->get(route('penjual.pembeli.rincian', $murid))
        ->assertOk()
        ->assertSee('Rina Murid')
        ->assertSee('Nasi Goreng Kampung')
        ->assertSee('Es Cendol')
        ->assertSee('Pesanan berjalan');
});

test('penjual bisa mendaftarkan akun murid', function () {
    sebagaiPenjual()->post(route('penjual.pembeli.simpan'), [
        'nama' => 'Murid Baru',
        'nis' => '2026999',
        'kelas' => 'X RPL 1',
        'email' => 'baru@grabo.sch.id',
        'kata_sandi' => 'rahasia-baru',
        'ulangi_kata_sandi' => 'rahasia-baru',
    ])->assertRedirect();

    expect(Pengguna::query()->where('email', 'baru@grabo.sch.id')->first())
        ->peran->toBe('murid')
        ->nis->toBe('2026999');
});

test('menu baru langsung tampil di halaman siswa dan bisa ditandai habis', function () {
    sebagaiPenjual()->post(route('penjual.menu.simpan'), [
        'nama' => 'Soto Ayam',
        'kategori_id' => Sajian::query()->first()->kategori_id,
        'stan' => 'Stan Bu Rina',
        'harga' => 12000,
        'jenis' => 'makanan',
        'tersedia' => '1',
    ])->assertRedirect(route('penjual.menu'));

    $soto = Sajian::query()->where('slug', 'soto-ayam')->firstOrFail();
    $this->get(route('menu'))->assertSee('Soto Ayam');

    $this->patch(route('penjual.menu.ketersediaan', $soto));
    expect($soto->fresh()->tersedia)->toBeFalse();
    $this->get(route('menu'))->assertDontSee('Soto Ayam');
});

test('kode diskon yang sudah ada ditolak', function () {
    sebagaiPenjual()->post(route('penjual.diskon.simpan'), [
        'kode' => 'hemat14',
        'label' => 'Duplikat',
        'potongan' => 1000,
        'belanja_minimal' => 5000,
    ])->assertSessionHasErrors('kode');
});

test('diskon nonaktif hilang dari daftar promo keranjang', function () {
    $diskon = Diskon::query()->where('kode', 'HEMAT14')->first();

    sebagaiPenjual()->patch(route('penjual.diskon.aktif', $diskon));

    expect(PromoController::kodePromo())->not->toHaveKey('HEMAT14');
});

test('laporan menghitung pendapatan tanpa pesanan batal', function () {
    $sajian = Sajian::query()->where('harga', 12000)->first();
    Pesanan::factory()->berisi([$sajian])->state(['metode' => 'qris'])->create();
    Pesanan::factory()->berisi([$sajian])->state(['metode' => 'qris'])->berstatus('batal')->create();
    Pesanan::factory()->berisi([$sajian])->state(['metode' => 'tunai'])->create();

    sebagaiPenjual()->get(route('penjual.laporan', ['metode' => 'qris']))
        ->assertOk()
        ->assertSee('1 transaksi')
        ->assertSee('1 batal')
        ->assertSee('Rp 12.000');
});

test('transaksi bisa dihapus dari laporan beserta isinya', function () {
    $pesanan = Pesanan::factory()->berisi(Sajian::query()->take(2)->get()->all())->create();
    $lain = Pesanan::factory()->berisi([Sajian::query()->first()])->create();

    sebagaiPenjual()->from(route('penjual.laporan'))
        ->delete(route('penjual.laporan.hapus', $pesanan))
        ->assertRedirect(route('penjual.laporan'))
        ->assertSessionHas('status');

    expect(Pesanan::query()->find($pesanan->id))->toBeNull()
        ->and(ItemPesanan::query()->where('pesanan_id', $pesanan->id)->count())->toBe(0)
        ->and($lain->fresh())->not->toBeNull();
});

test('murid tidak bisa menghapus transaksi', function () {
    $pesanan = Pesanan::factory()->create();

    $this->actingAs(Pengguna::factory()->create())
        ->delete(route('penjual.laporan.hapus', $pesanan))
        ->assertRedirect(route('masuk'));

    expect($pesanan->fresh())->not->toBeNull();
});

test('laporan menolak rentang tanggal terbalik', function () {
    sebagaiPenjual()->get(route('penjual.laporan', ['dari' => '2026-11-13', 'sampai' => '2026-11-01']))
        ->assertSessionHasErrors('sampai');
});

test('profil penjual tersimpan di database', function () {
    sebagaiPenjual()->put(route('penjual.profil.simpan'), [
        'nama' => 'Kantin Baru',
        'pengelola' => 'OSIS',
        'email' => 'kantin@grabo.sch.id',
        'telepon' => '0812',
        'lokasi' => 'Gedung A',
        'jam_buka' => '07.00',
        'jam_tutup' => '14.00',
    ])->assertRedirect(route('penjual.profil'));

    expect(ProfilKantin::milikKantin()->nama)->toBe('Kantin Baru');
});
