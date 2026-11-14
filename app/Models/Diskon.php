<?php

namespace App\Models;

use Database\Factories\DiskonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Kode promo yang bisa dipakai murid di keranjang.
 */
#[Table('diskon')]
#[Fillable(['kode', 'label', 'potongan', 'belanja_minimal', 'aktif'])]
class Diskon extends Model
{
    /** @use HasFactory<DiskonFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'potongan' => 'integer',
            'belanja_minimal' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    /**
     * Pesanan yang memakai kode ini.
     *
     * @return HasMany<Pesanan, $this>
     */
    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'kode_promo', 'kode');
    }

    /**
     * Potongan untuk subtotal tertentu; 0 bila belum mencapai belanja minimal.
     */
    public function potonganUntuk(int $subtotal): int
    {
        return $subtotal >= $this->belanja_minimal ? min($this->potongan, $subtotal) : 0;
    }
}
