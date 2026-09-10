<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Akun pengelola Grabo. Siswa tidak memakai tabel ini; datanya ada di Student.
 */
#[Fillable(['name', 'email', 'password', 'role', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const PERAN_ADMIN = 'admin';     // akses penuh, termasuk kelola admin
    public const PERAN_PETUGAS = 'petugas'; // petugas kantin: menu, pesanan, siswa

    /** Nama peran yang ditampilkan di layar. */
    public const NAMA_PERAN = [
        self::PERAN_ADMIN => 'Administrator',
        self::PERAN_PETUGAS => 'Petugas Kantin',
    ];

    /**
     * Kolom yang perlu diubah tipenya saat dibaca.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /** Boleh membuka dasbor? */
    public function bolehBukaDasbor(): bool
    {
        return $this->is_active && array_key_exists($this->role, self::NAMA_PERAN);
    }

    public function labelPeran(): string
    {
        return self::NAMA_PERAN[$this->role] ?? $this->role;
    }

    public function adalahAdmin(): bool
    {
        return $this->role === self::PERAN_ADMIN;
    }
}
