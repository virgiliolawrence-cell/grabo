# Grabo

Situs pemesanan kantin sekolah. Siswa memilih menu dari ponsel, membayar tunai
di loket atau lewat pembayaran daring, lalu mengambil pesanan tanpa mengantre.

Seluruh tampilan, pesan kesalahan, dan komentar kode memakai bahasa Indonesia.

> **Tahap sekarang: tampilan saja.** Belum ada tabel Grabo maupun dasbor
> pengelola &mdash; menu dibaca dari `config/menu.php`, keranjang disimpan di
> browser, dan status masuk hanya ditandai di session. Bagian pengelolaan
> dikerjakan menyusul.

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
php artisan migrate
npm run build
php artisan serve
```

Untuk mengembangkan tampilan, jalankan `npm run dev` di jendela terpisah supaya
perubahan CSS langsung terlihat.

## Cara masuk

Halaman siswa masih memakai masuk sementara: email berformat benar dan kata
sandi minimal 8 karakter sudah diterima. Belum ada pencocokan ke tabel apa pun.

## Peta halaman

Semuanya dijaga middleware `siswa`, kecuali halaman masuk.

| Alamat | Isi |
|---|---|
| `/` | Beranda: hero, menu populer, cara memesan |
| `/menu` | Seluruh menu per kategori, bisa disaring per stan lewat `?stan=` |
| `/menu/{slug}` | Deskripsi satu sajian, pilihan varian, tambah ke keranjang |
| `/promo` | Kode promo; sekali tekan kodenya disalin dan ditandai sedang dipakai |
| `/kontak` | Jam layanan, daftar stan, pertanyaan umum, dan formulir pesan |
| `/pembayaran` | Data pemesan, waktu ambil, metode bayar |
| `/pembayaran/selesai` | Kode pesanan dan cara membayar |
| `/masuk` | Halaman masuk |

## Di mana datanya

| Berkas | Isi |
|---|---|
| `config/menu.php` | Katalog sajian: nama, stan, harga, deskripsi, foto, galeri |
| `config/grabo.php` | Email, telepon, dan alamat media sosial kantin |
| `PromoController::kodePromo()` | Kode promo beserta potongan dan belanja minimalnya |
| `localStorage` | Isi keranjang (`grabo.keranjang`) dan kode promo aktif (`grabo.promo`) |

Foto menu ada di `public/images/food/photos/`, dinamai sesuai slug menunya.
Ilustrasi 2D untuk hero ada di `public/images/food/*.svg`.

## Penamaan

Nama berkas, kelas, method, variabel, kelas CSS, atribut `data-*`, dan id
elemen semuanya bahasa Indonesia.

| Bagian | Contoh |
|---|---|
| Controller | `BerandaController`, `MenuController`, `PembayaranController`, `KontakController`, `Autentikasi/MasukController` |
| Method | `tampilkan()`, `daftar()`, `form()`, `simpan()`, `selesai()`, `keluar()` |
| Middleware | `PastikanSiswaSudahMasuk` (alias `siswa`) |
| View | `beranda`, `menu`, `menu-rincian`, `promo`, `kontak`, `pembayaran`, `pembayaran-selesai`, `autentikasi/masuk` |
| Folder view | `tataletak/`, `bagian/`, `autentikasi/` |
| Nama rute | `beranda`, `menu`, `menu.rincian`, `promo`, `kontak`, `pembayaran`, `pembayaran.kirim`, `pembayaran.selesai`, `masuk`, `masuk.proses`, `keluar` |
| Kunci katalog | `nama`, `stan`, `harga`, `jenis`, `sematan`, `galeri`, `spesifikasi` |
| Kelas CSS | `judul-besar`, `kepala-situs`, `kapsul-nav`, `kartu-pilihan`, `titik-promo` |
| id elemen | `panelKeranjang`, `formPembayaran`, `jumlahRincian`, `kodePromo` |

Yang **tidak** diterjemahkan karena itu nama bawaan, bukan milik proyek ini:

- Direktori kerangka Laravel (`app/Http/Controllers`, `app/Models`, `resources/views`)
- Kelas dan method kerangka (`Controller`, `Request`, `handle()`, `boot()`, `casts()`)
- Kelas utilitas Tailwind (`flex`, `items-center`, `bg-white`)
- API peramban (`localStorage`, `classList`, `addEventListener`, `Intl.NumberFormat`)
- Kunci aturan validasi (`required`, `max`) dan kolom tabel bawaan (`name`, `email`, `password`)

## Catatan

- Angka dari browser tidak dipercaya. Saat pesanan dikirim, harga tiap baris
  dibaca ulang dari katalog dan potongan promo dihitung ulang di server.
- Pesanan belum disimpan. Rinciannya hanya dititipkan ke session untuk
  ditampilkan di halaman selesai.
- Belum ada gerbang pembayaran. Kode QR dan nomor rekening virtual di halaman
  selesai hanyalah contoh.
- Formulir kontak belum mengirim lewat server; tombolnya menyusun surat di
  aplikasi email siswa.

## Lisensi

Berjalan di atas Laravel, yang berlisensi [MIT](https://opensource.org/licenses/MIT).
