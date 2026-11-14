<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Pengguna>
 */
class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    /**
     * Sandi yang sudah di-hash, dipakai ulang supaya pengujian tidak lambat.
     */
    protected static ?string $sandi;

    /**
     * Bawaannya akun murid.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'kata_sandi' => static::$sandi ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'peran' => Pengguna::PERAN_MURID,
            'nis' => (string) fake()->unique()->numberBetween(2024001, 2026999),
            'kelas' => fake()->randomElement(['X', 'XI', 'XII']).' '.fake()->randomElement(['RPL', 'TKJ', 'AKL']).' '.fake()->numberBetween(1, 2),
        ];
    }

    /**
     * Akun pengelola dasbor penjual.
     */
    public function penjual(): static
    {
        return $this->state(fn (array $atribut) => [
            'peran' => Pengguna::PERAN_PENJUAL,
            'nis' => null,
            'kelas' => null,
        ]);
    }

    public function belumTerverifikasi(): static
    {
        return $this->state(fn (array $atribut) => [
            'email_verified_at' => null,
        ]);
    }
}
