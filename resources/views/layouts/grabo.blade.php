@php
    // Logo lockup: ilustrasi dua anak + wordmark GRABO.
    $graboLogo = asset('images/grabo-logo.png');
@endphp
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

<body class="bg-cream text-stone-800 antialiased">

@include('partials.nav')

    <main>
        @yield('content')
    </main>

@include('partials.footer')

@include('partials.cart')

@include('partials.scripts')
</body>

</html>
