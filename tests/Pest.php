<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Kelas dasar pengujian
|--------------------------------------------------------------------------
|
| Setiap closure pengujian terikat ke sebuah kelas PHPUnit. Di sini semua
| pengujian di folder Feature dan Unit memakai TestCase milik Laravel, dan
| RefreshDatabase menyiapkan database SQLite di memori sebelum tiap uji.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Harapan tambahan
|--------------------------------------------------------------------------
|
| Tempat menambah metode expect() sendiri bila ada pemeriksaan yang sering
| diulang di banyak berkas pengujian.
|
*/

expect()->extend('berupaRupiah', function () {
    return $this->toMatch('/^Rp [\d.]+$/');
});
