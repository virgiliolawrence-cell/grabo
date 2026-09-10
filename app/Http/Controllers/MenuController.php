<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class MenuController extends Controller
{
    /**
     * Seluruh kategori menu beserta isinya.
     *
     * Isinya diambil dari tabel menu_items supaya perubahan di dasbor
     * admin langsung terlihat siswa. Urutan dan kalimat pengantar tiap
     * kategori masih dari config/menu.php karena tidak diatur per menu.
     */
    public static function categories(): Collection
    {
        $urutan = collect(config('menu.categories'))->pluck('note', 'label');

        return MenuItem::available()
            ->orderBy('name')
            ->get()
            ->groupBy('category')
            ->map(fn (Collection $items, string $label) => [
                'label' => $label,
                'note' => $urutan[$label] ?? '',
                'items' => $items->map(fn (MenuItem $item) => self::asArray($item))->all(),
            ])
            // Kategori yang tidak dikenal config diletakkan paling belakang.
            ->sortBy(function (array $kategori) use ($urutan) {
                $posisi = $urutan->keys()->search($kategori['label']);

                return $posisi === false ? 99 : $posisi;
            })
            ->values();
    }

    /**
     * Bentuk model menjadi array, karena view sudah memakai $item['...'].
     *
     * @return array<string, mixed>
     */
    private static function asArray(MenuItem $item): array
    {
        return [
            'slug' => $item->slug,
            'name' => $item->name,
            'stall' => $item->stall,
            'price' => $item->price,
            'type' => $item->type,
            'badge' => $item->badge,
            'rating' => $item->rating,
            'reviews' => $item->reviews,
            'sold' => $item->sold,
            'ready' => $item->ready,
            'stock' => $item->stock,
            'summary' => $item->summary,
            'description' => $item->description,
            'image' => $item->image,
            'photo' => $item->photo,
            'gallery' => $item->gallery ?? [],
            'specs' => $item->specs ?? [],
        ];
    }

    /**
     * Semua item menu tanpa pengelompokan kategori.
     */
    public static function items(): Collection
    {
        return self::categories()->flatMap(fn (array $category) => $category['items']);
    }

    /**
     * Nama stan yang sedang berjualan, untuk daftar pilihan di bilah atas.
     *
     * @return Collection<int, string>
     */
    public static function stalls(): Collection
    {
        return MenuItem::available()->distinct()->orderBy('stall')->pluck('stall');
    }

    /**
     * Halaman menu: carousel promo + menu per kategori.
     */
    public function index(Request $request): View
    {
        // 'slug' menentukan tujuan tombol "Lihat Menu" di tiap slide.
        $promos = [
            [
                'slug' => 'mie-goreng-jawa',
                'eyebrow' => 'Dimasak Dadakan',
                'title' => 'Mie Goreng Jawa',
                'text' => 'Digoreng begitu pesananmu masuk, jadi masih panas saat kamu ambil di loket.',
                'price' => 'Rp 11.000',
                'image' => 'images/food/photos/promo-mie-goreng.jpg',
                'alt' => 'Mie goreng jawa sedang dimasak di atas wajan besar',
            ],
            [
                'slug' => 'roti-bakar-coklat',
                'eyebrow' => 'Menu Baru',
                'title' => 'Roti Bakar Coklat',
                'text' => 'Roti panggang isi coklat dari Stan Camilan, pas untuk istirahat kedua.',
                'price' => 'Rp 9.000',
                'image' => 'images/food/photos/promo-roti-coklat.jpg',
                'alt' => 'Roti bakar isi coklat yang sudah dipanggang',
            ],
            [
                'slug' => 'es-cendol',
                'eyebrow' => 'Paling Segar',
                'title' => 'Es Cendol',
                'text' => 'Cendol dengan santan dan gula merah, penyegar setelah jam olahraga.',
                'price' => 'Rp 6.000',
                'image' => 'images/food/photos/promo-es-cendol.jpg',
                'alt' => 'Semangkuk es cendol dengan serutan es',
            ],
        ];

        // Penyaring stan dari bilah atas: kategori yang jadi kosong tidak ditampilkan.
        $stan = $request->string('stan')->trim()->value();

        $categories = self::categories()
            ->map(function (array $category) use ($stan) {
                if ($stan !== '') {
                    $category['items'] = array_values(array_filter(
                        $category['items'],
                        fn (array $item) => $item['stall'] === $stan
                    ));
                }

                return $category;
            })
            ->reject(fn (array $category) => $category['items'] === [])
            ->values();

        return view('menu', [
            'promos' => $promos,
            'categories' => $categories->all(),
            'stan' => $stan,
            'jumlahMenu' => $categories->sum(fn (array $category) => count($category['items'])),
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
