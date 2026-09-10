<?php

namespace Database\Seeders;

use App\Models\Discount;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class GraboSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAdmins();
        $this->seedStudents();
        $this->seedMenuItems();
        $this->seedDiscounts();
        $this->seedOrders();
    }

    /** Akun yang boleh membuka dasbor. */
    private function seedAdmins(): void
    {
        $admins = [
            ['name' => 'Admin Koperasi', 'email' => 'admin@grabo.sch.id', 'role' => User::PERAN_ADMIN],
            ['name' => 'Bu Rina', 'email' => 'burina@grabo.sch.id', 'role' => User::PERAN_PETUGAS],
            ['name' => 'Pak Joko', 'email' => 'pakjoko@grabo.sch.id', 'role' => User::PERAN_PETUGAS],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'role' => $admin['role'],
                    'is_active' => true,
                    // Kata sandi contoh untuk pengembangan; ganti sebelum dipakai sungguhan.
                    'password' => 'grabo12345',
                ],
            );
        }
    }

    private function seedStudents(): void
    {
        $students = [
            ['nis' => '2024001', 'name' => 'Virgilio Lawrence', 'class' => 'XII TKJ 1', 'balance' => 75000],
            ['nis' => '2024002', 'name' => 'Ryo Marvel', 'class' => 'XII TKJ 1', 'balance' => 42000],
            ['nis' => '2024003', 'name' => 'Vinno Alvian Chow', 'class' => 'XII TKJ 1', 'balance' => 18000],
            ['nis' => '2024004', 'name' => 'Justin Geraldo', 'class' => 'XII TKJ 1', 'balance' => 96000],
            ['nis' => '2024005', 'name' => 'Anisa Rahmawati', 'class' => 'XII AKL 1', 'balance' => 55000],
            ['nis' => '2024006', 'name' => 'Budi Ariyanto', 'class' => 'XII AKL 1', 'balance' => 12000],
            ['nis' => '2024007', 'name' => 'Citra Dewi', 'class' => 'XI TKJ 2', 'balance' => 63000],
            ['nis' => '2024008', 'name' => 'Dimas Prakoso', 'class' => 'XI TKJ 2', 'balance' => 8000],
            ['nis' => '2024009', 'name' => 'Elsa Puspita', 'class' => 'XI AKL 2', 'balance' => 34000],
            ['nis' => '2024010', 'name' => 'Fajar Nugroho', 'class' => 'XI AKL 2', 'balance' => 27000],
            ['nis' => '2024011', 'name' => 'Gita Ramadhani', 'class' => 'X TKJ 1', 'balance' => 50000],
            ['nis' => '2024012', 'name' => 'Hendra Wijaya', 'class' => 'X TKJ 1', 'balance' => 0, 'is_active' => false],
        ];

        foreach ($students as $student) {
            Student::updateOrCreate(
                ['nis' => $student['nis']],
                $student + [
                    'email' => Str::slug($student['name'], '.') . '@grabo.sch.id',
                    'is_active' => $student['is_active'] ?? true,
                ],
            );
        }
    }

    /**
     * Menu diambil dari config/menu.php supaya isi katalog lama tidak hilang
     * saat pindah ke database.
     */
    private function seedMenuItems(): void
    {
        foreach (config('menu.categories', []) as $category) {
            foreach ($category['items'] as $item) {
                MenuItem::updateOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'name' => $item['name'],
                        'stall' => $item['stall'],
                        'category' => $category['label'],
                        'type' => $item['type'] ?? 'makanan',
                        'price' => $item['price'],
                        'badge' => $item['badge'] ?? null,
                        'summary' => $item['summary'] ?? null,
                        'description' => $item['description'] ?? null,
                        'image' => $item['image'] ?? null,
                        'photo' => $item['photo'] ?? true,
                        'is_available' => true,
                        'stock' => random_int(15, 60),
                        'gallery' => $item['gallery'] ?? null,
                        'specs' => $item['specs'] ?? null,
                        'rating' => $item['rating'] ?? null,
                        'reviews' => $item['reviews'] ?? 0,
                        'sold' => $item['sold'] ?? 0,
                        'ready' => $item['ready'] ?? null,
                    ],
                );
            }
        }
    }

    private function seedDiscounts(): void
    {
        $discounts = [
            ['code' => 'HEMAT14', 'label' => 'Paket hemat', 'amount' => 2000, 'min_spend' => 14000],
            ['code' => 'ROTI21', 'label' => 'Beli 2 gratis 1', 'amount' => 9000, 'min_spend' => 18000],
            ['code' => 'SEGAR5', 'label' => 'Promo minuman', 'amount' => 1000, 'min_spend' => 5000],
        ];

        foreach ($discounts as $discount) {
            Discount::updateOrCreate(
                ['code' => $discount['code']],
                $discount + [
                    'is_active' => true,
                    'starts_at' => now()->startOfMonth()->toDateString(),
                    'ends_at' => now()->endOfMonth()->toDateString(),
                ],
            );
        }
    }

    /** Transaksi contoh 14 hari terakhir, supaya laporan ada isinya. */
    private function seedOrders(): void
    {
        if (Order::exists()) {
            return;
        }

        $students = Student::where('is_active', true)->get();
        $menuItems = MenuItem::all();
        $discounts = Discount::all()->keyBy('code');
        $metode = ['tunai', 'saldo', 'qris', 'transfer'];
        $slots = ['sekarang', 'istirahat-1', 'istirahat-2'];
        $statuses = ['selesai', 'selesai', 'selesai', 'disiapkan', 'menunggu', 'batal'];

        for ($i = 0; $i < 60; $i++) {
            $student = $students->random();
            $createdAt = now()->subDays(random_int(0, 13))->setTime(random_int(7, 14), random_int(0, 59));

            $order = Order::create([
                'code' => 'GRB-' . strtoupper(Str::random(6)),
                'student_id' => $student->id,
                'student_name' => $student->name,
                'student_class' => $student->class,
                'pickup_slot' => $slots[array_rand($slots)],
                'payment_method' => $metode[array_rand($metode)],
                'status' => $statuses[array_rand($statuses)],
                'subtotal' => 0,
                'total' => 0,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            $subtotal = 0;

            foreach ($menuItems->random(random_int(1, 3)) as $item) {
                $qty = random_int(1, 2);
                $subtotal += $item->price * $qty;

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item->id,
                    'name' => $item->name,
                    'stall' => $item->stall,
                    'price' => $item->price,
                    'qty' => $qty,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Sebagian pesanan memakai kode promo.
            $discount = random_int(1, 4) === 1 ? $discounts->random() : null;
            $discountAmount = $discount && $subtotal >= $discount->min_spend
                ? min($discount->amount, $subtotal)
                : 0;

            $order->update([
                'discount_id' => $discountAmount > 0 ? $discount->id : null,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total' => $subtotal - $discountAmount,
            ]);

            if ($discountAmount > 0) {
                $discount->increment('used_count');
            }
        }
    }
}
