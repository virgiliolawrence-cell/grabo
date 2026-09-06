<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') &mdash; Grabo</title>
    <link rel="icon" href="{{ $graboLogo }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-stone-100 text-stone-800 antialiased">

    {{-- Bilah atas dashboard --}}
    <header class="bg-stone-950 text-white">
        <div class="mx-auto flex max-w-[1600px] flex-wrap items-center justify-between gap-4 px-5 py-3.5 lg:px-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="flex items-center rounded-lg bg-white px-2 py-1">
                    <img src="{{ $graboLogo }}" alt="Grabo" class="h-8 w-auto" width="30" height="32">
                </span>
                <span class="leading-tight">
                    <span class="block headline text-lg">Dashboard</span>
                    <span class="block text-[10px] uppercase tracking-[0.2em] text-white/50">Kantin Grabo</span>
                </span>
            </a>

            @php
                $menuAdmin = [
                    ['label' => 'Ringkasan', 'route' => 'admin.dashboard'],
                    ['label' => 'Manajemen Menu', 'route' => 'admin.menu.index'],
                    ['label' => 'Daftar Siswa', 'route' => 'admin.students.index'],
                    ['label' => 'Laporan Transaksi', 'route' => 'admin.transactions.index'],
                    ['label' => 'Manajemen Diskon', 'route' => 'admin.discounts.index'],
                ];
            @endphp

            <nav class="order-3 flex w-full flex-wrap gap-1 lg:order-none lg:w-auto" aria-label="Navigasi dashboard">
                @foreach ($menuAdmin as $item)
                    @php $aktif = request()->routeIs(Str::before($item['route'], '.index') . '*'); @endphp
                    <a href="{{ route($item['route']) }}"
                        class="rounded-lg px-3.5 py-2 text-sm transition {{ $aktif ? 'bg-neon-500 font-semibold text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}"
                        @if ($aktif) aria-current="page" @endif>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex items-center gap-3 text-sm">
                <span class="hidden text-right leading-tight sm:block">
                    <span class="block font-semibold">{{ auth()->user()->name }}</span>
                    <span class="block text-[10px] uppercase tracking-[0.16em] text-white/50">{{ auth()->user()->role }}</span>
                </span>
                <a href="{{ route('home') }}" class="hidden rounded-lg border border-white/25 px-3 py-2 text-white/70 transition hover:text-white lg:block">
                    Lihat situs
                </a>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-lg border border-white/25 px-3 py-2 transition hover:bg-white/10">Keluar</button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-[1600px] px-5 py-8 lg:px-8">
        {{-- Pesan hasil aksi --}}
        @if (session('status'))
            <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-3.5 text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-3.5 text-red-700">
                <p class="font-semibold">Periksa lagi isiannya:</p>
                <ul class="mt-1 list-inside list-disc text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

</body>

</html>
