<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * Identitas kantin. Hanya ada satu baris.
 */
#[Table('profil_kantin')]
#[Fillable(['nama', 'pengelola', 'email', 'telepon', 'lokasi', 'jam_buka', 'jam_tutup', 'deskripsi'])]
class ProfilKantin extends Model
{
    /**
     * Ambil profil kantin; dibuat dari nilai awal bila tabelnya masih kosong.
     */
    public static function milikKantin(): self
    {
        return self::query()->firstOrCreate([], [
            'nama' => 'Kantin Sekolah Grabo',
            'pengelola' => 'Koperasi Siswa',
            'email' => config('grabo.kontak.email'),
            'telepon' => config('grabo.kontak.telepon'),
            'lokasi' => 'Gedung B, lantai dasar',
            'jam_buka' => '07.00',
            'jam_tutup' => '14.30',
            'deskripsi' => 'Kantin sekolah yang menyiapkan makanan berat, camilan, dan minuman untuk siswa dan guru.',
        ]);
    }
}
