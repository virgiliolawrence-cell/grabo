<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;

class MenuController extends Controller
{
    /**
     * Seluruh kategori menu.
     *
     * Sumbernya config/menu.php supaya halaman menu, halaman detail, dan
     * beranda memakai daftar yang sama. Ganti dengan query model begitu
     * tabel menu tersedia.
     */
    public static function categories(): Collection
    {
        return collect(config('menu.categories'));
    }

    /**
     * Semua item menu tanpa pengelompokan kategori.
     */
    public static function items(): Collection
    {
        return self::categories()->flatMap(fn (array $category) => $category['items']);
    }

    /**
     * Halaman menu: carousel promo + menu per kategori.
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

        return view('menu', [
            'promos' => $promos,
            'categories' => self::categories()->all(),
        ]);
    }

    /**
     * Halaman deskripsi satu menu.
     */
    public function show(string $slug): View
    {
        $category = self::categories()->first(
            fn (array $category) => collect($category['items'])->contains('slug', $slug)
        );

        abort_if($category === null, 404);

        return view('menu-detail', [
            'item' => collect($category['items'])->firstWhere('slug', $slug),
            'category' => $category,
            // Menu lain dari kategori yang sama, sebagai saran.
            'related' => collect($category['items'])
                ->reject(fn (array $other) => $other['slug'] === $slug)
                ->take(3)
                ->values()
                ->all(),
        ]);
    }
}
