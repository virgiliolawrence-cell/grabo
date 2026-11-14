# Grabo — Kantin Sekolah Digital

Aplikasi web pemesanan kantin sekolah. **Murid** memilih menu dari ponselnya
saat masih di kelas, membayar, lalu mengambil pesanan tanpa ikut mengantre.
**Penjual** (pengelola kantin) memakai dasbor untuk melihat pesanan yang masuk,
mencentang menu yang sudah disiapkan, mengelola menu dan diskon, serta melihat
laporan transaksi.

![Beranda Grabo](docs/gambar/beranda.jpg)

> Laporan lengkap pembagian tugas beserta gambar bukti hasil kerja:
> [docs/Laporan-Proyek-Grabo.pdf](docs/Laporan-Proyek-Grabo.pdf)

---

## Daftar Isi

- [Anggota Kelompok](#anggota-kelompok)
- [Latar Belakang](#latar-belakang)
- [Deskripsi Aplikasi](#deskripsi-aplikasi)
- [Fitur Murid](#fitur-murid)
- [Fitur Dasbor Penjual](#fitur-dasbor-penjual)
- [Desain UI (Figma)](#desain-ui-figma)
- [Tangkapan Layar](#tangkapan-layar)
- [Teknologi](#teknologi)
- [Cara Menjalankan](#cara-menjalankan)
- [Akun Contoh](#akun-contoh)
- [Struktur Proyek](#struktur-proyek)
- [Basis Data](#basis-data)
- [Keputusan Teknis](#keputusan-teknis)
- [Aturan Penamaan](#aturan-penamaan)
- [Alur Kerja Git](#alur-kerja-git)
- [Pengujian](#pengujian)
- [Batasan Saat Ini](#batasan-saat-ini)
- [Rencana Lanjutan](#rencana-lanjutan)

---

## Anggota Kelompok

| Nama | Bagian | Tanggung jawab |
|---|---|---|
| Virgilio Lawrence | Backend Developer | Basis data, autentikasi & peran akun, penyimpanan pesanan, logika dasbor penjual, validasi, pengujian |
| Vinno Alvian Chow | Frontend Developer | Halaman murid, tata letak dasbor penjual, animasi & transisi halaman, tampilan ponsel |
| Ryo Marvel | Frontend Designer (UI/UX) | Desain Figma halaman Beranda, Menu, dan Promo |
| Justin Geraldo | Backend Designer (UI/UX) | Desain halaman Masuk, Checkout, Pesanan Diterima, dan seluruh dasbor penjual |

## Latar Belakang

Jam istirahat di sekolah hanya 30 menit, tapi antrean di kantin bisa memakan
sepertiganya. Siswa yang kelasnya jauh dari kantin sering kehabisan menu, dan
petugas stan kewalahan menerima pesanan lisan dari puluhan siswa sekaligus
sambil menghitung uang kembalian.

Grabo memindahkan tahap **memilih dan memesan** ke waktu sebelum bel istirahat
berbunyi. Ketika bel berbunyi, murid tinggal mengambil pesanan yang sudah
disiapkan.

## Deskripsi Aplikasi

Grabo mengumpulkan seluruh stan kantin sekolah dalam satu aplikasi dengan dua
jenis akun:

| Peran | Setelah masuk | Yang bisa dilakukan |
|---|---|---|
| **Murid** | Beranda | Memesan menu, memakai kode promo, membayar, memantau status pesanan |
| **Penjual** | Dasbor penjual | Menyiapkan pesanan, mengelola menu, diskon, data murid, laporan, profil kantin |

Alur satu pesanan dari dua sisi:

```
Murid   : Masuk -> Pilih menu & varian -> Keranjang -> Bayar -> Kode pesanan
                                                                    |
Penjual :              Serahkan & selesai <- Centang tiap menu <- Pesanan masuk
                                                  |
Murid   :                      Pesanan Saya: "Siap diambil" -> ambil di loket
```

Yang membedakan Grabo dari sekadar daftar menu:

- **Satu halaman untuk semua stan.** Murid tidak perlu berjalan dari stan ke
  stan untuk tahu apa yang dijual hari ini.
- **Harga tidak bisa dipalsukan.** Isi keranjang dari browser dihitung ulang di
  server memakai harga di basis data.
- **Daftar centang per menu.** Penjual mencentang setiap menu yang sudah
  disiapkan, status pesanan ikut berubah, dan murid melihatnya di halaman
  Pesanan Saya.
- **Seluruh antarmuka dan kode berbahasa Indonesia**, termasuk pesan kesalahan
  formulir, nama tabel, dan nama kelas.

## Fitur Murid

### 1. Masuk & keamanan akun
- Masuk memakai akun di tabel `pengguna`; kata sandi disimpan ter-hash.
- Pesan kesalahan tampil bila email atau kata sandi tidak cocok.
- Murid diarahkan ke beranda; halaman yang dibuka sebelum masuk diingat dan
  dilanjutkan setelah masuk.
- Tombol mata untuk menampilkan kata sandi, dan pilihan **Ingat saya**.

### 2. Beranda
- Poster pembuka "Pesan Sebelum Bel, Makan Duluan" dengan ilustrasi melayang.
- Angka ringkas, tiga keunggulan layanan, menu populer, dan langkah cara memesan.

### 3. Daftar menu
- Korsel promo yang bergeser otomatis.
- Sajian dikelompokkan per kategori: Makanan Berat, Gorengan & Camilan, Minuman.
- Penyaring per stan lewat menu turun "Cari stan kantin" di bilah atas.
- Hanya menu yang sedang dijual yang tampil; menu yang ditandai habis oleh
  penjual otomatis tersembunyi.

### 4. Rincian sajian
- Galeri foto, nilai ulasan, jumlah terjual, perkiraan waktu siap, spesifikasi.
- Pilihan varian sesuai jenis sajian (tingkat pedas & porsi, tambah saus, atau
  suhu, gula & es), harga ikut berubah, dan kolom catatan untuk stan.
- Tombol **Tambah ke Keranjang** dan **Beli Sekarang**.

### 5. Keranjang & kode promo
- Panel geser dari kanan; isinya disimpan di `localStorage`.
- Tombol tambah, kurang, dan ubah varian per baris.
- Kode promo dari halaman Promo bisa disalin sekaligus dipasang ke keranjang.

### 6. Pembayaran & konfirmasi
- Nama dan kelas otomatis terisi dari akun.
- Waktu ambil: secepatnya, istirahat 1 (09.30), atau istirahat 2 (12.00).
- Metode: tunai di loket, saldo kartu pelajar, QRIS, transfer bank, dompet digital.
- Pesanan **disimpan ke basis data** dan mendapat kode unik, misalnya `GRB-7Q2KXA`.

### 7. Pesanan Saya
- Riwayat pesanan murid beserta statusnya: Menunggu, Diproses, Siap diambil,
  Selesai, atau Batal.
- Centang hijau di setiap menu yang sudah disiapkan stan.

### 8. Kontak
- Kartu email, telepon, dan loket; jam layanan; daftar stan; pertanyaan umum;
  formulir pesan.

## Fitur Dasbor Penjual

Semua halaman di `/penjual` hanya bisa dibuka akun berperan `penjual`. Murid
yang mencoba membukanya dikeluarkan dan diminta masuk dengan akun penjual.

| Halaman | Fungsi |
|---|---|
| **Manajemen Pembelian** | Semua pesanan terbaru, tab penyaring per status, pencarian kode/nama, **daftar centang per menu**, ubah status |
| **Rincian Pesanan** | Kartu pesanan dengan daftar centang, tombol *Serahkan & selesai* dan *Batalkan* |
| **Data Pembeli** | Daftar murid beserta jumlah pesanan, total belanja, pesanan terakhir; daftarkan akun murid baru |
| **Rincian Pembeli** | Semua yang pernah dibeli satu murid: pesanan berjalan (bisa dicentang), riwayat, menu favorit |
| **Manajemen Menu** | Tambah, ubah, unggah foto, tandai habis / jual lagi |
| **Manajemen Diskon** | Tambah kode promo, aktifkan / nonaktifkan, hitungan pemakaian |
| **Laporan Transaksi** | Saring per tanggal & metode bayar, baris total pendapatan, hapus transaksi |
| **Profil Penjual** | Nama kantin, pengelola, kontak, lokasi, jam buka & tutup |

Status pesanan berjalan otomatis mengikuti centang:

```
Menunggu --(centang pertama)--> Diproses --(semua tercentang)--> Siap diambil --(diserahkan)--> Selesai
```

## Desain UI (Figma)

Desain dibuat di Figma lebih dulu, lalu dijadikan acuan saat membangun halaman.
Berkas desain aslinya ada di [`docs/figma/`](docs/figma).

| Halaman | Desainer | Desain |
|---|---|---|
| Beranda | Ryo Marvel | ![Desain beranda](docs/gambar/desain-homepage.jpg) |
| Menu | Ryo Marvel | ![Desain menu](docs/gambar/desain-menu.jpg) |
| Promo | Ryo Marvel | ![Desain promo](docs/gambar/desain-promo.jpg) |
| Masuk | Justin Geraldo | ![Desain halaman masuk](docs/gambar/desain-login.jpg) |
| Checkout | Justin Geraldo | ![Desain checkout](docs/gambar/desain-checkout.jpg) |
| Pesanan diterima | Justin Geraldo | ![Desain pesanan diterima](docs/gambar/desain-booking.jpg) |
| Dasbor penjual | Justin Geraldo | Lihat tabel [Dasbor penjual](#dasbor-penjual) di Tangkapan Layar |

## Tangkapan Layar

### Halaman murid

| Halaman | Tampilan |
|---|---|
| Masuk | ![Halaman masuk](docs/gambar/masuk.jpg) |
| Daftar menu | ![Daftar menu](docs/gambar/menu.jpg) |
| Rincian sajian | ![Rincian sajian](docs/gambar/menu-rincian.jpg) |
| Keranjang | ![Keranjang](docs/gambar/keranjang.jpg) |
| Promo | ![Halaman promo](docs/gambar/promo.jpg) |
| Pembayaran | ![Halaman pembayaran](docs/gambar/pembayaran.jpg) |
| Pesanan diterima | ![Pesanan diterima](docs/gambar/pembayaran-selesai.jpg) |
| Pesanan Saya | ![Pesanan Saya](docs/gambar/pesanan-saya.jpg) |
| Kontak | ![Halaman kontak](docs/gambar/kontak.jpg) |

### Dasbor penjual

| Halaman | Tampilan |
|---|---|
| Manajemen Pembelian | ![Manajemen Pembelian](docs/gambar/dasbor-pembelian.jpg) |
| Daftar centang | ![Pesanan setelah satu menu dicentang](docs/gambar/dasbor-centang-sesudah.jpg) |
| Rincian pembeli | ![Rincian pembeli](docs/gambar/dasbor-pembeli-rincian.jpg) |
| Manajemen Menu | ![Manajemen Menu](docs/gambar/dasbor-menu.jpg) |
| Manajemen Diskon | ![Manajemen Diskon](docs/gambar/dasbor-diskon.jpg) |
| Laporan Transaksi | ![Laporan Transaksi](docs/gambar/dasbor-laporan.jpg) |
| Profil Penjual | ![Profil Penjual](docs/gambar/dasbor-profil.jpg) |

## Teknologi

| Bagian | Dipakai | Alasan |
|---|---|---|
| Bahasa | PHP 8.3+ (dikembangkan di 8.5) | Sudah tersedia di Laragon, dipakai di sekolah |
| Kerangka | Laravel 13 | Routing, Blade, Eloquent, validasi, dan autentikasi siap pakai |
| Basis data | SQLite (bawaan) atau MySQL | SQLite langsung jalan tanpa server; MySQL dipakai di Laragon |
| Templat | Blade + komponen anonim | Menyusun halaman langsung dari PHP, tanpa API terpisah |
| Gaya | Tailwind CSS v4 | Token warna dan font diatur di `@theme` |
| Pembangun aset | Vite 8 | Membangun CSS cepat dan memuat font mandiri |
| Skrip | JavaScript biasa | Keranjang, varian, dan animasi tidak butuh kerangka besar |
| Transisi halaman | View Transitions API | Perpindahan halaman halus tanpa aplikasi satu halaman |
| Font | Figtree + Anton | Figtree untuk teks, Anton untuk judul poster |
| Pengujian | Pest 4 | Sintaksnya ringkas dan mudah dibaca |

## Cara Menjalankan

Kebutuhan: PHP 8.3+, Composer, dan Node.js 20+.

```bash
git clone https://github.com/virgiliolawrence-cell/grabo.git
cd grabo
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan serve
```

Buka `http://127.0.0.1:8000`.

- `.env.example` memakai **SQLite**, jadi tidak perlu menyiapkan server basis
  data. Untuk **MySQL** (misalnya di Laragon), buat basis data `grabo`, lalu ubah
  `DB_CONNECTION=mysql` dan `DB_DATABASE=grabo` di `.env` sebelum `migrate --seed`.
- `migrate --seed` membuat semua tabel lalu mengisi katalog, akun contoh, dan
  pesanan contoh.
- `storage:link` diperlukan agar foto menu yang diunggah penjual bisa tampil.
- Untuk mengembangkan tampilan, jalankan `npm run dev` di jendela terpisah.

## Akun Contoh

Dibuat oleh `database/seeders/PenggunaSeeder.php`. Kata sandi semua akun contoh
tertulis di komentar bagian atas berkas itu.

| Peran | Email |
|---|---|
| Penjual | `penjual@grabo.sch.id` |
| Murid | `alya@grabo.sch.id`, `bima@grabo.sch.id`, `citra@grabo.sch.id`, `dimas@grabo.sch.id`, `eka@grabo.sch.id`, `fajar@grabo.sch.id` |

Akun murid baru juga bisa dibuat penjual lewat **Pembeli → Daftarkan Murid**.

## Struktur Proyek

```
grabo/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Autentikasi/MasukController.php      masuk, proses, keluar
│   │   │   ├── BerandaController.php                beranda
│   │   │   ├── MenuController.php                   katalog dari basis data, daftar, rincian
│   │   │   ├── PembayaranController.php             formulir, simpan pesanan, selesai
│   │   │   ├── PesananSayaController.php            riwayat pesanan murid
│   │   │   ├── PromoController.php                  kode promo aktif & halaman promo
│   │   │   ├── KontakController.php                 jam layanan, tanya-jawab
│   │   │   └── Penjual/
│   │   │       ├── KelolaPembelianController.php    daftar, rincian, ubah status, centang menu
│   │   │       ├── DataPembeliController.php        daftar murid, rincian, daftarkan murid
│   │   │       ├── KelolaMenuController.php         tambah, ubah, tandai habis
│   │   │       ├── KelolaDiskonController.php       tambah, aktif/nonaktif
│   │   │       ├── LaporanTransaksiController.php   saring, rekap, hapus
│   │   │       └── ProfilPenjualController.php      profil kantin
│   │   └── Middleware/PastikanPenjual.php           penjaga dasbor penjual
│   └── Models/                                      Pengguna, Kategori, Sajian, Diskon,
│                                                    Pesanan, ItemPesanan, ProfilKantin
├── config/
│   ├── menu.php                                     isi awal katalog (dibaca seeder)
│   └── grabo.php                                    email, telepon, media sosial
├── database/
│   ├── migrations/                                  tabel pengguna, katalog, pesanan
│   ├── factories/                                   Pengguna, Sajian, Diskon, Pesanan
│   └── seeders/                                     Katalog, Pengguna, Pesanan
├── lang/id/                                         pesan validasi, autentikasi, paginasi
├── resources/
│   ├── css/app.css                                  token warna, font, animasi, kelas khusus
│   └── views/
│       ├── tataletak/                               grabo (murid), penjual, autentikasi
│       ├── bagian/                                  navigasi, kaki, keranjang, skrip
│       │   └── penjual/                             kepala & kaki dasbor
│       ├── components/                              lencana, penjual/judul-halaman,
│       │                                            penjual/kartu-pesanan, penjual/centang-item
│       ├── penjual/                                 semua halaman dasbor
│       └── *.blade.php                              halaman murid
├── public/gambar/
│   ├── sajian/                                      foto menu
│   ├── ilustrasi/                                   ilustrasi 2D beranda
│   └── logo-grabo.png
├── docs/
│   ├── Laporan-Proyek-Grabo.pdf / .docx             laporan kelompok
│   ├── figma/                                       ekspor desain Figma (PNG)
│   └── gambar/                                      tangkapan layar README
├── routes/web.php                                   rute murid & dasbor penjual
└── tests/                                           57 pengujian Pest
```

## Basis Data

| Tabel | Isi |
|---|---|
| `pengguna` | Akun murid dan penjual: `nama`, `email`, `kata_sandi` (ter-hash), `peran`, `nis`, `kelas` |
| `kategori` | Kelompok menu dan urutannya |
| `sajian` | Menu: nama, stan, harga, jenis, deskripsi, foto, galeri, spesifikasi, `tersedia` |
| `diskon` | Kode promo: `kode`, `label`, `potongan`, `belanja_minimal`, `aktif` |
| `pesanan` | Kode, pembeli, waktu ambil, metode bayar, subtotal, potongan, total, `status` |
| `item_pesanan` | Isi pesanan: menu, harga saat dipesan, jumlah, varian, catatan, `sudah_disiapkan` |
| `profil_kantin` | Identitas kantin (satu baris) |

Sumber lain:

| Sumber | Isi |
|---|---|
| `localStorage` | Isi keranjang (`grabo.keranjang`), kode promo aktif (`grabo.promo`), tab navigasi terakhir |
| `config/grabo.php` | Email, telepon, dan alamat media sosial kantin |
| `public/storage/menu/` | Foto menu yang diunggah penjual |

## Keputusan Teknis

**Total dihitung ulang di server.** Keranjang hidup di browser, jadi isinya bisa
disunting siapa saja. `PembayaranController::simpan()` membaca ulang harga dari
tabel `sajian`, membuang menu yang tidak dikenal atau sedang habis, dan
menghitung ulang potongan dari tabel `diskon`. Nilai `total` kiriman formulir
diabaikan.

**Harga disalin ke `item_pesanan`.** Nama, stan, dan harga menu disimpan apa
adanya saat dipesan, jadi riwayat dan laporan tidak berubah meskipun menu
diubah atau dihapus kemudian.

**Status mengikuti daftar centang.** `KelolaPembelianController::centangItem()`
menghitung menu yang sudah disiapkan: centang pertama mengubah status menjadi
Diproses, semua tercentang menjadi Siap diambil. Menekan *Serahkan & selesai*
mencentang semua menu sekaligus.

**Satu sumber katalog.** Beranda, halaman menu, rincian, dan pembayaran membaca
katalog lewat `MenuController::kategori()`, yang mengambil data dari tabel dan
mengembalikannya dalam bentuk yang sama dengan katalog lama. View murid tidak
perlu diubah saat katalog pindah ke basis data.

**Peran dijaga di middleware.** Rute `/penjual` memakai `auth` dan
`PastikanPenjual`. Murid yang tersesat ke dasbor dikeluarkan dan diarahkan ke
halaman masuk dengan penjelasan, bukan dibiarkan di halaman 403.

**Transisi halaman tanpa SPA.** Perpindahan halaman memakai View Transitions
lintas dokumen. Bilah navigasi tetap di tempat, isi halaman bergeser halus, dan
pil penanda tab meluncur dari tab sebelumnya. Semua animasi mati bila perangkat
memakai `prefers-reduced-motion`.

**Keranjang di `localStorage`, bukan session.** Isinya bertahan meski tab
ditutup dan tidak membebani server. Setiap pembacaan dibungkus `try/catch`
karena mode penyamaran bisa melarang penyimpanan.

## Aturan Penamaan

Semua nama buatan proyek ini memakai bahasa Indonesia secara konsisten.

| Bagian | Contoh |
|---|---|
| Controller | `KelolaPembelianController`, `DataPembeliController`, `LaporanTransaksiController` |
| Method | `daftar()`, `tampilkan()`, `simpan()`, `perbarui()`, `centangItem()`, `alihKetersediaan()` |
| Model & tabel | `Pengguna` → `pengguna`, `Sajian` → `sajian`, `ItemPesanan` → `item_pesanan` |
| Kolom | `nama`, `kata_sandi`, `belanja_minimal`, `sudah_disiapkan`, `waktu_ambil` |
| Middleware | `PastikanPenjual` (alias `penjual`) |
| Nama rute | `penjual.pembelian.centang`, `penjual.laporan.hapus`, `pesanan-saya` |
| Isian formulir | `kata_sandi`, `ulangi_kata_sandi`, `ingat_saya`, `kategori_id` |
| Komponen Blade | `x-lencana`, `x-penjual.kartu-pesanan`, `x-penjual.centang-item` |
| Kelas CSS | `judul-besar`, `kapsul-navigasi`, `penanda-penjual`, `siap-muncul`, `aktif` |
| Atribut & JS | `data-menu-turun`, `data-keranjang-tambah`, `geserPenandaNavigasi()`, `tampilkanNotifikasi()` |
| Folder aset | `public/gambar/sajian`, `public/gambar/ilustrasi` |

Yang **tidak** diterjemahkan karena ditetapkan Laravel atau peramban:

- Akhiran jenis kelas: `Controller`, `Seeder`, `Factory`, `Test`
- Method kerangka: `handle()`, `up()`, `run()`, `casts()`, `$permintaan->user()`
- Kolom yang dibaca Laravel sendiri: `id`, `created_at`, `updated_at`,
  `email_verified_at`, `remember_token`
- Tabel internal Laravel: `sessions`, `cache`, `jobs`, `password_reset_tokens`
- Kunci `password` pada `Auth::attempt()`; nilainya tetap dicocokkan ke kolom
  `kata_sandi` lewat `Pengguna::getAuthPasswordName()`
- Kelas utilitas Tailwind dan API peramban (`localStorage`, `classList`)

## Alur Kerja Git

- Fitur dikerjakan di branch terpisah, misalnya `fitur/dasbor-penjual`, lalu
  digabung ke `main` setelah semua pengujian lulus.
- Pesan commit berbahasa Indonesia, diawali kalimat ringkas tentang apa yang
  berubah, lalu penjelasan alasannya bila perlu.

## Pengujian

```bash
php artisan test
```

57 pengujian Pest. Basis data pengujian memakai SQLite di memori dan diisi
katalog lewat `KatalogSeeder` sebelum setiap uji.

| Berkas | Yang diuji |
|---|---|
| `Feature/HalamanPenjualTest.php` | Akses per peran, daftar centang & status otomatis, data pembeli, menu, diskon, laporan, hapus transaksi, profil, sorotan tab navigasi |
| `Feature/PesananMuridTest.php` | Masuk & keluar per peran, kata sandi salah, pesanan tersimpan dengan harga katalog, Pesanan Saya |
| `Feature/HalamanSiswaTest.php` | Penjagaan halaman, semua halaman murid terbuka, penyaring stan, slug tak dikenal |
| `Feature/PesanValidasiTest.php` | Tidak ada pesan kesalahan yang tampil sebagai kunci mentah seperti `validation.gte` |
| `Feature/PenggunaTest.php` | Tabel `pengguna` & kolom `kata_sandi`, masuk dengan kata sandi ter-hash |
| `Unit/KatalogTest.php` | Foto menu tersedia, slug unik, daftar stan, bentuk kode promo |

## Batasan Saat Ini

- **Belum ada gerbang pembayaran.** Kode QR dan nomor rekening virtual di
  halaman konfirmasi hanyalah contoh.
- **Harga tambahan varian** (misalnya porsi jumbo) baru dihitung di layar; server
  masih memakai harga dasar menu.
- **Satu akun penjual untuk semua stan.** Belum ada pembagian akses per stan.
- **Formulir kontak belum mengirim lewat server**; tombolnya menyusun surat di
  aplikasi email murid.

## Rencana Lanjutan

1. Harga varian ikut dihitung dan disimpan di server.
2. Notifikasi ke murid ketika pesanan siap diambil.
3. Akun penjual per stan, masing-masing hanya melihat pesanan stannya.
4. Ekspor laporan transaksi ke Excel atau PDF.
5. Saldo kartu pelajar ikut terpotong saat memesan.
