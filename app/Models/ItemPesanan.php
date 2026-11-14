<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Satu baris di dalam pesanan. 'sudah_disiapkan' adalah centang dari penjual.
 */
#[Table('item_pesanan')]
#[Fillable(['pesanan_id', 'sajian_id', 'nama', 'stan', 'harga', 'jumlah', 'pilihan', 'catatan', 'sudah_disiapkan'])]
class ItemPesanan extends Model
{
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'jumlah' => 'integer',
            'sudah_disiapkan' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Pesanan, $this>
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * @return BelongsTo<Sajian, $this>
     */
    public function sajian(): BelongsTo
    {
        return $this->belongsTo(Sajian::class);
    }
}
