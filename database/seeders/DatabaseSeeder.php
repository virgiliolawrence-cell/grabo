<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Isi database untuk pengembangan: katalog, akun contoh, dan pesanan contoh.
     */
    public function run(): void
    {
        $this->call([
            KatalogSeeder::class,
            PenggunaSeeder::class,
            PesananSeeder::class,
        ]);
    }
}
