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

    'custom' => [],

    /*
     * Nama kolom yang enak dibaca. Tanpa ini, pesannya memakai nama kolom
     * apa adanya, misalnya "min_spend" atau "is_active".
     */
    'attributes' => [
        'amount' => 'Potongan',
        'badge' => 'Label',
        'balance' => 'Saldo kartu',
        'bank' => 'Bank',
        'catatan' => 'Catatan',
        'category' => 'Kategori',
        'class' => 'Kelas',
        'code' => 'Kode',
        'description' => 'Deskripsi lengkap',
        'discount' => 'Potongan',
        'email' => 'Email',
        'ends_at' => 'Tanggal berakhir',
        'dompet' => 'Dompet digital',
        'image' => 'Path gambar',
        'is_active' => 'Status aktif',
        'is_available' => 'Status tampil',
        'kelas' => 'Kelas',
        'keranjang' => 'Isi keranjang',
        'label' => 'Nama promo',
        'metode' => 'Metode pembayaran',
        'min_spend' => 'Belanja minimal',
        'nama' => 'Nama siswa',
        'name' => 'Nama',
        'nis' => 'NIS',
        'note' => 'Catatan',
        'password' => 'Kata sandi',
        'price' => 'Harga',
        'promo' => 'Kode promo',
        'remember' => 'Ingat saya',
        'stall' => 'Stan',
        'starts_at' => 'Tanggal mulai',
        'status' => 'Status',
        'stock' => 'Stok',
        'summary' => 'Ringkasan',
        'total' => 'Total',
        'type' => 'Jenis varian',
        'waktu' => 'Waktu pengambilan',
    ],

];
