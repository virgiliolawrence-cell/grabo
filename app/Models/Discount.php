<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'label', 'amount', 'min_spend', 'is_active', 'starts_at', 'ends_at', 'used_count'];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'min_spend' => 'integer',
            'is_active' => 'boolean',
            'starts_at' => 'date',
            'ends_at' => 'date',
        ];
    }

    /** Boleh dipakai untuk belanja sebesar $subtotal hari ini. */
    public function appliesTo(int $subtotal): bool
    {
        return $subtotal >= $this->min_spend && $this->isUsableOn(now());
    }

    /** Aktif dan masih dalam rentang tanggalnya. */
    public function isUsableOn(\DateTimeInterface $date): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->starts_at && $date < $this->starts_at) {
            return false;
        }

        return ! ($this->ends_at && $date > $this->ends_at);
    }
}
