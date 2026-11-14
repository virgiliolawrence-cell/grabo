<?php

namespace Database\Factories;

use App\Models\Diskon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Diskon>
 */
class DiskonFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->unique()->bothify('HEMAT##')),
            'label' => 'Promo '.fake()->word(),
            'potongan' => 2000,
            'belanja_minimal' => 10000,
            'aktif' => true,
        ];
    }

    public function nonaktif(): static
    {
        return $this->state(fn (array $atribut) => ['aktif' => false]);
    }
}
