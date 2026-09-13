<?php

/*
 * Katalog kantin.
 *
 * Dipakai bersama oleh halaman menu dan halaman detail produk, jadi
 * keduanya tidak bisa berbeda isi. Selama belum ada tabel menu di
 * database, daftar ini yang menjadi sumbernya.
 *
 * - 'type' menentukan varian yang ditawarkan di halaman detail
 *   (lihat OPTION_GROUPS di bagian/skrip.blade.php).
 * - 'gallery' hanya berisi foto menu. Menu yang cuma punya satu gambar
 *   tidak menampilkan deretan thumbnail di halaman detail.
 */

return [

    'kategori' => [

        [
            'label' => 'Makanan Berat',
            'catatan' => 'Porsi mengenyangkan untuk jam istirahat pertama.',
            'sajian' => [

                [
                    'slug' => 'nasi-goreng-kampung',
                    'nama' => 'Nasi Goreng Kampung',
                    'stan' => 'Stan Bu Rina',
                    'harga' => 12000,
                    'jenis' => 'makanan',
                    'sematan' => 'Paling Laris',
                    'nilai' => 4.8,
                    'ulasan' => 412,
                    'terjual' => 1860,
                    'waktuSiap' => '±7 menit',
                    'ringkasan' => 'Nasi goreng kampung dengan kerupuk, sambal, dan lalapan segar.',
                    'deskripsi' => 'Nasi digoreng di wajan besar dengan bawang merah, terasi, dan kecap sampai keluar aroma sangit tipis yang khas. Disajikan bersama telur mata sapi, irisan timun, dan kerupuk yang dibungkus terpisah supaya tetap renyah sampai kelas.',
                    'gambar' => 'images/food/photos/nasi-goreng-kampung.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/nasi-goreng-kampung.jpg', 'foto' => true, 'keterangan' => 'Porsi lengkap'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => '1 piring + telur + kerupuk',
                        'Tingkat pedas' => 'Bisa dipilih saat memesan',
                        'Waktu siap' => 'Sekitar 7 menit',
                        'Kemasan' => 'Kotak kertas tahan minyak',
                        'Mengandung' => 'Telur, terasi, gluten',
                    ],
                ],

                [
                    'slug' => 'mie-ayam-jamur',
                    'nama' => 'Mie Ayam Jamur',
                    'stan' => 'Stan Pak Joko',
                    'harga' => 10000,
                    'jenis' => 'makanan',
                    'sematan' => null,
                    'nilai' => 4.7,
                    'ulasan' => 268,
                    'terjual' => 1240,
                    'waktuSiap' => '±5 menit',
                    'ringkasan' => 'Mie ayam dengan tumisan jamur, ayam cincang, dan sawi hijau.',
                    'deskripsi' => 'Mie pangsit direbus dadakan lalu diaduk dengan minyak bawang buatan sendiri. Topping ayam cincang dan jamur ditumis manis gurih, ditemani sawi hijau dan kuah kaldu bening yang dibungkus terpisah.',
                    'gambar' => 'images/food/photos/mie-ayam-jamur.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/mie-ayam-jamur.jpg', 'foto' => true, 'keterangan' => 'Semangkuk mie ayam'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => '1 mangkuk + kuah terpisah',
                        'Tingkat pedas' => 'Sambal dibungkus terpisah',
                        'Waktu siap' => 'Sekitar 5 menit',
                        'Kemasan' => 'Mangkuk kertas bertutup',
                        'Mengandung' => 'Gluten, kedelai',
                    ],
                ],

                [
                    'slug' => 'ayam-geprek',
                    'nama' => 'Ayam Geprek',
                    'stan' => 'Stan Dapur Mama',
                    'harga' => 13000,
                    'jenis' => 'makanan',
                    'sematan' => 'Pedas',
                    'nilai' => 4.9,
                    'ulasan' => 531,
                    'terjual' => 2105,
                    'waktuSiap' => '±8 menit',
                    'ringkasan' => 'Ayam crispy diulek bersama sambal bawang, disajikan dengan nasi hangat.',
                    'deskripsi' => 'Ayam digoreng dengan tepung berbumbu sampai renyah, lalu diulek di cobek bersama sambal bawang yang baru dibuat pagi itu. Level pedasnya bisa diatur, dari yang aman untuk lidah pemula sampai yang bikin ngos-ngosan.',
                    'gambar' => 'images/food/photos/ayam-geprek.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/ayam-geprek.jpg', 'foto' => true, 'keterangan' => 'Ayam geprek sambal bawang'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => '1 potong ayam + nasi',
                        'Tingkat pedas' => 'Tiga level, dipilih saat memesan',
                        'Waktu siap' => 'Sekitar 8 menit',
                        'Kemasan' => 'Kotak kertas tahan minyak',
                        'Mengandung' => 'Gluten, bawang',
                    ],
                ],

                [
                    'slug' => 'mie-goreng-jawa',
                    'nama' => 'Mie Goreng Jawa',
                    'stan' => 'Stan Pak Joko',
                    'harga' => 11000,
                    'jenis' => 'makanan',
                    'sematan' => null,
                    'nilai' => 4.6,
                    'ulasan' => 187,
                    'terjual' => 940,
                    'waktuSiap' => '±6 menit',
                    'ringkasan' => 'Mie goreng jawa yang digoreng dadakan begitu pesanan masuk.',
                    'deskripsi' => 'Mie basah digoreng di wajan besar bersama telur, kol, dan sawi dengan kecap manis yang sedikit dikaramelkan. Baru dimasak setelah pesananmu diterima, jadi masih panas saat diambil di loket.',
                    'gambar' => 'images/food/photos/mie-goreng-jawa.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/mie-goreng-jawa.jpg', 'foto' => true, 'keterangan' => 'Dimasak dadakan'],
                    ],
                    'spesifikasi' => [
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
            'label' => 'Gorengan & Camilan',
            'catatan' => 'Teman ngobrol saat istirahat kedua.',
            'sajian' => [

                [
                    'slug' => 'batagor-saus-kacang',
                    'nama' => 'Batagor Saus Kacang',
                    'stan' => 'Stan Kang Asep',
                    'harga' => 9000,
                    'jenis' => 'camilan',
                    'sematan' => null,
                    'nilai' => 4.7,
                    'ulasan' => 224,
                    'terjual' => 1130,
                    'waktuSiap' => '±4 menit',
                    'ringkasan' => 'Batagor goreng renyah dengan saus kacang dan perasan jeruk limau.',
                    'deskripsi' => 'Batagor digoreng ulang sebentar supaya kulitnya kembali renyah, lalu disiram saus kacang kental yang diulek sendiri. Jeruk limau dan kecap diberikan terpisah supaya kamu bisa mengatur asam manisnya.',
                    'gambar' => 'images/food/photos/batagor-saus-kacang.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/batagor-saus-kacang.jpg', 'foto' => true, 'keterangan' => 'Sepiring batagor'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => '5 potong + saus',
                        'Tingkat pedas' => 'Sambal terpisah',
                        'Waktu siap' => 'Sekitar 4 menit',
                        'Kemasan' => 'Kotak kertas + cup saus',
                        'Mengandung' => 'Ikan, kacang tanah, gluten',
                    ],
                ],

                [
                    'slug' => 'roti-bakar-mentega',
                    'nama' => 'Roti Bakar Mentega',
                    'stan' => 'Stan Camilan',
                    'harga' => 8000,
                    'jenis' => 'camilan',
                    'sematan' => 'Menu Baru',
                    'nilai' => 4.5,
                    'ulasan' => 96,
                    'terjual' => 380,
                    'waktuSiap' => '±5 menit',
                    'ringkasan' => 'Roti panggang mentega, renyah di luar dan lembut di dalamnya.',
                    'deskripsi' => 'Roti tawar tebal dipanggang di atas teflon dengan mentega sampai pinggirnya keemasan. Bagian dalamnya tetap lembut, dan taburan gula halus bisa diminta lebih sedikit lewat catatan pesanan.',
                    'gambar' => 'images/food/photos/roti-bakar-mentega.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/roti-bakar-mentega.jpg', 'foto' => true, 'keterangan' => 'Roti bakar mentega'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => '2 potong',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 5 menit',
                        'Kemasan' => 'Kertas roti + kantong',
                        'Mengandung' => 'Susu, gluten',
                    ],
                ],

                [
                    'slug' => 'batagor-kuah-pedas',
                    'nama' => 'Batagor Kuah Pedas',
                    'stan' => 'Stan Kang Asep',
                    'harga' => 10000,
                    'jenis' => 'camilan',
                    'sematan' => null,
                    'nilai' => 4.6,
                    'ulasan' => 118,
                    'terjual' => 520,
                    'waktuSiap' => '±5 menit',
                    'ringkasan' => 'Batagor dengan kuah pedas gurih untuk yang tidak suka saus kacang.',
                    'deskripsi' => 'Versi berkuah dari batagor Kang Asep: kaldu bening dengan cabai rawit ulek dan bawang goreng. Kuahnya dibungkus terpisah supaya batagornya tidak lembek di perjalanan menuju kelas.',
                    'gambar' => 'images/food/photos/batagor-kuah-pedas.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/batagor-kuah-pedas.jpg', 'foto' => true, 'keterangan' => 'Batagor bersiram kuah'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => '5 potong + kuah',
                        'Tingkat pedas' => 'Pedas, cabai bisa dikurangi',
                        'Waktu siap' => 'Sekitar 5 menit',
                        'Kemasan' => 'Mangkuk kertas + cup kuah',
                        'Mengandung' => 'Ikan, gluten',
                    ],
                ],

                [
                    'slug' => 'roti-bakar-coklat',
                    'nama' => 'Roti Bakar Coklat',
                    'stan' => 'Stan Camilan',
                    'harga' => 9000,
                    'jenis' => 'camilan',
                    'sematan' => null,
                    'nilai' => 4.8,
                    'ulasan' => 143,
                    'terjual' => 610,
                    'waktuSiap' => '±5 menit',
                    'ringkasan' => 'Roti panggang isi coklat, pas untuk istirahat kedua.',
                    'deskripsi' => 'Roti dipanggang dengan mentega lalu diisi meses coklat yang dibiarkan meleleh sebentar sebelum dipotong. Manisnya sedang, jadi tidak bikin eneg walau dimakan sambil belajar.',
                    'gambar' => 'images/food/photos/roti-bakar-coklat.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/roti-bakar-coklat.jpg', 'foto' => true, 'keterangan' => 'Isi coklat meleleh'],
                    ],
                    'spesifikasi' => [
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
            'catatan' => 'Penyegar setelah jam pelajaran.',
            'sajian' => [

                [
                    'slug' => 'es-teh-manis',
                    'nama' => 'Es Teh Manis',
                    'stan' => 'Stan Minuman',
                    'harga' => 4000,
                    'jenis' => 'minuman',
                    'sematan' => 'Paling Laris',
                    'nilai' => 4.9,
                    'ulasan' => 688,
                    'terjual' => 3240,
                    'waktuSiap' => '±2 menit',
                    'ringkasan' => 'Teh seduh dingin dengan es batu, menyegarkan setelah jam pelajaran.',
                    'deskripsi' => 'Teh tubruk diseduh pekat pagi hari lalu didinginkan, bukan teh instan. Kadar gula dan jumlah es bisa diatur saat memesan, jadi bisa disesuaikan dengan seleramu.',
                    'gambar' => 'images/food/photos/es-teh-manis.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/es-teh-manis.jpg', 'foto' => true, 'keterangan' => 'Segelas es teh'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => 'Gelas 400 ml',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 2 menit',
                        'Kemasan' => 'Gelas plastik + tutup segel',
                        'Mengandung' => 'Kafeina',
                    ],
                ],

                [
                    'slug' => 'es-teh-tawar',
                    'nama' => 'Es Teh Tawar',
                    'stan' => 'Stan Minuman',
                    'harga' => 3000,
                    'jenis' => 'minuman',
                    'sematan' => null,
                    'nilai' => 4.5,
                    'ulasan' => 74,
                    'terjual' => 410,
                    'waktuSiap' => '±2 menit',
                    'ringkasan' => 'Teh dingin tanpa gula untuk yang sedang mengurangi manis.',
                    'deskripsi' => 'Teh yang sama dengan es teh manis, hanya tanpa gula sama sekali. Cocok diminum bersama makanan berat karena tidak menutupi rasa makanannya.',
                    'gambar' => 'images/food/photos/es-teh-tawar.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/es-teh-tawar.jpg', 'foto' => true, 'keterangan' => 'Segelas teh tawar'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => 'Gelas 400 ml',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 2 menit',
                        'Kemasan' => 'Gelas plastik + tutup segel',
                        'Mengandung' => 'Kafeina',
                    ],
                ],

                [
                    'slug' => 'es-cendol',
                    'nama' => 'Es Cendol',
                    'stan' => 'Stan Minuman',
                    'harga' => 6000,
                    'jenis' => 'minuman',
                    'sematan' => null,
                    'nilai' => 4.7,
                    'ulasan' => 205,
                    'terjual' => 870,
                    'waktuSiap' => '±3 menit',
                    'ringkasan' => 'Cendol dengan santan dan gula merah, penyegar setelah jam olahraga.',
                    'deskripsi' => 'Cendol hijau dari tepung beras disiram santan encer dan gula merah cair yang dimasak sendiri. Es serutnya ditambahkan terakhir supaya santannya tidak langsung encer.',
                    'gambar' => 'images/food/photos/es-cendol.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/es-cendol.jpg', 'foto' => true, 'keterangan' => 'Semangkuk es cendol'],
                    ],
                    'spesifikasi' => [
                        'Porsi' => 'Gelas 400 ml',
                        'Tingkat pedas' => 'Tidak pedas',
                        'Waktu siap' => 'Sekitar 3 menit',
                        'Kemasan' => 'Gelas plastik + tutup segel',
                        'Mengandung' => 'Santan, gluten',
                    ],
                ],

                [
                    'slug' => 'susu-coklat-dingin',
                    'nama' => 'Susu Coklat Dingin',
                    'stan' => 'Stan Minuman',
                    'harga' => 6000,
                    'jenis' => 'minuman',
                    'sematan' => null,
                    'nilai' => 4.6,
                    'ulasan' => 61,
                    'terjual' => 290,
                    'waktuSiap' => '±2 menit',
                    'ringkasan' => 'Susu coklat dingin yang dikocok dadakan, tidak terlalu manis.',
                    'deskripsi' => 'Bubuk coklat dilarutkan dengan air panas sedikit dulu supaya tidak menggumpal, baru dicampur susu dingin dan dikocok. Manisnya bisa dikurangi lewat pilihan gula.',
                    'gambar' => 'images/food/photos/susu-coklat-dingin.jpg',
                    'foto' => true,
                    'galeri' => [
                        ['sumber' => 'images/food/photos/susu-coklat-dingin.jpg', 'foto' => true, 'keterangan' => 'Susu coklat dingin'],
                    ],
                    'spesifikasi' => [
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
