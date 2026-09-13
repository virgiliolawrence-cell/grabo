<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Grabo — Pesan Kantin Sekolah Tanpa Antre')</title>
    <meta name="description" content="@yield('description', 'Grabo adalah layanan pemesanan kantin sekolah. Pilih menu lewat ponsel, bayar di aplikasi, lalu ambil pesanan tanpa perlu mengantre.')">
    <link rel="icon" href="{{ $graboLogo }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-krem text-stone-800 antialiased">

@include('bagian.navigasi')

    <main>
        @yield('content')
    </main>

@include('bagian.kaki')

@include('bagian.keranjang')

@include('bagian.skrip')
</body>

</html>
