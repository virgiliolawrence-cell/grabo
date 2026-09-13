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

    /** Kata sandi yang sedang dipakai factory ini. */
    protected static ?string $sandi;

    /**
     * Nilai bawaan model saat dibuat lewat factory.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$sandi ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /** Akun yang emailnya belum diverifikasi. */
    public function belumTerverifikasi(): static
    {
        return $this->state(fn (array $atribut) => [
            'email_verified_at' => null,
        ]);
    }
}
