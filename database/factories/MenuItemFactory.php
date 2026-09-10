<?php

namespace Database\Factories;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
{
    protected $model = MenuItem::class;

    /**
     * Nilai bawaan model saat dibuat lewat factory.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nama = fake()->words(2, true);

        return [
            'slug' => Str::slug($nama) . '-' . fake()->unique()->numberBetween(1, 99999),
            'name' => Str::title($nama),
            'stall' => 'Stan ' . fake()->firstName(),
            'category' => 'Makanan Berat',
            'type' => 'makanan',
            'price' => fake()->numberBetween(4, 25) * 1000,
            'badge' => null,
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image' => null,
            'photo' => false,
            'is_available' => true,
            'stock' => fake()->numberBetween(5, 50),
            'gallery' => [],
            'specs' => [],
            'rating' => 4.5,
            'reviews' => fake()->numberBetween(0, 300),
            'sold' => fake()->numberBetween(0, 2000),
            'ready' => '±5 menit',
        ];
    }

    /** Menu yang disembunyikan dari halaman siswa. */
    public function disembunyikan(): static
    {
        return $this->state(fn () => ['is_available' => false]);
    }
}
