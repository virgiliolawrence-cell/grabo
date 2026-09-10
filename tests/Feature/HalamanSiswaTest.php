<?php

use App\Models\MenuItem;

/*
 * Uji dasar halaman siswa. Semua halaman dijaga middleware 'student',
 * jadi statusnya ditandai dulu di session sebelum halamannya dibuka.
 */

test('halaman siswa mengalihkan ke halaman masuk kalau belum masuk', function () {
    $this->get('/')->assertRedirect(route('login'));
    $this->get('/menu')->assertRedirect(route('login'));
    $this->get('/checkout')->assertRedirect(route('login'));
});

test('beranda terbuka setelah masuk', function () {
    $this->withSession(['grabo_logged_in' => true, 'grabo_user' => 'siswa@grabo.sch.id'])
        ->get('/')
        ->assertStatus(200)
        ->assertSee('Menu Populer');
});

test('halaman menu menampilkan menu dari database', function () {
    $menu = MenuItem::factory()->create([
        'slug' => 'nasi-uduk-uji',
        'name' => 'Nasi Uduk Uji',
        'stall' => 'Stan Uji',
        'category' => 'Makanan Berat',
        'is_available' => true,
    ]);

    $this->withSession(['grabo_logged_in' => true, 'grabo_user' => 'siswa@grabo.sch.id'])
        ->get('/menu')
        ->assertStatus(200)
        ->assertSee($menu->name);
});

test('menu yang disembunyikan tidak muncul di halaman menu', function () {
    $menu = MenuItem::factory()->create([
        'slug' => 'menu-tersembunyi',
        'name' => 'Menu Tersembunyi',
        'is_available' => false,
    ]);

    $this->withSession(['grabo_logged_in' => true, 'grabo_user' => 'siswa@grabo.sch.id'])
        ->get('/menu')
        ->assertStatus(200)
        ->assertDontSee($menu->name);
});
