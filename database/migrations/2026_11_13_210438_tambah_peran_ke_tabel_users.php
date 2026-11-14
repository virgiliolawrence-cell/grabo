<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Peran akun: 'penjual' membuka dasbor, 'murid' memesan dari halaman siswa.
 * NIS dan kelas hanya diisi untuk murid.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('peran', 20)->default('murid')->after('password')->index();
            $table->string('nis', 20)->nullable()->unique()->after('peran');
            $table->string('kelas', 20)->nullable()->after('nis');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['nis']);
            $table->dropIndex(['peran']);
            $table->dropColumn(['peran', 'nis', 'kelas']);
        });
    }
};
