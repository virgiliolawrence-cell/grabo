# Grabo

Situs pemesanan kantin sekolah. Siswa memilih menu dari ponsel, membayar tunai
di loket atau lewat pembayaran daring, lalu mengambil pesanan tanpa mengantre.
Pengelola kantin mengurus menu, siswa, transaksi, dan diskon lewat dasbor.

Seluruh tampilan, pesan kesalahan, dan komentar kode memakai bahasa Indonesia.

## Kebutuhan

- PHP 8.3 atau lebih baru
- Composer
- Node.js 20 atau lebih baru
- SQLite (bawaan PHP)

## Cara menjalankan

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Untuk mengembangkan tampilan, jalankan `npm run dev` di jendela terpisah supaya
perubahan CSS langsung terlihat.

## Akun contoh

Kata sandi ketiganya `grabo12345`. Ini hanya untuk pengembangan — ganti sebelum
dipakai sungguhan.

| Email | Peran | Bisa apa |
|---|---|---|
| `admin@grabo.sch.id` | Administrator | semua modul dasbor |
| `burina@grabo.sch.id` | Petugas Kantin | semua modul dasbor |
| `pakjoko@grabo.sch.id` | Petugas Kantin | semua modul dasbor |

Halaman siswa masih memakai masuk sementara: email berformat benar dan kata
sandi minimal 8 karakter sudah diterima, statusnya hanya ditandai di session.

## Peta halaman

**Siswa** &mdash; semuanya dijaga middleware `student`.

| Alamat | Isi |
|---|---|
| `/` | Beranda: hero, menu populer, cara memesan |
| `/menu` | Seluruh menu per kategori, bisa disaring per stan lewat `?stan=` |
| `/menu/{slug}` | Deskripsi satu menu, pilihan varian, tambah ke keranjang |
| `/promo` | Kode promo yang sedang aktif |
| `/checkout` | Data pemesan, waktu ambil, metode bayar |
| `/checkout/selesai` | Kode pesanan dan cara membayar |
| `/login` | Halaman masuk |

**Pengelola** &mdash; dijaga middleware `admin`.

| Alamat | Isi |
|---|---|
| `/admin/masuk` | Masuk dasbor |
| `/admin` | Ringkasan: tiga kartu angka, transaksi terbaru, menu terlaris |
| `/admin/menu` | Manajemen menu: harga, stok, tampil/sembunyi |
| `/admin/siswa` | Daftar siswa dan saldo kartunya |
| `/admin/transaksi` | Laporan transaksi, penyaring, ubah status pesanan |
| `/admin/diskon` | Manajemen kode promo |

## Susunan data

| Tabel | Isi |
|---|---|
| `users` | Akun pengelola: `admin` dan `petugas` |
| `students` | Daftar siswa beserta saldo kartu |
| `menu_items` | Katalog menu tiap stan |
| `discounts` | Kode promo, potongan, dan syaratnya |
| `orders` / `order_items` | Pesanan; nama dan harga menu disalin ke barisnya supaya laporan lama tidak ikut berubah saat harga diganti |

## Catatan

- Angka dari browser tidak dipercaya. Saat pesanan disimpan, harga tiap baris
  dibaca ulang dari tabel menu dan potongan dari tabel diskon.
- Belum ada gerbang pembayaran. Kode QR dan nomor rekening virtual di halaman
  selesai hanyalah contoh.
- Saldo kartu siswa tercatat, tapi belum ikut terpotong saat memesan.
- Alamat media sosial dan kontak diatur di `config/grabo.php`. Yang dikosongkan
  tidak akan dirender.

## Lisensi

Berjalan di atas Laravel, yang berlisensi [MIT](https://opensource.org/licenses/MIT).
