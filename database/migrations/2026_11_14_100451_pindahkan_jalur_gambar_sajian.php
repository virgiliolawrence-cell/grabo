<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Folder gambar dipindah dari public/images/food/photos ke public/gambar/sajian.
 * Jalur yang sudah tersimpan di sajian.gambar dan sajian.galeri ikut diganti.
 */
return new class extends Migration
{
    private const JALUR_LAMA = 'images/food/photos/';

    private const JALUR_BARU = 'gambar/sajian/';

    public function up(): void
    {
        $this->gantiJalur(self::JALUR_LAMA, self::JALUR_BARU);
    }

    public function down(): void
    {
        $this->gantiJalur(self::JALUR_BARU, self::JALUR_LAMA);
    }

    private function gantiJalur(string $dari, string $ke): void
    {
        DB::table('sajian')->orderBy('id')->each(function (object $sajian) use ($dari, $ke) {
            DB::table('sajian')->where('id', $sajian->id)->update([
                'gambar' => $sajian->gambar === null ? null : str_replace($dari, $ke, $sajian->gambar),
                // Galeri berupa JSON; garis miringnya bisa tersimpan sebagai "\/".
                'galeri' => $sajian->galeri === null ? null : str_replace(
                    [$dari, str_replace('/', '\/', $dari)],
                    [$ke, str_replace('/', '\/', $ke)],
                    $sajian->galeri,
                ),
            ]);
        });
    }
};
