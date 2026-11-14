<?php

namespace Tests;

use Database\Seeders\KatalogSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Katalog (menu, diskon, profil kantin) diisi sebelum tiap uji, karena
     * hampir semua halaman membacanya dari database.
     */
    protected bool $seed = true;

    protected string $seeder = KatalogSeeder::class;
}
