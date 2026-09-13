<?php

use App\Http\Controllers\MenuController;
use App\Http\Controllers\PromoController;

test('setiap sajian punya foto yang benar-benar ada', function () {
    foreach (MenuController::semuaSajian() as $sajian) {
        expect($sajian['gambar'])->not->toBeNull("Sajian {$sajian['slug']} belum punya gambar")
            ->and(public_path($sajian['gambar']))->toBeFile("Berkas {$sajian['gambar']} tidak ditemukan");
    }
});

test('setiap gambar di galeri juga ada berkasnya', function () {
    foreach (MenuController::semuaSajian() as $sajian) {
        foreach ($sajian['galeri'] as $bidikan) {
            expect(public_path($bidikan['sumber']))->toBeFile("Berkas {$bidikan['sumber']} tidak ditemukan");
        }
    }
});

test('katalog tidak lagi memakai ilustrasi 2D', function () {
    foreach (MenuController::semuaSajian() as $sajian) {
        $semua = array_merge([$sajian['gambar']], array_column($sajian['galeri'], 'sumber'));

        foreach ($semua as $sumber) {
            expect($sumber)->not->toEndWith('.svg', "Sajian {$sajian['slug']} masih memakai ilustrasi");
        }
    }
});

test('slug sajian tidak ada yang kembar', function () {
    expect(MenuController::semuaSajian()->pluck('slug')->duplicates())->toBeEmpty();
});

test('daftar stan diambil dari katalog tanpa pengulangan', function () {
    $stan = MenuController::daftarStan();

    expect($stan)->not->toBeEmpty()
        ->and($stan->duplicates())->toBeEmpty()
        ->and($stan->all())->toContain('Stan Bu Rina');
});

test('kode promo memakai bentuk yang dimengerti keranjang', function () {
    foreach (PromoController::kodePromo() as $kode => $syarat) {
        expect($kode)->toBe(strtoupper($kode))
            ->and($syarat)->toHaveKeys(['potongan', 'belanjaMinimal', 'label']);
    }
});
