<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Table('kategori')]
#[Fillable(['label', 'catatan', 'urutan'])]
class Kategori extends Model
{
    /**
     * @return HasMany<Sajian, $this>
     */
    public function sajian(): HasMany
    {
        return $this->hasMany(Sajian::class)->orderBy('urutan')->orderBy('id');
    }
}
