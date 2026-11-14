<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Masuk — Grabo')</title>
    <meta name="description" content="@yield('description', 'Masuk ke akun Grabo untuk memesan menu kantin sekolah tanpa antre.')">
    <link rel="icon" href="{{ $graboLogo }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Masuk → beranda (dan keluar → masuk) ikut berganti dengan memudar halus. --}}
    <style>
        @view-transition {
            navigation: auto;
        }
    </style>
</head>

{{-- Halaman masuk berdiri sendiri: tanpa bilah navigasi, kaki halaman, atau keranjang. --}}
<body class="bg-white text-stone-800 antialiased">
    @yield('content')
</body>

</html>
