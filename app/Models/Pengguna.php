<?php

namespace App\Models;

use Database\Factories\PenggunaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Akun pengguna bawaan Laravel.
 *
 * Belum dipakai Grabo: halaman siswa masih memakai penanda session, dan akun
 * pengelola baru dibuat saat dasbor dikerjakan. Nama kolomnya tetap bahasa
 * Inggris karena itu skema tabel bawaan Laravel.
 */
#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class Pengguna extends Authenticatable
{
    /** @use HasFactory<PenggunaFactory> */
    use HasFactory, Notifiable;

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
        ];
    }
}
