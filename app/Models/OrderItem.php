<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'menu_item_id', 'name', 'stall', 'price', 'qty', 'options', 'note'];

    protected function casts(): array
    {
        return ['price' => 'integer', 'qty' => 'integer'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function subtotal(): int
    {
        return $this->price * $this->qty;
    }
}
