<?php

use App\Http\Controllers\MenuController;

/*
 * Uji dasar halaman siswa. Semua halaman dijaga middleware 'siswa',
 * jadi statusnya ditandai dulu di session sebelum halamannya dibuka.
 */

function masuk(): Tests\TestCase
{
    return test()->withSession([
        'grabo_sudah_masuk' => true,
        'grabo_pengguna' => 'siswa@grabo.sch.id',
    ]);
}

test('halaman siswa mengalihkan ke halaman masuk kalau belum masuk', function () {
    $this->get(route('beranda'))->assertRedirect(route('masuk'));
    $this->get(route('menu'))->assertRedirect(route('masuk'));
    $this->get(route('kontak'))->assertRedirect(route('masuk'));
    $this->get(route('pembayaran'))->assertRedirect(route('masuk'));
});

test('semua halaman siswa terbuka setelah masuk', function (string $namaRute) {
    masuk()->get(route($namaRute))->assertStatus(200);
})->with(['beranda', 'menu', 'promo', 'kontak', 'pembayaran']);

test('halaman menu menampilkan seluruh sajian dari katalog', function () {
    $halaman = masuk()->get(route('menu'))->assertStatus(200);

    foreach (MenuController::semuaSajian() as $sajian) {
        $halaman->assertSee($sajian['nama']);
    }
});

test('halaman menu bisa disaring per stan', function () {
    masuk()->get(route('menu', ['stan' => 'Stan Bu Rina']))
        ->assertStatus(200)
        ->assertSee('Nasi Goreng Kampung')
        ->assertDontSee('Susu Coklat Dingin');
});

test('slug sajian yang tidak dikenal menghasilkan 404', function () {
    masuk()->get(route('menu.rincian', 'sajian-karangan'))->assertStatus(404);
});
