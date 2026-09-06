<?php

/*
 * Identitas dan tautan luar milik kantin.
 *
 * Ditaruh di config supaya sekolah bisa mengganti alamat akunnya tanpa
 * menyunting Blade. Isi yang dikosongkan tidak akan dirender sama sekali,
 * jadi tidak ada ikon yang menautkan ke halaman kosong.
 */

return [

    'kontak' => [
        'email' => env('GRABO_EMAIL', 'halo@grabo.sch.id'),
        'telepon' => env('GRABO_TELEPON', '02155501 98'),
    ],

    'sosial' => [
        'instagram' => env('GRABO_INSTAGRAM', 'https://www.instagram.com/grabo.sch.id'),
        'tiktok' => env('GRABO_TIKTOK', 'https://www.tiktok.com/@grabo.sch.id'),
        'facebook' => env('GRABO_FACEBOOK', 'https://www.facebook.com/grabo.sch.id'),
    ],

];
