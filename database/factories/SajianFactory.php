<?php

namespace Database\Factories;

use App\Models\Kategori;
use App\Models\Sajian;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Sajian>
 */
class SajianFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = ucwords(fake()->unique()->words(2, true));

        return [
            'kategori_id' => fn () => Kategori::query()->firstOrCreate(['label' => 'Makanan Berat'])->id,
            'slug' => str($nama)->slug()->value(),
            'nama' => $nama,
            'stan' => 'Stan '.fake()->firstName(),
            'harga' => fake()->numberBetween(3, 15) * 1000,
            'jenis' => 'makanan',
            'tersedia' => true,
        ];
    }

    public function habis(): static
    {
        return $this->state(fn (array $atribut) => ['tersedia' => false]);
    }
}
