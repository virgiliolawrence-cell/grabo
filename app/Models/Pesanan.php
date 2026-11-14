<?php

namespace App\Models;

use Database\Factories\PesananFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Pesanan dari seorang murid.
 *
 * Alur status: menunggu → diproses → siap → selesai, atau batal.
 */
#[Table('pesanan')]
#[Fillable([
    'kode', 'pengguna_id', 'nama', 'kelas', 'waktu_ambil', 'metode', 'bank', 'dompet', 'catatan',
    'kode_promo', 'subtotal', 'potongan', 'total', 'status',
])]
class Pesanan extends Model
{
    /** @use HasFactory<PesananFactory> */
    use HasFactory;

    /**
     * Label dan warna lencana tiap status.
     *
     * @var array<string, array{label: string, warna: string}>
     */
    public const STATUS = [
        'menunggu' => ['label' => 'Menunggu', 'warna' => 'kuning'],
        'diproses' => ['label' => 'Diproses', 'warna' => 'biru'],
        'siap' => ['label' => 'Siap diambil', 'warna' => 'jingga'],
        'selesai' => ['label' => 'Selesai', 'warna' => 'hijau'],
        'batal' => ['label' => 'Batal', 'warna' => 'merah'],
    ];

    /**
     * @var array<string, string>
     */
    public const METODE = [
        'tunai' => 'Tunai di loket',
        'saldo' => 'Saldo kartu pelajar',
        'qris' => 'QRIS',
        'transfer' => 'Transfer bank',
        'dompet' => 'Dompet digital',
    ];

    /**
     * @var array<string, string>
     */
    public const WAKTU_AMBIL = [
        'sekarang' => 'Secepatnya',
        'istirahat-1' => 'Istirahat 1 · 09.30',
        'istirahat-2' => 'Istirahat 2 · 12.00',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'subtotal' => 'integer',
            'potongan' => 'integer',
            'total' => 'integer',
        ];
    }

    /**
     * Kode pesanan dipakai di URL dasbor.
     */
    public function getRouteKeyName(): string
    {
        return 'kode';
    }

    /**
     * @return BelongsTo<Pengguna, $this>
     */
    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    /**
     * @return HasMany<ItemPesanan, $this>
     */
    public function item(): HasMany
    {
        return $this->hasMany(ItemPesanan::class);
    }

    public static function buatKode(): string
    {
        do {
            $kode = 'GRB-'.strtoupper(Str::random(6));
        } while (self::query()->where('kode', $kode)->exists());

        return $kode;
    }

    public function labelStatus(): string
    {
        return self::STATUS[$this->status]['label'];
    }

    public function warnaStatus(): string
    {
        return self::STATUS[$this->status]['warna'];
    }

    public function labelMetode(): string
    {
        return trim(self::METODE[$this->metode].' '.($this->bank ?? $this->dompet ?? ''));
    }

    /**
     * Pesanan yang masih harus dikerjakan stan.
     */
    public function masihBerjalan(): bool
    {
        return in_array($this->status, ['menunggu', 'diproses', 'siap'], true);
    }
}
