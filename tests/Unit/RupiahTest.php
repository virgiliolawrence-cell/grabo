<?php

use App\Models\Discount;
use App\Models\MenuItem;

test('harga menu ditulis dalam format rupiah', function () {
    $menu = new MenuItem(['price' => 12500]);

    expect($menu->price_label)->toBe('Rp 12.500');
});

test('diskon hanya berlaku bila belanja mencapai minimal', function () {
    $diskon = new Discount([
        'code' => 'UJI',
        'amount' => 2000,
        'min_spend' => 15000,
        'is_active' => true,
    ]);

    expect($diskon->appliesTo(20000))->toBeTrue()
        ->and($diskon->appliesTo(14999))->toBeFalse();
});

test('diskon yang dinonaktifkan tidak pernah berlaku', function () {
    $diskon = new Discount([
        'code' => 'MATI',
        'amount' => 2000,
        'min_spend' => 0,
        'is_active' => false,
    ]);

    expect($diskon->appliesTo(50000))->toBeFalse();
});
