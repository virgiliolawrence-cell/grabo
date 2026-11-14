<?php

/*
 * Pesan kesalahan formulir dalam bahasa Indonesia.
 *
 * Hanya berisi aturan yang benar-benar dipakai Grabo, bukan seluruh daftar
 * bawaan Laravel. Tambahkan barisnya kalau nanti ada aturan baru; aturan
 * yang belum diterjemahkan akan tampil dalam bahasa Inggris.
 */

return [

    'accepted' => ':attribute wajib disetujui.',
    'after' => ':attribute harus tanggal setelah :date.',
    'after_or_equal' => ':attribute tidak boleh lebih awal dari :date.',
    'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
    'before' => ':attribute harus tanggal sebelum :date.',
    'boolean' => ':attribute hanya boleh berisi ya atau tidak.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'date' => ':attribute bukan tanggal yang benar.',
    'date_format' => ':attribute harus berformat :format.',
    'different' => ':attribute dan :other harus berbeda.',
    'email' => ':attribute harus berupa alamat email yang benar.',
    'exists' => ':attribute yang dipilih tidak ada dalam data.',
    'file' => ':attribute harus berupa berkas.',
    'filled' => ':attribute wajib diisi.',
    'image' => ':attribute harus berupa gambar.',
    'in' => ':attribute yang dipilih tidak tersedia.',
    'integer' => ':attribute harus berupa angka bulat.',
    'json' => ':attribute tidak terbaca. Muat ulang halamannya lalu coba lagi.',
    'lowercase' => ':attribute harus ditulis dengan huruf kecil.',
    'mimes' => ':attribute harus berupa berkas :values.',
    'not_in' => ':attribute yang dipilih tidak boleh dipakai.',
    'numeric' => ':attribute harus berupa angka.',
    'present' => ':attribute wajib ada.',
    'prohibited' => ':attribute tidak boleh diisi.',
    'regex' => 'Format :attribute tidak sesuai.',
    'required' => ':attribute wajib diisi.',
    'required_if' => ':attribute wajib diisi bila :other bernilai :value.',
    'same' => ':attribute dan :other harus sama.',
    'string' => ':attribute harus berupa teks.',
    'unique' => ':attribute sudah dipakai.',
    'uploaded' => ':attribute gagal diunggah.',
    'uppercase' => ':attribute harus ditulis dengan huruf besar.',
    'url' => ':attribute harus berupa tautan yang benar.',

    'max' => [
        'array' => ':attribute tidak boleh lebih dari :max item.',
        'file' => ':attribute tidak boleh lebih besar dari :max kilobita.',
        'numeric' => ':attribute tidak boleh lebih dari :max.',
        'string' => ':attribute tidak boleh lebih dari :max karakter.',
    ],

    'min' => [
        'array' => ':attribute minimal berisi :min item.',
        'file' => ':attribute minimal berukuran :min kilobita.',
        'numeric' => ':attribute minimal :min.',
        'string' => ':attribute minimal :min karakter.',
    ],

    'between' => [
        'array' => ':attribute harus berisi antara :min dan :max item.',
        'file' => ':attribute harus berukuran antara :min dan :max kilobita.',
        'numeric' => ':attribute harus antara :min dan :max.',
        'string' => ':attribute harus antara :min dan :max karakter.',
    ],

    'size' => [
        'array' => ':attribute harus berisi :size item.',
        'file' => ':attribute harus berukuran :size kilobita.',
        'numeric' => ':attribute harus bernilai :size.',
        'string' => ':attribute harus :size karakter.',
    ],

    /*
     * Perbandingan dengan kolom lain atau angka. Bila pembandingnya kolom lain
     * (mis. 'gte:potongan'), :value berisi nilai kolom itu.
     */
    'gt' => [
        'array' => ':attribute harus berisi lebih dari :value item.',
        'file' => ':attribute harus lebih besar dari :value kilobita.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string' => ':attribute harus lebih dari :value karakter.',
    ],

    'gte' => [
        'array' => ':attribute minimal berisi :value item.',
        'file' => ':attribute minimal berukuran :value kilobita.',
        'numeric' => ':attribute tidak boleh lebih kecil dari :value.',
        'string' => ':attribute minimal :value karakter.',
    ],

    'lt' => [
        'array' => ':attribute harus berisi kurang dari :value item.',
        'file' => ':attribute harus lebih kecil dari :value kilobita.',
        'numeric' => ':attribute harus lebih kecil dari :value.',
        'string' => ':attribute harus kurang dari :value karakter.',
    ],

    'lte' => [
        'array' => ':attribute tidak boleh berisi lebih dari :value item.',
        'file' => ':attribute tidak boleh lebih besar dari :value kilobita.',
        'numeric' => ':attribute tidak boleh lebih besar dari :value.',
        'string' => ':attribute tidak boleh lebih dari :value karakter.',
    ],

    'custom' => [
        'belanja_minimal' => [
            'gte' => 'Belanja minimal tidak boleh lebih kecil dari potongannya (Rp :value). Kalau tidak, diskonnya bisa membuat total menjadi nol.',
        ],
        'jam_tutup' => [
            'after' => 'Jam tutup harus lebih lambat dari jam buka.',
        ],
        'sampai' => [
            'after_or_equal' => 'Tanggal akhir tidak boleh lebih awal dari tanggal mulai.',
        ],
        'ulangi_kata_sandi' => [
            'same' => 'Kata sandi yang diulang tidak sama.',
        ],
    ],

    /*
     * Nama kolom yang enak dibaca. Tanpa ini, pesannya memakai nama kolom
     * apa adanya, misalnya "belanja_minimal" atau "kategori_id".
     */
    'attributes' => [
        // Pembayaran (halaman siswa).
        'bank' => 'Bank',
        'catatan' => 'Catatan',
        'dompet' => 'Dompet digital',
        'kelas' => 'Kelas',
        'keranjang' => 'Isi keranjang',
        'metode' => 'Metode pembayaran',
        'nama' => 'Nama siswa',
        'promo' => 'Kode promo',
        'total' => 'Total',
        'waktu' => 'Waktu pengambilan',

        // Masuk & akun.
        'email' => 'Email',
        'ingat_saya' => 'Ingat saya',
        'kata_sandi' => 'Kata sandi',
        'nis' => 'NIS',
        'ulangi_kata_sandi' => 'Ulangan kata sandi',

        // Formulir dasbor penjual.
        'belanja_minimal' => 'Belanja minimal',
        'dari' => 'Tanggal mulai',
        'deskripsi' => 'Deskripsi',
        'gambar' => 'Foto menu',
        'harga' => 'Harga',
        'jam_buka' => 'Jam buka',
        'jam_tutup' => 'Jam tutup',
        'jenis' => 'Jenis',
        'kategori_id' => 'Kategori',
        'kode' => 'Kode promo',
        'label' => 'Nama promo',
        'lokasi' => 'Lokasi',
        'pengelola' => 'Pengelola',
        'potongan' => 'Potongan',
        'ringkasan' => 'Ringkasan',
        'sampai' => 'Tanggal akhir',
        'stan' => 'Stan',
        'status' => 'Status',
        'telepon' => 'Telepon',
        'tersedia' => 'Status dijual',
    ],

];
