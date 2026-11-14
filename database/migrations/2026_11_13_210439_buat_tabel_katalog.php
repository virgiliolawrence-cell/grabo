<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Katalog kantin (kategori + sajian), kode diskon, dan profil kantin.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('label', 60);
            $table->string('catatan')->nullable();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('sajian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori')->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('nama', 60);
            $table->string('stan', 40)->index();
            $table->unsignedInteger('harga');
            $table->string('jenis', 20)->default('makanan');
            $table->string('sematan', 30)->nullable();
            $table->decimal('nilai', 2, 1)->default(0);
            $table->unsignedInteger('ulasan')->default(0);
            $table->unsignedInteger('terjual')->default(0);
            $table->string('waktu_siap', 20)->nullable();
            $table->string('ringkasan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->boolean('foto')->default(true);
            $table->json('galeri')->nullable();
            $table->json('spesifikasi')->nullable();
            $table->boolean('tersedia')->default(true)->index();
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('diskon', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 12)->unique();
            $table->string('label', 40);
            $table->unsignedInteger('potongan');
            $table->unsignedInteger('belanja_minimal')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::create('profil_kantin', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 60);
            $table->string('pengelola', 60);
            $table->string('email');
            $table->string('telepon', 20);
            $table->string('lokasi', 80);
            $table->string('jam_buka', 5);
            $table->string('jam_tutup', 5);
            $table->string('deskripsi', 300)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profil_kantin');
        Schema::dropIfExists('diskon');
        Schema::dropIfExists('sajian');
        Schema::dropIfExists('kategori');
    }
};
