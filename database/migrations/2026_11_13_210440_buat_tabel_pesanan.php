<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Pesanan murid dan isinya.
 *
 * Nama, harga, dan pilihan sajian disalin ke item_pesanan supaya riwayat
 * tidak berubah kalau menu diubah atau dihapus belakangan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 12)->unique();
            $table->foreignId('pengguna_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama', 60);
            $table->string('kelas', 20);
            $table->string('waktu_ambil', 20);
            $table->string('metode', 20);
            $table->string('bank', 30)->nullable();
            $table->string('dompet', 30)->nullable();
            $table->string('catatan', 200)->nullable();
            $table->string('kode_promo', 12)->nullable();
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('potongan')->default(0);
            $table->unsignedInteger('total');
            $table->string('status', 20)->default('menunggu')->index();
            $table->timestamps();
        });

        Schema::create('item_pesanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesanan_id')->constrained('pesanan')->cascadeOnDelete();
            $table->foreignId('sajian_id')->nullable()->constrained('sajian')->nullOnDelete();
            $table->string('nama', 60);
            $table->string('stan', 40);
            $table->unsignedInteger('harga');
            $table->unsignedSmallInteger('jumlah');
            $table->string('pilihan')->nullable();
            $table->string('catatan', 200)->nullable();
            $table->boolean('sudah_disiapkan')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_pesanan');
        Schema::dropIfExists('pesanan');
    }
};
