<?php

use App\Models\Pengguna;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

test('pengguna memakai tabel pengguna dengan kolom berbahasa Indonesia', function () {
    $pengguna = Pengguna::factory()->create(['nama' => 'Alya Putri']);

    expect(Schema::hasTable('users'))->toBeFalse()
        ->and(Schema::hasColumns('pengguna', ['nama', 'email', 'kata_sandi', 'peran', 'nis', 'kelas']))->toBeTrue()
        ->and(Pengguna::query()->find($pengguna->id)->nama)->toBe('Alya Putri');
});

test('kata sandi disimpan ter-hash di kolom kata_sandi dan bisa dipakai masuk', function () {
    $pengguna = Pengguna::factory()->create(['kata_sandi' => 'rahasia-sekali']);

    expect($pengguna->getAttributes()['kata_sandi'])->not->toBe('rahasia-sekali')
        ->and(Auth::attempt(['email' => $pengguna->email, 'password' => 'rahasia-sekali']))->toBeTrue();
});
