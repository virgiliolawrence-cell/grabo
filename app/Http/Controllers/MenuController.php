<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class MenuController extends Controller
{
    /**
     * Katalog menu kantin.
     *
     * Untuk sekarang datanya masih di sini supaya satu sumber dipakai bersama
     * halaman menu dan beranda. Pindahkan ke model Eloquent begitu tabelnya ada.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function catalog(): array
    {
        return [
            [
                'name' => 'Nasi Goreng Kampung',
                'stall' => 'Stan Bu Rina',
                'price' => 12000,
                'desc' => 'Nasi goreng kampung dengan kerupuk, sambal, dan lalapan segar.',
                'image' => 'images/food/photos/nasi-goreng.jpg',
                'photo' => true,
                'badge' => 'Best Seller',
                'category' => 'Makanan Berat',
                'popular' => true,
            ],
            [
                'name' => 'Mie Ayam Jamur',
                'stall' => 'Stan Pak Joko',
                'price' => 10000,
                'desc' => 'Mie ayam dengan tumisan jamur, ayam cincang, dan sawi hijau.',
                'image' => 'images/food/photos/mie-ayam.jpg',
                'photo' => true,
                'badge' => null,
                'category' => 'Makanan Berat',
                'popular' => true,
            ],
            [
                'name' => 'Ayam Geprek',
                'stall' => 'Stan Dapur Mama',
                'price' => 13000,
                'desc' => 'Ayam crispy diulek bersama sambal bawang, disajikan dengan nasi hangat.',
                'image' => 'images/food/photos/ayam-geprek.jpg',
                'photo' => true,
                'badge' => 'Pedas',
                'category' => 'Makanan Berat',
                'popular' => true,
            ],
            [
                'name' => 'Mie Goreng Jawa',
                'stall' => 'Stan Pak Joko',
                'price' => 11000,
                'desc' => 'Mie goreng jawa yang digoreng dadakan begitu pesanan masuk.',
                'image' => 'images/food/photos/promo-mie-goreng.jpg',
                'photo' => true,
                'badge' => null,
                'category' => 'Makanan Berat',
                'popular' => false,
            ],
            [
                'name' => 'Batagor Saus Kacang',
                'stall' => 'Stan Kang Asep',
                'price' => 9000,
                'desc' => 'Batagor goreng renyah dengan saus kacang dan perasan jeruk limau.',
                'image' => 'images/food/photos/batagor.jpg',
                'photo' => true,
                'badge' => null,
                'category' => 'Gorengan & Snack',
                'popular' => true,
            ],
            [
                'name' => 'Roti Bakar Mentega',
                'stall' => 'Stan Snack Corner',
                'price' => 8000,
                'desc' => 'Roti panggang mentega, renyah di luar dan lembut di dalamnya.',
                'image' => 'images/food/photos/roti-bakar.jpg',
                'photo' => true,
                'badge' => 'Menu Baru',
                'category' => 'Gorengan & Snack',
                'popular' => true,
            ],
            [
                'name' => 'Batagor Kuah Pedas',
                'stall' => 'Stan Kang Asep',
                'price' => 10000,
                'desc' => 'Batagor dengan kuah kaldu pedas, cocok saat cuaca dingin.',
                'image' => 'images/food/batagor.svg',
                'photo' => false,
                'badge' => null,
                'category' => 'Gorengan & Snack',
                'popular' => false,
            ],
            [
                'name' => 'Roti Bakar Coklat',
                'stall' => 'Stan Snack Corner',
                'price' => 9000,
                'desc' => 'Roti panggang isi coklat, pas untuk istirahat kedua.',
                'image' => 'images/food/photos/promo-roti-coklat.jpg',
                'photo' => true,
                'badge' => null,
                'category' => 'Gorengan & Snack',
                'popular' => false,
            ],
            [
                'name' => 'Es Teh Manis',
                'stall' => 'Stan Minuman',
                'price' => 4000,
                'desc' => 'Teh seduh dingin dengan es batu, menyegarkan setelah jam pelajaran.',
                'image' => 'images/food/photos/es-teh.jpg',
                'photo' => true,
                'badge' => 'Best Seller',
                'category' => 'Minuman',
                'popular' => true,
            ],
            [
                'name' => 'Es Teh Tawar',
                'stall' => 'Stan Minuman',
                'price' => 3000,
                'desc' => 'Teh dingin tanpa gula untuk yang sedang mengurangi manis.',
                'image' => 'images/food/es-teh.svg',
                'photo' => false,
                'badge' => null,
                'category' => 'Minuman',
                'popular' => false,
            ],
            [
                'name' => 'Es Cendol',
                'stall' => 'Stan Minuman',
                'price' => 6000,
                'desc' => 'Cendol dengan santan dan gula merah, penyegar setelah olahraga.',
                'image' => 'images/food/photos/promo-es-cendol.jpg',
                'photo' => true,
                'badge' => null,
                'category' => 'Minuman',
                'popular' => false,
            ],
            [
                'name' => 'Susu Coklat Dingin',
                'stall' => 'Stan Minuman',
                'price' => 6000,
                'desc' => 'Susu coklat dingin yang mengenyangkan.',
                'image' => null,
                'photo' => false,
                'badge' => null,
                'category' => 'Minuman',
                'popular' => false,
            ],
        ];
    }

    /**
     * Jenis varian yang ditawarkan panel detail produk.
     */
    public static function typeFor(string $category): string
    {
        return match (true) {
            str_contains($category, 'Minuman') => 'minuman',
            str_contains($category, 'Snack') => 'snack',
            default => 'makanan',
        };
    }

    /**
     * Tambahkan label harga dan jenis varian ke setiap item.
     *
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    public static function decorate(array $items): array
    {
        return array_map(static function (array $item): array {
            $item['price_label'] = 'Rp ' . number_format($item['price'], 0, ',', '.');
            $item['type'] = self::typeFor($item['category']);

            return $item;
        }, $items);
    }

    /**
     * Halaman menu: carousel promo + menu yang dikelompokkan per kategori.
     */
    public function index(): View
    {
        $promos = [
            [
                'eyebrow' => 'Dimasak Dadakan',
                'title' => 'Mie Goreng Jawa',
                'text' => 'Digoreng begitu pesananmu masuk, jadi masih panas saat kamu ambil di loket.',
                'price' => 'Rp 11.000',
                'image' => 'images/food/photos/promo-mie-goreng.jpg',
                'alt' => 'Mie goreng jawa sedang dimasak di atas wajan besar',
            ],
            [
                'eyebrow' => 'Menu Baru',
                'title' => 'Roti Bakar Coklat',
                'text' => 'Roti panggang isi coklat dari Stan Snack Corner, pas untuk istirahat kedua.',
                'price' => 'Rp 9.000',
                'image' => 'images/food/photos/promo-roti-coklat.jpg',
                'alt' => 'Roti bakar isi coklat yang sudah dipanggang',
            ],
            [
                'eyebrow' => 'Paling Segar',
                'title' => 'Es Cendol',
                'text' => 'Cendol dengan santan dan gula merah, penyegar setelah jam olahraga.',
                'price' => 'Rp 6.000',
                'image' => 'images/food/photos/promo-es-cendol.jpg',
                'alt' => 'Semangkuk es cendol dengan serutan es',
            ],
        ];

        $catatanKategori = [
            'Makanan Berat' => 'Porsi mengenyangkan untuk jam istirahat pertama.',
            'Gorengan & Snack' => 'Teman ngobrol saat istirahat kedua.',
            'Minuman' => 'Penyegar setelah jam pelajaran.',
        ];

        $categories = [];

        foreach (self::decorate(self::catalog()) as $item) {
            $categories[$item['category']]['label'] = $item['category'];
            $categories[$item['category']]['note'] = $catatanKategori[$item['category']] ?? '';
            $categories[$item['category']]['items'][] = $item;
        }

        return view('menu', [
            'promos' => $promos,
            'categories' => array_values($categories),
        ]);
    }
}
