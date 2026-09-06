<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Admin memakai tabel users bawaan Laravel, dibedakan lewat kolom role.
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('admin')->after('email');
            $table->boolean('is_active')->default(true)->after('role');
        });

        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->unique();
            $table->string('name');
            $table->string('class', 20);
            $table->string('email')->nullable();
            // Saldo kartu pelajar disimpan sebagai rupiah bulat, bukan desimal.
            $table->unsignedInteger('balance')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('stall');
            $table->string('category');
            $table->string('type')->default('makanan');   // makanan | snack | minuman
            $table->unsignedInteger('price');
            $table->string('badge')->nullable();
            $table->string('summary')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('photo')->default(true);
            $table->boolean('is_available')->default(true);
            $table->unsignedInteger('stock')->default(0);
            // Galeri dan spesifikasi bentuknya bebas, jadi disimpan sebagai JSON.
            $table->json('gallery')->nullable();
            $table->json('specs')->nullable();
            $table->decimal('rating', 2, 1)->nullable();
            $table->unsignedInteger('reviews')->default(0);
            $table->unsignedInteger('sold')->default(0);
            $table->string('ready')->nullable();
            $table->timestamps();
        });

        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30)->unique();
            $table->string('label');
            $table->unsignedInteger('amount');       // potongan rupiah
            $table->unsignedInteger('min_spend');    // minimal belanja
            $table->boolean('is_active')->default(true);
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->string('student_name');
            $table->string('student_class', 20);
            $table->string('pickup_slot');           // sekarang | istirahat-1 | istirahat-2
            $table->string('payment_method');        // tunai | saldo | qris | transfer | ewallet
            $table->string('payment_detail')->nullable();
            $table->foreignId('discount_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('subtotal')->default(0);
            $table->unsignedInteger('discount_amount')->default(0);
            $table->unsignedInteger('total')->default(0);
            $table->string('status')->default('menunggu');   // menunggu | disiapkan | selesai | batal
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            // Nama dan harga ikut disalin supaya laporan lama tidak berubah
            // ketika menu aslinya diganti harga atau dihapus.
            $table->string('name');
            $table->string('stall');
            $table->unsignedInteger('price');
            $table->unsignedInteger('qty');
            $table->string('options')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('students');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};
