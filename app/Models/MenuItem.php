<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug', 'name', 'stall', 'category', 'type', 'price', 'badge',
        'summary', 'description', 'image', 'photo', 'is_available',
        'stock', 'gallery', 'specs', 'rating', 'reviews', 'sold', 'ready',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'photo' => 'boolean',
            'is_available' => 'boolean',
            'gallery' => 'array',
            'specs' => 'array',
        ];
    }

    /** Hanya menu yang boleh tampil di halaman siswa. */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true);
    }

    public function getPriceLabelAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}
