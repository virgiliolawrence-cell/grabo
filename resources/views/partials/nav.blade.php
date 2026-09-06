@php
    // Sorotan tab mengikuti halaman yang dibuka, bukan posisi scroll.
    // Di beranda tautan Kontak memakai jangkar dalam halaman.
    $isHome = request()->routeIs('home');

    $navActive = match (true) {
        request()->routeIs('home') => 'home',
        // Halaman deskripsi produk ikut menyalakan tab Menu.
        request()->routeIs('menu', 'menu.show') => 'menu',
        request()->routeIs('promo') => 'promo',
        default => null,
    };

    $navItems = [
        ['label' => 'Home', 'target' => 'home', 'href' => $isHome ? '#home' : route('home') . '#home'],
        ['label' => 'Menu', 'target' => 'menu', 'href' => route('menu')],
        ['label' => 'Promo', 'target' => 'promo', 'href' => route('promo')],
        ['label' => 'Kontak', 'target' => 'kontak', 'href' => $isHome ? '#kontak' : route('home') . '#kontak'],
    ];
@endphp

    {{-- Utility bar --}}
    <div class="bg-stone-950 text-white/65">
        <div class="mx-auto grid max-w-7xl grid-cols-2 items-center gap-4 px-6 py-3 text-[13px] lg:grid-cols-3">
            <div class="flex items-center gap-6">
                {{-- Pilih stan: menyaring halaman menu lewat ?stan= --}}
                <div class="relative" data-dropdown>
                    <button type="button" data-dropdown-toggle aria-expanded="false" aria-haspopup="true"
                        class="flex items-center gap-2 transition hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                            <path d="M12 21s-7-6.1-7-11a7 7 0 1 1 14 0c0 4.9-7 11-7 11Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round" />
                            <circle cx="12" cy="10" r="2.3" stroke="currentColor" stroke-width="1.6" />
                        </svg>
                        {{ request('stan') ?: 'Cari stan kantin' }}
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-3 w-3 transition-transform" data-dropdown-caret aria-hidden="true">
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div data-dropdown-panel hidden
                        class="absolute left-0 top-full z-[60] mt-2 w-60 overflow-hidden rounded-2xl bg-white py-2 text-stone-700 shadow-2xl ring-1 ring-stone-900/10">
                        <p class="px-4 pb-2 pt-1 text-[10px] uppercase tracking-[0.18em] text-stone-400">Stan yang buka</p>
                        <a href="{{ route('menu') }}"
                            class="block px-4 py-2 transition hover:bg-neon-50 hover:text-neon-700 {{ request('stan') ? '' : 'font-semibold text-neon-700' }}">
                            Semua stan
                        </a>
                        @foreach ($daftarStan as $stall)
                            <a href="{{ route('menu', ['stan' => $stall]) }}"
                                class="block px-4 py-2 transition hover:bg-neon-50 hover:text-neon-700 {{ request('stan') === $stall ? 'font-semibold text-neon-700' : '' }}">
                                {{ $stall }}
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Bahasa: baru ada satu, jadi daftarnya apa adanya. --}}
                <div class="relative hidden sm:block" data-dropdown>
                    <button type="button" data-dropdown-toggle aria-expanded="false" aria-haspopup="true"
                        class="flex items-center gap-2 transition hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                            <circle cx="12" cy="12" r="8.5" stroke="currentColor" stroke-width="1.6" />
                            <path d="M3.5 12h17M12 3.5c4 4.6 4 12.4 0 17-4-4.6-4-12.4 0-17Z" stroke="currentColor" stroke-width="1.6" />
                        </svg>
                        ID
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-3 w-3 transition-transform" data-dropdown-caret aria-hidden="true">
                            <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </button>

                    <div data-dropdown-panel hidden
                        class="absolute left-0 top-full z-[60] mt-2 w-56 overflow-hidden rounded-2xl bg-white py-2 text-stone-700 shadow-2xl ring-1 ring-stone-900/10">
                        <p class="flex items-center justify-between px-4 py-2 font-semibold text-neon-700">
                            Bahasa Indonesia
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                                <path d="M5 12.5l4.5 4.5L19 7.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </p>
                        <p class="px-4 py-2 text-stone-400">English &mdash; belum tersedia</p>
                    </div>
                </div>
            </div>

            <p class="hidden text-center font-semibold text-white lg:block">grabo.sch.id</p>

            <div class="flex items-center justify-end gap-5">
                {{-- Alamat akunnya diatur di config/grabo.php; yang kosong tidak dirender. --}}
                @if ($ig = config('grabo.sosial.instagram'))
                    <a href="{{ $ig }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram Grabo" class="transition hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                            <rect x="3" y="3" width="18" height="18" rx="5" stroke="currentColor" stroke-width="1.7" />
                            <circle cx="12" cy="12" r="4" stroke="currentColor" stroke-width="1.7" />
                            <circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" />
                        </svg>
                    </a>
                @endif
                @if ($tiktok = config('grabo.sosial.tiktok'))
                    <a href="{{ $tiktok }}" target="_blank" rel="noopener noreferrer" aria-label="TikTok Grabo" class="hidden transition hover:text-white sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                            <path d="M14 3h2.6c.3 1.9 1.4 3.4 3.4 3.7v2.6c-1.3.1-2.5-.2-3.6-.9v5.9a5.6 5.6 0 1 1-5.6-5.6c.3 0 .6 0 .9.1v2.7a2.9 2.9 0 1 0 2 2.8V3Z" />
                        </svg>
                    </a>
                @endif
                @if ($fb = config('grabo.sosial.facebook'))
                    <a href="{{ $fb }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook Grabo" class="hidden transition hover:text-white sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4" aria-hidden="true">
                            <path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5H16.7V3.6c-.29-.04-1.3-.13-2.48-.13-2.45 0-4.13 1.5-4.13 4.25V9.9H7.4V13h2.69v8h3.41Z" />
                        </svg>
                    </a>
                @endif
                {{-- Identitas pemakai + tombol keluar --}}
                @if (session('grabo_user'))
                    <span class="hidden max-w-[220px] truncate lg:inline" title="{{ session('grabo_user') }}">
                        {{ session('grabo_user') }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="underline-offset-2 transition hover:text-white hover:underline">
                            Keluar
                        </button>
                    </form>
                @else
                    <span class="hidden lg:inline">Gratis ongkir antar kelas</span>
                @endif
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <header class="site-header sticky top-0 z-50 rounded-b-[1.75rem] text-white">
        <div class="header-inner relative mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:gap-8">
            {{-- Merek --}}
            <a href="{{ route('home') }}" class="group flex shrink-0 items-center gap-3 rounded-2xl bg-white px-3 py-2 shadow-lg shadow-neon-900/25 ring-1 ring-white/60 transition hover:shadow-xl">
                <img src="{{ $graboLogo }}" alt="Grabo &mdash; kantin sekolah digital" class="h-12 w-auto sm:h-14" width="54" height="56">
                {{-- Wordmark sudah ada di dalam logo, jadi di sini cukup deskriptornya. --}}
                <span class="hidden border-l border-stone-200 pl-3 text-[10px] uppercase leading-snug tracking-[0.2em] text-neon-700 sm:block">
                    Kantin<br>Digital
                </span>
            </a>

            {{-- Tautan di dalam kapsul kaca --}}
            <nav class="nav-capsule absolute left-1/2 hidden -translate-x-1/2 items-center gap-1 rounded-full p-1.5 backdrop-blur-md backdrop-saturate-150 lg:flex" aria-label="Navigasi utama">
                <span class="nav-indicator" aria-hidden="true"></span>
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}" data-nav-target="{{ $item['target'] }}"
                        class="nav-link {{ $navActive === $item['target'] ? 'is-active' : '' }}"
                        @if ($navActive === $item['target']) aria-current="true" @endif>{{ $item['label'] }}</a>
                @endforeach
            </nav>

            <div class="flex shrink-0 items-center gap-2 sm:gap-3">
                <button type="button" id="cartToggle" aria-label="Buka keranjang belanja" aria-expanded="false" aria-controls="cartPanel"
                    class="relative flex h-11 w-11 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white backdrop-blur transition hover:bg-white/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <path d="M3 4h2l2.4 12.4a2 2 0 0 0 2 1.6h7.2a2 2 0 0 0 2-1.6L21 8H6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                        <circle cx="9.5" cy="20" r="1.4" fill="currentColor" />
                        <circle cx="17" cy="20" r="1.4" fill="currentColor" />
                    </svg>
                    <span id="cartCount" data-cart-count aria-live="polite"
                        class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-white text-[10px] font-bold text-neon-700 shadow">0</span>
                </button>

                <a href="{{ route('menu') }}" class="hidden items-center gap-2.5 rounded-full bg-white px-6 py-3 font-semibold text-neon-700 shadow-lg shadow-neon-900/25 transition hover:-translate-y-0.5 hover:bg-neon-50 hover:shadow-xl sm:inline-flex">
                    Pesan Sekarang
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                        <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>

                <button type="button" id="menuToggle" aria-controls="mobileMenu" aria-expanded="false" aria-label="Buka menu navigasi"
                    class="flex h-11 w-11 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white backdrop-blur transition hover:bg-white/20 lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <path d="M4 7h16M4 12h16M4 17h16" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="hidden px-4 pb-5 sm:px-6 lg:hidden">
            <div class="nav-capsule space-y-1 rounded-2xl p-2 backdrop-blur-md backdrop-saturate-150">
                @foreach ($navItems as $item)
                    <a href="{{ $item['href'] }}" data-nav-target="{{ $item['target'] }}"
                        class="nav-link-mobile {{ $navActive === $item['target'] ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                @endforeach
                <a href="{{ route('menu') }}" class="mt-1 block rounded-2xl bg-white px-4 py-3 text-center font-semibold text-neon-700 sm:hidden">Pesan Sekarang</a>
            </div>
        </div>
    </header>
