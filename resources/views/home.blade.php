@extends('layouts.grabo')

@section('content')
        {{-- Hero poster --}}
        <section id="home" class="relative isolate scroll-mt-28 overflow-hidden bg-cream pb-16 pt-10 lg:pb-24 lg:pt-14">
            {{-- Ilustrasi yang melayang (dekoratif) --}}
            <img src="{{ asset('images/food/es-teh.svg') }}" alt="" aria-hidden="true"
                class="pointer-events-none absolute -left-16 top-24 z-0 w-52 rotate-[-14deg] opacity-80 blur-[3px] lg:w-72" width="288" height="288">
            <img src="{{ asset('images/food/mie-ayam.svg') }}" alt="" aria-hidden="true"
                class="pointer-events-none absolute -right-20 top-8 z-0 hidden w-72 rotate-[10deg] opacity-80 blur-[4px] lg:block" width="288" height="288">
            <img src="{{ asset('images/food/roti-bakar.svg') }}" alt="" aria-hidden="true"
                class="pointer-events-none absolute -left-14 bottom-2 z-0 hidden w-40 rotate-[8deg] opacity-90 blur-[1px] xl:block" width="160" height="160">

            <div class="relative mx-auto max-w-7xl px-6">
                {{-- Judul raksasa --}}
                <h1 class="relative z-10 headline text-stone-900">
                    <span class="block text-[clamp(1.6rem,4.4vw,3.6rem)] leading-[1.05] text-stone-400">Pesan sebelum bel</span>
                    <span class="mt-2 flex items-baseline gap-4 text-[clamp(3.1rem,13vw,11.5rem)] lg:mt-1 lg:justify-between">
                        <span>Makan</span>
                        <span class="text-neon-500 [text-shadow:0_0_45px_rgba(255,106,0,0.35)]">Duluan</span>
                    </span>
                </h1>

                {{-- Produk utama, ditumpuk di antara kedua kata --}}
                <img src="{{ asset('images/food/nasi-goreng.svg') }}" alt="Ilustrasi sepiring nasi goreng spesial dengan telur mata sapi"
                    class="animate-float relative z-20 mx-auto -mt-6 w-64 drop-shadow-2xl sm:w-80 lg:absolute lg:left-1/2 lg:top-8 lg:mt-0 lg:w-[38vw] lg:max-w-[480px] lg:-translate-x-1/2"
                    width="480" height="480">

                {{-- Baris bawah: deskripsi + kartu snack --}}
                <div class="relative z-30 mt-10 grid items-end gap-12 lg:mt-6 lg:grid-cols-2">
                    <div class="max-w-md">
                        <p class="text-lg leading-relaxed text-stone-600">
                            Grabo mengumpulkan seluruh stan kantin dalam satu halaman. Pilih menu dari kelas,
                            bayar dengan saldo pelajar, lalu ambil pesananmu begitu bel istirahat berbunyi
                            &mdash; <span class="font-semibold text-neon-700">tanpa antre.</span>
                        </p>

                        <a href="#menu" class="mt-8 inline-flex items-center gap-3 rounded-full border-2 border-neon-500 px-8 py-3.5 text-lg font-semibold text-neon-700 shadow-[0_0_0_rgba(255,106,0,0)] transition hover:bg-neon-500 hover:text-white hover:shadow-[0_0_32px_rgba(255,106,0,0.55)]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                <path d="M3 4h2l2.4 12.4a2 2 0 0 0 2 1.6h7.2a2 2 0 0 0 2-1.6L21 8H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="9.5" cy="20" r="1.4" fill="currentColor" />
                                <circle cx="17" cy="20" r="1.4" fill="currentColor" />
                            </svg>
                            Pesan Sekarang
                        </a>
                    </div>

                    <div class="lg:justify-self-end">
                        <a href="#menu" class="group block w-60">
                            <span class="block overflow-hidden rounded-2xl shadow-lg shadow-neon-900/10 transition group-hover:-translate-y-1">
                                <img src="{{ asset('images/food/photos/batagor.jpg') }}" alt="Batagor dengan saus kacang" class="h-40 w-full object-cover" width="240" height="160">
                            </span>
                            <span class="mt-4 block border-b-2 border-neon-500 pb-1 text-center text-lg text-stone-800">
                                Jelajahi Menu Snack
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Statistik --}}
        <section class="bg-stone-900 text-white">
            <div class="mx-auto grid max-w-7xl grid-cols-1 divide-y divide-white/15 px-6 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                @php
                    $stats = [
                        ['value' => '500+', 'label' => 'Siswa terdaftar'],
                        ['value' => '30', 'label' => 'Stan kantin'],
                        ['value' => '0 Menit', 'label' => 'Waktu antre'],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="flex items-baseline gap-4 py-8 sm:justify-center">
                        <span class="headline text-4xl text-neon-500 [text-shadow:0_0_22px_rgba(255,106,0,0.55)] sm:text-5xl">{{ $stat['value'] }}</span>
                        <span class="text-[11px] uppercase tracking-[0.2em] text-white/60">{{ $stat['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Keunggulan --}}
        <section class="bg-white">
            <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 py-14 sm:grid-cols-3">
                @php
                    $values = [
                        ['title' => 'Pesan dari mana saja', 'text' => 'Dari kelas, perpustakaan, atau lapangan &mdash; cukup lewat ponsel.'],
                        ['title' => 'Pembayaran non-tunai', 'text' => 'Terhubung dengan saldo kartu pelajar, tanpa repot uang kembalian.'],
                        ['title' => 'Notifikasi siap ambil', 'text' => 'Datang ke loket hanya ketika pesananmu benar-benar sudah siap.'],
                    ];
                @endphp
                @foreach ($values as $i => $value)
                    <div class="flex gap-4">
                        <span class="headline text-3xl text-neon-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3 class="text-lg font-semibold text-stone-900">{{ $value['title'] }}</h3>
                            <p class="mt-1 leading-relaxed text-stone-500">{!! $value['text'] !!}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Popular Menu --}}
        <section id="menu" class="scroll-mt-28 bg-cream">
            <div class="mx-auto max-w-7xl px-6 py-20 lg:py-24">
                <div class="flex flex-col gap-6 border-b-2 border-stone-900/10 pb-8 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <span class="text-[11px] uppercase tracking-[0.24em] text-neon-800">Popular Menu</span>
                        <h2 class="mt-3 headline text-[clamp(2.5rem,7vw,5.5rem)] text-stone-900">Paling Dicari</h2>
                    </div>
                    <p class="max-w-sm leading-relaxed text-stone-500">
                        Menu favorit siswa dari stan-stan kantin sekolah, lengkap dengan harga dan ketersediaan hari ini.
                    </p>
                </div>

                @php
                    $menu = [
                        ['image' => 'photos/nasi-goreng.jpg', 'name' => 'Nasi Goreng Kampung', 'desc' => 'Nasi goreng kampung dengan kerupuk, sambal, dan lalapan segar.', 'price' => 'Rp 12.000', 'stall' => 'Stan Bu Rina', 'badge' => 'Best Seller'],
                        ['image' => 'photos/mie-ayam.jpg', 'name' => 'Mie Ayam Jamur', 'desc' => 'Mie ayam dengan tumisan jamur, ayam cincang, dan sawi hijau.', 'price' => 'Rp 10.000', 'stall' => 'Stan Pak Joko', 'badge' => null],
                        ['image' => 'photos/ayam-geprek.jpg', 'name' => 'Ayam Geprek', 'desc' => 'Ayam crispy diulek bersama sambal bawang, disajikan dengan nasi hangat.', 'price' => 'Rp 13.000', 'stall' => 'Stan Dapur Mama', 'badge' => 'Best Seller'],
                        ['image' => 'photos/batagor.jpg', 'name' => 'Batagor Saus Kacang', 'desc' => 'Batagor goreng renyah dengan saus kacang dan perasan jeruk limau.', 'price' => 'Rp 9.000', 'stall' => 'Stan Kang Asep', 'badge' => null],
                        ['image' => 'photos/roti-bakar.jpg', 'name' => 'Roti Bakar Mentega', 'desc' => 'Roti panggang mentega, renyah di luar dan lembut di dalamnya.', 'price' => 'Rp 8.000', 'stall' => 'Stan Snack Corner', 'badge' => 'Menu Baru'],
                        ['image' => 'photos/es-teh.jpg', 'name' => 'Es Teh Manis', 'desc' => 'Teh seduh dingin dengan es batu, menyegarkan setelah jam pelajaran.', 'price' => 'Rp 4.000', 'stall' => 'Stan Minuman', 'badge' => null],
                    ];
                @endphp

                <div class="mt-12 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($menu as $item)
                        <article class="group flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-neon-900/10">
                            <div class="relative h-56 overflow-hidden">
                                <img src="{{ asset('images/food/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                    class="h-full w-full object-cover transition duration-500 group-hover:scale-110" width="600" height="450" loading="lazy">
                                @if ($item['badge'])
                                    <span class="absolute left-4 top-4 rounded-full bg-neon-500 px-3.5 py-1 text-[10px] uppercase tracking-[0.16em] text-white">{{ $item['badge'] }}</span>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-6">
                                <span class="text-[10px] uppercase tracking-[0.18em] text-stone-400">{{ $item['stall'] }}</span>
                                <h3 class="mt-2 headline text-2xl text-stone-900">{{ $item['name'] }}</h3>
                                <p class="mt-2 flex-1 leading-relaxed text-stone-500">{{ $item['desc'] }}</p>

                                <div class="mt-5 flex items-center justify-between border-t border-stone-100 pt-4">
                                    <span class="headline text-2xl text-neon-600">{{ $item['price'] }}</span>
                                    <button type="button"
                                        data-add-to-cart
                                        data-name="{{ $item['name'] }}"
                                        data-stall="{{ $item['stall'] }}"
                                        data-price="{{ (int) preg_replace('/\D/', '', $item['price']) }}"
                                        class="inline-flex items-center gap-2 rounded-full border-2 border-neon-500 px-5 py-2 text-sm font-semibold text-neon-800 transition hover:bg-neon-500 hover:text-white hover:shadow-[0_0_22px_rgba(255,106,0,0.45)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                                        </svg>
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-12 text-center">
                    <a href="#menu" class="inline-flex items-center gap-2 border-b-2 border-neon-500 pb-1 text-lg text-stone-800 transition hover:gap-3 hover:text-neon-700">
                        Lihat seluruh menu kantin
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- How It Works --}}
        <section id="how" class="scroll-mt-28 bg-white">
            <div class="mx-auto max-w-7xl px-6 py-20 lg:py-24">
                <div class="mx-auto max-w-3xl text-center">
                    <span class="text-[11px] uppercase tracking-[0.24em] text-neon-800">How It Works</span>
                    <h2 class="mt-3 headline text-[clamp(2.5rem,7vw,5.5rem)] text-stone-900">Tiga Langkah Saja</h2>
                    <p class="mt-4 text-lg leading-relaxed text-stone-500">
                        Dari memilih menu sampai makanan ada di tangan, semuanya selesai sebelum jam istirahat habis.
                    </p>
                </div>

                @php
                    $steps = [
                        ['title' => 'Browse Menu', 'text' => 'Telusuri menu dari seluruh stan kantin, lengkap dengan harga dan sisa porsi hari ini.'],
                        ['title' => 'Place Order', 'text' => 'Masukkan pilihanmu ke keranjang, bayar dengan saldo pelajar, lalu pesanan diteruskan ke stan.'],
                        ['title' => 'Pick Up Food', 'text' => 'Tunggu notifikasi siap diambil, tunjukkan kode pesanan di loket, dan makanan langsung diserahkan.'],
                    ];
                @endphp

                <ol class="relative mt-16 grid grid-cols-1 gap-12 md:grid-cols-3 md:gap-8">
                    <div class="pointer-events-none absolute left-[16.6%] right-[16.6%] top-11 hidden border-t-2 border-dashed border-neon-300 md:block" aria-hidden="true"></div>

                    @foreach ($steps as $i => $step)
                        <li class="relative flex flex-col items-center text-center">
                            <span class="relative z-10 flex h-22 w-22 items-center justify-center rounded-full bg-neon-500 headline text-4xl text-white shadow-[0_0_30px_rgba(255,106,0,0.5)]">
                                {{ $i + 1 }}
                            </span>
                            <h3 class="mt-6 headline text-3xl text-stone-900">{{ $step['title'] }}</h3>
                            <p class="mt-3 max-w-xs leading-relaxed text-stone-500">{{ $step['text'] }}</p>

                            @if (! $loop->last)
                                <span class="mt-6 text-2xl text-neon-500 md:hidden" aria-hidden="true">&darr;</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        {{-- CTA --}}
        <section class="relative overflow-hidden bg-neon-500">
            <img src="{{ asset('images/food/ayam-geprek.svg') }}" alt="" aria-hidden="true"
                class="pointer-events-none absolute -left-10 -top-10 w-56 rotate-[-12deg] opacity-25 blur-[2px]" width="224" height="224">
            <img src="{{ asset('images/food/es-teh.svg') }}" alt="" aria-hidden="true"
                class="pointer-events-none absolute -bottom-14 right-4 w-56 rotate-[12deg] opacity-25 blur-[2px]" width="224" height="224">

            <div class="relative mx-auto flex max-w-7xl flex-col items-center gap-10 px-6 py-16 text-center lg:flex-row lg:justify-between lg:py-20 lg:text-left">
                <div class="max-w-2xl">
                    <h2 class="headline text-[clamp(2.25rem,6vw,4.5rem)] leading-[0.9] text-white">Sudah waktunya istirahat?</h2>
                    <p class="mt-6 text-lg leading-relaxed text-orange-50">
                        Masuk dengan akun sekolahmu, susun pesanan sekarang, dan biarkan makanan sudah menunggu di loket.
                    </p>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <a href="#menu" class="inline-flex items-center gap-2.5 rounded-full bg-white px-8 py-4 text-lg font-semibold text-neon-700 shadow-lg shadow-neon-900/20 transition hover:-translate-y-0.5">
                        Order Now
                    </a>
                    <a href="#kontak" class="inline-flex items-center gap-2 rounded-full border-2 border-white/70 px-7 py-4 text-lg text-white transition hover:bg-white/10">
                        Login
                    </a>
                </div>
            </div>
        </section>
@endsection
