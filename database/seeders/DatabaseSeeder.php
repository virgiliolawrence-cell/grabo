<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Isi database dengan data awal.
     */
    public function run(): void
    {
        $this->call(GraboSeeder::class);
    }
}
