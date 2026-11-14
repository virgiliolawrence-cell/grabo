<?php

namespace App\Models;

use Database\Factories\PenggunaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Akun Grabo: penjual (pengelola dasbor) atau murid (pemesan).
 *
 * Tabelnya 'pengguna' dengan kolom nama & kata_sandi. Kolom yang dibaca
 * Laravel sendiri (email_verified_at, remember_token) tetap memakai nama bawaan.
 */
#[Table('pengguna')]
#[Fillable(['nama', 'email', 'kata_sandi', 'peran', 'nis', 'kelas'])]
#[Hidden(['kata_sandi', 'remember_token'])]
class Pengguna extends Authenticatable
{
    /** @use HasFactory<PenggunaFactory> */
    use HasFactory, Notifiable;

    public const PERAN_PENJUAL = 'penjual';

    public const PERAN_MURID = 'murid';

    /**
     * Kolom yang perlu diubah tipenya saat dibaca.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'kata_sandi' => 'hashed',
        ];
    }

    /**
     * Beri tahu Laravel bahwa kata sandi disimpan di kolom 'kata_sandi'.
     */
    public function getAuthPasswordName(): string
    {
        return 'kata_sandi';
    }

    /**
     * Pesanan yang pernah dibuat murid ini.
     *
     * @return HasMany<Pesanan, $this>
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'pengguna_id');
    }

    public function adalahPenjual(): bool
    {
        return $this->peran === self::PERAN_PENJUAL;
    }

    /**
     * @param  Builder<Pengguna>  $kueri
     */
    public function scopeMurid(Builder $kueri): void
    {
        $kueri->where('peran', self::PERAN_MURID);
    }
}
