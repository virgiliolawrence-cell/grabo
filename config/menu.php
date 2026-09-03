<?php

/*
 * Katalog kantin.
 *
 * Dipakai bersama oleh halaman menu dan halaman detail produk, jadi
 * keduanya tidak bisa berbeda isi. Selama belum ada tabel menu di
 * database, daftar ini yang menjadi sumbernya.
 *
 * - 'type' menentukan varian yang ditawarkan di halaman detail
 *   (lihat OPTION_GROUPS di partials/scripts.blade.php).
 * - 'gallery' hanya berisi gambar yang benar-benar ada. Menu yang cuma
 *   punya satu gambar tidak menampilkan deretan thumbnail.
 */

return [

    'categories' => [

        [
            'label' => 'Makanan Berat',
            'note' => 'Porsi mengenyangkan untuk jam istirahat pertama.',
            'items' => [

                [
                    'slug' => 'nasi-goreng-kampung',
                    'name' => 'Nasi Goreng Kampung',
                    'stall' => 'Stan Bu Rina',
                    'price' => 12000,
                    'type' => 'makanan',
                    'badge' => 'Best Seller',
                    'rating' => 4.8,
                    'reviews' => 412,
                    'sold' => 1860,
                    'ready' => '±7 menit',
                    'summary' => 'Nasi goreng kampung dengan kerupuk, sambal, dan lalapan segar.',
                    'description' => 'Nasi digoreng di wajan besar dengan bawang merah, terasi, dan kecap sampai keluar aroma sangit tipis yang khas. Disajikan bersama telur mata sapi, irisan timun, dan kerupuk yang dibungkus terpisah supaya tetap renyah sampai kelas.',
                    'image' => 'images/food/photos/nasi-goreng.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/nasi-goreng.jpg', 'photo' => true, 'label' => 'Porsi lengkap'],
                        ['src' => 'images/food/nasi-goreng.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => '1 piring + telur + kerupuk',
                        'Tingkat pedas' => 'Bisa dipilih saat memesan',
                        'Waktu siap' => 'Sekitar 7 menit',
                        'Kemasan' => 'Kotak kertas tahan minyak',
                        'Mengandung' => 'Telur, terasi, gluten',
                    ],
                ],

                [
                    'slug' => 'mie-ayam-jamur',
                    'name' => 'Mie Ayam Jamur',
                    'stall' => 'Stan Pak Joko',
                    'price' => 10000,
                    'type' => 'makanan',
                    'badge' => null,
                    'rating' => 4.7,
                    'reviews' => 268,
                    'sold' => 1240,
                    'ready' => '±5 menit',
                    'summary' => 'Mie ayam dengan tumisan jamur, ayam cincang, dan sawi hijau.',
                    'description' => 'Mie pangsit direbus dadakan lalu diaduk dengan minyak bawang buatan sendiri. Topping ayam cincang dan jamur ditumis manis gurih, ditemani sawi hijau dan kuah kaldu bening yang dibungkus terpisah.',
                    'image' => 'images/food/photos/mie-ayam.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/mie-ayam.jpg', 'photo' => true, 'label' => 'Semangkuk mie ayam'],
                        ['src' => 'images/food/mie-ayam.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => '1 mangkuk + kuah terpisah',
                        'Tingkat pedas' => 'Sambal dibungkus terpisah',
                        'Waktu siap' => 'Sekitar 5 menit',
                        'Kemasan' => 'Mangkuk kertas bertutup',
                        'Mengandung' => 'Gluten, kedelai',
                    ],
                ],

                [
                    'slug' => 'ayam-geprek',
                    'name' => 'Ayam Geprek',
                    'stall' => 'Stan Dapur Mama',
                    'price' => 13000,
                    'type' => 'makanan',
                    'badge' => 'Pedas',
                    'rating' => 4.9,
                    'reviews' => 531,
                    'sold' => 2105,
                    'ready' => '±8 menit',
                    'summary' => 'Ayam crispy diulek bersama sambal bawang, disajikan dengan nasi hangat.',
                    'description' => 'Ayam digoreng dengan tepung berbumbu sampai renyah, lalu diulek di cobek bersama sambal bawang yang baru dibuat pagi itu. Level pedasnya bisa diatur, dari yang aman untuk lidah pemula sampai yang bikin ngos-ngosan.',
                    'image' => 'images/food/photos/ayam-geprek.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/ayam-geprek.jpg', 'photo' => true, 'label' => 'Ayam geprek sambal bawang'],
                        ['src' => 'images/food/ayam-geprek.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => '1 potong ayam + nasi',
                        'Tingkat pedas' => 'Tiga level, dipilih saat memesan',
                        'Waktu siap' => 'Sekitar 8 menit',
                        'Kemasan' => 'Kotak kertas tahan minyak',
                        'Mengandung' => 'Gluten, bawang',
                    ],
                ],

                [
                    'slug' => 'mie-goreng-jawa',
                    'name' => 'Mie Goreng Jawa',
                    'stall' => 'Stan Pak Joko',
                    'price' => 11000,
                    'type' => 'makanan',
                    'badge' => null,
                    'rating' => 4.6,
                    'reviews' => 187,
                    'sold' => 940,
                    'ready' => '±6 menit',
                    'summary' => 'Mie goreng jawa yang digoreng dadakan begitu pesanan masuk.',
                    'description' => 'Mie basah digoreng di wajan besar bersama telur, kol, dan sawi dengan kecap manis yang sedikit dikaramelkan. Baru dimasak setelah pesananmu diterima, jadi masih panas saat diambil di loket.',
                    'image' => 'images/food/photos/promo-mie-goreng.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/promo-mie-goreng.jpg', 'photo' => true, 'label' => 'Dimasak dadakan'],
                    ],
                    'specs' => [
                        'Porsi' => '1 piring',
                        'Tingkat pedas' => 'Bisa dipilih saat memesan',
                        'Waktu siap' => 'Sekitar 6 menit',
                        'Kemasan' => 'Kotak kertas tahan minyak',
                        'Mengandung' => 'Telur, gluten, kedelai',
                    ],
                ],
            ],
        ],

        [
            'label' => 'Gorengan & Snack',
            'note' => 'Teman ngobrol saat istirahat kedua.',
            'items' => [

                [
                    'slug' => 'batagor-saus-kacang',
                    'name' => 'Batagor Saus Kacang',
                    'stall' => 'Stan Kang Asep',
                    'price' => 9000,
                    'type' => 'snack',
                    'badge' => null,
                    'rating' => 4.7,
                    'reviews' => 224,
                    'sold' => 1130,
                    'ready' => '±4 menit',
                    'summary' => 'Batagor goreng renyah dengan saus kacang dan perasan jeruk limau.',
                    'description' => 'Batagor digoreng ulang sebentar supaya kulitnya kembali renyah, lalu disiram saus kacang kental yang diulek sendiri. Jeruk limau dan kecap diberikan terpisah supaya kamu bisa mengatur asam manisnya.',
                    'image' => 'images/food/photos/batagor.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/batagor.jpg', 'photo' => true, 'label' => 'Sepiring batagor'],
                        ['src' => 'images/food/batagor.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => '5 potong + saus',
                        'Tingkat pedas' => 'Sambal terpisah',
                        'Waktu siap' => 'Sekitar 4 menit',
                        'Kemasan' => 'Kotak kertas + cup saus',
                        'Mengandung' => 'Ikan, kacang tanah, gluten',
                    ],
                ],

                [
                    'slug' => 'roti-bakar-mentega',
                    'name' => 'Roti Bakar Mentega',
                    'stall' => 'Stan Snack Corner',
                    'price' => 8000,
                    'type' => 'snack',
                    'badge' => 'Menu Baru',
                    'rating' => 4.5,
                    'reviews' => 96,
                    'sold' => 380,
                    'ready' => '±5 menit',
                    'summary' => 'Roti panggang mentega, renyah di luar dan lembut di dalamnya.',
                    'description' => 'Roti tawar tebal dipanggang di atas teflon dengan mentega sampai pinggirnya keemasan. Bagian dalamnya tetap lembut, dan taburan gula halus bisa diminta lebih sedikit lewat catatan pesanan.',
                    'image' => 'images/food/photos/roti-bakar.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/roti-bakar.jpg', 'photo' => true, 'label' => 'Roti bakar mentega'],
                        ['src' => 'images/food/roti-bakar.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => '2 potong',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 5 menit',
                        'Kemasan' => 'Kertas roti + kantong',
                        'Mengandung' => 'Susu, gluten',
                    ],
                ],

                [
                    'slug' => 'batagor-kuah-pedas',
                    'name' => 'Batagor Kuah Pedas',
                    'stall' => 'Stan Kang Asep',
                    'price' => 10000,
                    'type' => 'snack',
                    'badge' => null,
                    'rating' => 4.6,
                    'reviews' => 118,
                    'sold' => 520,
                    'ready' => '±5 menit',
                    'summary' => 'Batagor dengan kuah pedas gurih untuk yang tidak suka saus kacang.',
                    'description' => 'Versi berkuah dari batagor Kang Asep: kaldu bening dengan cabai rawit ulek dan bawang goreng. Kuahnya dibungkus terpisah supaya batagornya tidak lembek di perjalanan menuju kelas.',
                    'image' => 'images/food/batagor.svg',
                    'photo' => false,
                    'gallery' => [
                        ['src' => 'images/food/batagor.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => '5 potong + kuah',
                        'Tingkat pedas' => 'Pedas, cabai bisa dikurangi',
                        'Waktu siap' => 'Sekitar 5 menit',
                        'Kemasan' => 'Mangkuk kertas + cup kuah',
                        'Mengandung' => 'Ikan, gluten',
                    ],
                ],

                [
                    'slug' => 'roti-bakar-coklat',
                    'name' => 'Roti Bakar Coklat',
                    'stall' => 'Stan Snack Corner',
                    'price' => 9000,
                    'type' => 'snack',
                    'badge' => null,
                    'rating' => 4.8,
                    'reviews' => 143,
                    'sold' => 610,
                    'ready' => '±5 menit',
                    'summary' => 'Roti panggang isi coklat, pas untuk istirahat kedua.',
                    'description' => 'Roti dipanggang dengan mentega lalu diisi meses coklat yang dibiarkan meleleh sebentar sebelum dipotong. Manisnya sedang, jadi tidak bikin eneg walau dimakan sambil belajar.',
                    'image' => 'images/food/photos/promo-roti-coklat.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/promo-roti-coklat.jpg', 'photo' => true, 'label' => 'Isi coklat meleleh'],
                        ['src' => 'images/food/roti-bakar.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => '2 potong',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 5 menit',
                        'Kemasan' => 'Kertas roti + kantong',
                        'Mengandung' => 'Susu, coklat, gluten',
                    ],
                ],
            ],
        ],

        [
            'label' => 'Minuman',
            'note' => 'Penyegar setelah jam pelajaran.',
            'items' => [

                [
                    'slug' => 'es-teh-manis',
                    'name' => 'Es Teh Manis',
                    'stall' => 'Stan Minuman',
                    'price' => 4000,
                    'type' => 'minuman',
                    'badge' => 'Best Seller',
                    'rating' => 4.9,
                    'reviews' => 688,
                    'sold' => 3240,
                    'ready' => '±2 menit',
                    'summary' => 'Teh seduh dingin dengan es batu, menyegarkan setelah jam pelajaran.',
                    'description' => 'Teh tubruk diseduh pekat pagi hari lalu didinginkan, bukan teh instan. Kadar gula dan jumlah es bisa diatur saat memesan, jadi bisa disesuaikan dengan seleramu.',
                    'image' => 'images/food/photos/es-teh.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/es-teh.jpg', 'photo' => true, 'label' => 'Segelas es teh'],
                        ['src' => 'images/food/es-teh.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => 'Gelas 400 ml',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 2 menit',
                        'Kemasan' => 'Gelas plastik + tutup segel',
                        'Mengandung' => 'Kafeina',
                    ],
                ],

                [
                    'slug' => 'es-teh-tawar',
                    'name' => 'Es Teh Tawar',
                    'stall' => 'Stan Minuman',
                    'price' => 3000,
                    'type' => 'minuman',
                    'badge' => null,
                    'rating' => 4.5,
                    'reviews' => 74,
                    'sold' => 410,
                    'ready' => '±2 menit',
                    'summary' => 'Teh dingin tanpa gula untuk yang sedang mengurangi manis.',
                    'description' => 'Teh yang sama dengan es teh manis, hanya tanpa gula sama sekali. Cocok diminum bersama makanan berat karena tidak menutupi rasa makanannya.',
                    'image' => 'images/food/es-teh.svg',
                    'photo' => false,
                    'gallery' => [
                        ['src' => 'images/food/es-teh.svg', 'photo' => false, 'label' => 'Ilustrasi menu'],
                    ],
                    'specs' => [
                        'Porsi' => 'Gelas 400 ml',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 2 menit',
                        'Kemasan' => 'Gelas plastik + tutup segel',
                        'Mengandung' => 'Kafeina',
                    ],
                ],

                [
                    'slug' => 'es-cendol',
                    'name' => 'Es Cendol',
                    'stall' => 'Stan Minuman',
                    'price' => 6000,
                    'type' => 'minuman',
                    'badge' => null,
                    'rating' => 4.7,
                    'reviews' => 205,
                    'sold' => 870,
                    'ready' => '±3 menit',
                    'summary' => 'Cendol dengan santan dan gula merah, penyegar setelah jam olahraga.',
                    'description' => 'Cendol hijau dari tepung beras disiram santan encer dan gula merah cair yang dimasak sendiri. Es serutnya ditambahkan terakhir supaya santannya tidak langsung encer.',
                    'image' => 'images/food/photos/promo-es-cendol.jpg',
                    'photo' => true,
                    'gallery' => [
                        ['src' => 'images/food/photos/promo-es-cendol.jpg', 'photo' => true, 'label' => 'Semangkuk es cendol'],
                    ],
                    'specs' => [
                        'Porsi' => 'Gelas 400 ml',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 3 menit',
                        'Kemasan' => 'Gelas plastik + tutup segel',
                        'Mengandung' => 'Santan, gluten',
                    ],
                ],

                [
                    'slug' => 'susu-coklat-dingin',
                    'name' => 'Susu Coklat Dingin',
                    'stall' => 'Stan Minuman',
                    'price' => 6000,
                    'type' => 'minuman',
                    'badge' => null,
                    'rating' => 4.6,
                    'reviews' => 61,
                    'sold' => 290,
                    'ready' => '±2 menit',
                    'summary' => 'Susu coklat dingin yang dikocok dadakan, tidak terlalu manis.',
                    'description' => 'Bubuk coklat dilarutkan dengan air panas sedikit dulu supaya tidak menggumpal, baru dicampur susu dingin dan dikocok. Manisnya bisa dikurangi lewat pilihan gula.',
                    'image' => null,
                    'photo' => false,
                    'gallery' => [],
                    'specs' => [
                        'Porsi' => 'Gelas 400 ml',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 2 menit',
                        'Kemasan' => 'Gelas plastik + tutup segel',
                        'Mengandung' => 'Susu, coklat',
                    ],
                ],
            ],
        ],
    ],

];
