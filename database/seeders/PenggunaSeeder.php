<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Seeder;

/**
 * Akun contoh untuk pengembangan. Semua akun memakai sandi "password".
 *
 * - Penjual: penjual@grabo.sch.id
 * - Murid  : alya@grabo.sch.id, bima@grabo.sch.id, dan seterusnya.
 */
class PenggunaSeeder extends Seeder
{
    /**
     * Murid contoh: [nama, NIS, kelas]. Email = nama depan @grabo.sch.id.
     *
     * @var array<int, array{0: string, 1: string, 2: string}>
     */
    public const MURID = [
        ['Alya Putri', '2024001', 'XI RPL 1'],
        ['Bima Saputra', '2025014', 'X TKJ 2'],
        ['Citra Lestari', '2023027', 'XII AKL 1'],
        ['Dimas Pratama', '2024033', 'XI TKJ 1'],
        ['Eka Wulandari', '2025041', 'X AKL 2'],
        ['Fajar Nugroho', '2023052', 'XII RPL 2'],
    ];

    public function run(): void
    {
        Pengguna::query()->updateOrCreate(['email' => 'penjual@grabo.sch.id'], [
            'nama' => 'Pengelola Kantin',
            'kata_sandi' => 'password',
            'peran' => Pengguna::PERAN_PENJUAL,
            'email_verified_at' => now(),
        ]);

        foreach (self::MURID as [$nama, $nis, $kelas]) {
            Pengguna::query()->updateOrCreate(['email' => self::emailMurid($nama)], [
                'nama' => $nama,
                'kata_sandi' => 'password',
                'peran' => Pengguna::PERAN_MURID,
                'nis' => $nis,
                'kelas' => $kelas,
                'email_verified_at' => now(),
            ]);
        }
    }

    public static function emailMurid(string $nama): string
    {
        return strtolower(strtok($nama, ' ')).'@grabo.sch.id';
    }
}
