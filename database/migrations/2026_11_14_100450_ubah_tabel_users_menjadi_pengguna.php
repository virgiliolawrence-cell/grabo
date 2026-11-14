<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Seragamkan penamaan tabel akun ke bahasa Indonesia.
 *
 * users → pengguna, name → nama, password → kata_sandi. Kolom yang dibaca
 * Laravel sendiri (id, email_verified_at, remember_token, created_at,
 * updated_at) tetap memakai nama bawaannya. Kunci asing pesanan.pengguna_id
 * ikut berpindah ke tabel baru saat tabelnya diganti nama.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('users', 'pengguna');

        Schema::table('pengguna', function (Blueprint $table) {
            $table->renameColumn('name', 'nama');
            $table->renameColumn('password', 'kata_sandi');
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->renameColumn('nama', 'name');
            $table->renameColumn('kata_sandi', 'password');
        });

        Schema::rename('pengguna', 'users');
    }
};
