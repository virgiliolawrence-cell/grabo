<?php

namespace App\Models;

use Database\Factories\SajianFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Satu menu yang dijual di kantin.
 */
#[Table('sajian')]
#[Fillable([
    'kategori_id', 'slug', 'nama', 'stan', 'harga', 'jenis', 'sematan', 'nilai', 'ulasan', 'terjual',
    'waktu_siap', 'ringkasan', 'deskripsi', 'gambar', 'foto', 'galeri', 'spesifikasi', 'tersedia', 'urutan',
])]
class Sajian extends Model
{
    /** @use HasFactory<SajianFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'nilai' => 'float',
            'foto' => 'boolean',
            'tersedia' => 'boolean',
            'galeri' => 'array',
            'spesifikasi' => 'array',
        ];
    }

    /**
     * @return BelongsTo<Kategori, $this>
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Bentuk array yang dipakai view halaman siswa (dulu dari config/menu.php).
     *
     * @return array<string, mixed>
     */
    public function keArrayKatalog(): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'nama' => $this->nama,
            'stan' => $this->stan,
            'harga' => $this->harga,
            'jenis' => $this->jenis,
            'sematan' => $this->sematan,
            'nilai' => $this->nilai,
            'ulasan' => $this->ulasan,
            'terjual' => $this->terjual,
            'waktuSiap' => $this->waktu_siap,
            'ringkasan' => $this->ringkasan,
            'deskripsi' => $this->deskripsi,
            'gambar' => $this->gambar,
            'foto' => $this->foto,
            'galeri' => $this->galeri ?? [],
            'spesifikasi' => $this->spesifikasi ?? [],
        ];
    }
}
