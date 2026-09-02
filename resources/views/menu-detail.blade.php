@extends('layouts.grabo')

@section('title', $item['name'] . ' — Grabo')
@section('description', $item['summary'])

@section('content')
    @php
        // Bintang penuh digambar sekali, lalu ditumpuk lapisan oranye selebar nilainya.
        $ratingPercent = round(($item['rating'] / 5) * 100);
        $gallery = $item['gallery'];
        $mainImage = $gallery[0] ?? null;
    @endphp

    <section class="bg-cream px-4 py-8 sm:px-6 lg:py-12">
        <div class="mx-auto max-w-7xl">

            {{-- Remah roti --}}
            <nav aria-label="Remah roti" class="flex flex-wrap items-center gap-2 text-sm text-stone-500">
                <a href="{{ route('home') }}" class="transition hover:text-neon-700">Beranda</a>
                <span aria-hidden="true" class="text-stone-300">/</span>
                <a href="{{ route('menu') }}" class="transition hover:text-neon-700">Menu</a>
                <span aria-hidden="true" class="text-stone-300">/</span>
                <a href="{{ route('menu') }}#{{ Str::slug($category['label']) }}" class="transition hover:text-neon-700">{{ $category['label'] }}</a>
                <span aria-hidden="true" class="text-stone-300">/</span>
                <span class="text-stone-800">{{ $item['name'] }}</span>
            </nav>

            {{--
                Akar halaman detail. Seluruh data produk dititipkan di sini supaya
                partials/scripts.blade.php bisa menghitung harga varian tanpa
                perlu menebak isi halaman.
            --}}
            <div id="productDetail"
                class="mt-6 grid grid-cols-1 gap-8 lg:grid-cols-[1.05fr_1fr] lg:items-start lg:gap-12"
                data-checkout-url="{{ route('checkout') }}"
                data-edit="{{ request()->integer('ubah', -1) }}"
                data-product="{{ json_encode([
                    'slug' => $item['slug'],
                    'name' => $item['name'],
                    'stall' => $item['stall'],
                    'price' => $item['price'],
                    'image' => $item['image'] ? asset($item['image']) : null,
                    'photo' => $item['photo'],
                    'type' => $item['type'],
                ]) }}">

                {{-- Kolom kiri: galeri --}}
                <div class="lg:sticky lg:top-28">
                    <figure class="relative overflow-hidden rounded-[1.5rem] bg-neon-50 shadow-sm">
                        @if ($mainImage)
                            <img id="detailMainImage" src="{{ asset($mainImage['src']) }}" alt="{{ $item['name'] }}"
                                class="aspect-[4/3] w-full {{ $mainImage['photo'] ? 'object-cover' : 'object-contain p-10' }}"
                                width="900" height="675">
                        @else
                            {{-- Menu ini belum punya foto sama sekali. --}}
                            <div class="flex aspect-[4/3] w-full items-center justify-center px-8 text-center">
                                <span class="text-[11px] uppercase tracking-[0.18em] text-neon-800/70">Foto menyusul</span>
                            </div>
                        @endif

                        @if ($item['badge'])
                            <span class="absolute left-5 top-5 rounded-full bg-neon-500 px-4 py-1.5 text-[10px] uppercase tracking-[0.14em] text-white">
                                {{ $item['badge'] }}
                            </span>
                        @endif

                        @if ($mainImage)
                            <figcaption id="detailCaption"
                                class="absolute bottom-5 left-1/2 -translate-x-1/2 rounded-full bg-white/90 px-4 py-1.5 text-xs text-stone-600 backdrop-blur">
                                {{ $mainImage['label'] }}
                            </figcaption>
                        @endif
                    </figure>

                    {{-- Thumbnail hanya berguna kalau gambarnya memang lebih dari satu. --}}
                    @if (count($gallery) > 1)
                        <div class="mt-4 flex gap-3">
                            @foreach ($gallery as $i => $shot)
                                <button type="button" data-gallery="{{ $i }}"
                                    data-src="{{ asset($shot['src']) }}"
                                    data-photo="{{ $shot['photo'] ? '1' : '0' }}"
                                    data-label="{{ $shot['label'] }}"
                                    aria-label="Lihat {{ $shot['label'] }}"
                                    aria-current="{{ $i === 0 ? 'true' : 'false' }}"
                                    class="gallery-thumb h-20 w-24 shrink-0 overflow-hidden rounded-xl border-2 border-stone-200 bg-neon-50 transition hover:-translate-y-0.5">
                                    <img src="{{ asset($shot['src']) }}" alt=""
                                        class="h-full w-full {{ $shot['photo'] ? 'object-cover' : 'object-contain p-2' }}"
                                        width="120" height="90" loading="lazy">
                                </button>
                            @endforeach
                        </div>
                    @endif

                    {{-- Tiga hal yang paling sering ditanyakan sebelum memesan --}}
                    <ul class="mt-5 grid gap-2 text-sm text-stone-600 sm:grid-cols-3">
                        <li class="flex items-center gap-2 rounded-xl bg-white px-3 py-2.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0 text-neon-600" aria-hidden="true">
                                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                                <path d="M12 7v5l3.5 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Siap {{ $item['ready'] }}
                        </li>
                        <li class="flex items-center gap-2 rounded-xl bg-white px-3 py-2.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0 text-neon-600" aria-hidden="true">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <path d="m3.3 7 8.7 5 8.7-5M12 22V12" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            </svg>
                            Diambil di loket
                        </li>
                        <li class="flex items-center gap-2 rounded-xl bg-white px-3 py-2.5 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4 shrink-0 text-neon-600" aria-hidden="true">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <path d="m9 12 2 2 4-4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Dimasak hari ini
                        </li>
                    </ul>
                </div>

                {{-- Kolom kanan: keterangan dan aksi --}}
                <div>
                    <p class="text-[11px] uppercase tracking-[0.18em] text-stone-400">{{ $item['stall'] }}</p>
                    <h1 class="mt-2 headline text-[clamp(2rem,5vw,3.25rem)] text-stone-900">{{ $item['name'] }}</h1>

                    {{-- Rating --}}
                    <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-1.5 text-sm text-stone-500">
                        <span class="relative inline-flex" role="img"
                            aria-label="Rating {{ number_format($item['rating'], 1, ',', '.') }} dari 5">
                            <span class="flex gap-0.5 text-stone-200" aria-hidden="true">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 shrink-0" fill="currentColor">
                                        <path d="M12 2.6l2.85 5.77 6.37.93-4.61 4.49 1.09 6.34L12 17.14l-5.7 3 1.09-6.35L2.78 9.3l6.37-.93z" />
                                    </svg>
                                @endfor
                            </span>
                            <span class="absolute inset-y-0 left-0 overflow-hidden text-neon-500"
                                style="width: {{ $ratingPercent }}%" aria-hidden="true">
                                <span class="flex gap-0.5">
                                    @for ($i = 0; $i < 5; $i++)
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 shrink-0" fill="currentColor">
                                            <path d="M12 2.6l2.85 5.77 6.37.93-4.61 4.49 1.09 6.34L12 17.14l-5.7 3 1.09-6.35L2.78 9.3l6.37-.93z" />
                                        </svg>
                                    @endfor
                                </span>
                            </span>
                        </span>
                        <span class="font-semibold text-stone-800">{{ number_format($item['rating'], 1, ',', '.') }}</span>
                        <span aria-hidden="true" class="text-stone-300">&middot;</span>
                        <span>{{ number_format($item['reviews'], 0, ',', '.') }} ulasan</span>
                        <span aria-hidden="true" class="text-stone-300">&middot;</span>
                        <span>{{ number_format($item['sold'], 0, ',', '.') }} terjual</span>
                    </div>

                    <p class="mt-5 headline text-[clamp(1.9rem,4vw,2.5rem)] text-neon-600">
                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                    </p>

                    <p class="mt-4 max-w-prose leading-relaxed text-stone-600">{{ $item['description'] }}</p>

                    {{-- Varian: chip-nya dibuat di partials/scripts.blade.php sesuai jenis menu. --}}
                    <div id="detailOptions" class="mt-7 space-y-5"></div>

                    {{-- Catatan untuk stan --}}
                    <div class="mt-6">
                        <label for="detailNote" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">
                            Catatan tambahan
                        </label>
                        <textarea id="detailNote" rows="2" maxlength="120"
                            placeholder="Contoh: sambalnya dipisah"
                            class="mt-2 w-full resize-none rounded-xl border border-stone-200 bg-white px-4 py-3 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:ring-4 focus:ring-neon-500/20"></textarea>
                    </div>

                    {{-- Jumlah dan subtotal --}}
                    <div class="mt-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl bg-white px-5 py-4 shadow-sm">
                        <div class="flex items-center gap-3">
                            <span class="text-[11px] uppercase tracking-[0.18em] text-stone-500">Jumlah</span>
                            <div class="flex items-center gap-2">
                                <button type="button" id="detailQtyDown" aria-label="Kurangi jumlah"
                                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-xl text-stone-600 transition hover:bg-stone-100 disabled:cursor-not-allowed disabled:opacity-40">&minus;</button>
                                <span id="detailQty" aria-live="polite" class="w-8 text-center text-lg font-semibold text-stone-900">1</span>
                                <button type="button" id="detailQtyUp" aria-label="Tambah jumlah"
                                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-xl text-stone-600 transition hover:bg-stone-100">+</button>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Subtotal</span>
                            <span id="detailSubtotal" class="headline text-2xl text-neon-600">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Aksi utama --}}
                    <div class="mt-5 grid gap-3 sm:grid-cols-2">
                        <button type="button" id="detailBuy"
                            class="rounded-full bg-neon-500 px-6 py-4 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.45)] transition hover:-translate-y-0.5 hover:bg-neon-600">
                            Beli Sekarang
                        </button>
                        <button type="button" id="detailAdd"
                            class="rounded-full border-2 border-stone-300 px-6 py-4 font-semibold text-stone-800 transition hover:border-stone-800 hover:bg-white">
                            Tambah ke Keranjang
                        </button>
                    </div>

                    <p class="mt-3 text-sm text-stone-400">
                        &ldquo;Beli Sekarang&rdquo; memasukkan pesanan ini ke keranjang lalu langsung membuka halaman pembayaran.
                    </p>

                    {{-- Spesifikasi --}}
                    <section class="mt-8 rounded-2xl bg-white p-6 shadow-sm sm:p-7">
                        <h2 class="headline text-xl text-stone-900">Spesifikasi</h2>
                        <dl class="mt-4 space-y-3">
                            @foreach ($item['specs'] as $label => $value)
                                <div class="flex justify-between gap-6 border-b border-stone-100 pb-3 last:border-0 last:pb-0">
                                    <dt class="text-stone-500">{{ $label }}</dt>
                                    <dd class="text-right font-semibold text-stone-900">{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    </section>
                </div>
            </div>
        </div>
    </section>

    {{-- Menu lain dari kategori yang sama --}}
    @if (count($related) > 0)
        <section class="bg-cream px-4 pb-16 sm:px-6">
            <div class="mx-auto max-w-7xl">
                <div class="flex flex-wrap items-end justify-between gap-4 border-b-2 border-stone-900/10 pb-5">
                    <h2 class="headline text-[clamp(1.5rem,3vw,2.25rem)] text-stone-900">Masih di {{ $category['label'] }}</h2>
                    <a href="{{ route('menu') }}" class="text-sm text-stone-500 underline-offset-4 transition hover:text-neon-700 hover:underline">
                        Lihat seluruh menu
                    </a>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-5 lg:grid-cols-3 lg:gap-7">
                    @foreach ($related as $other)
                        <article class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-neon-900/10">
                            <a href="{{ route('menu.show', $other['slug']) }}" class="absolute inset-0 z-10"
                                aria-label="Lihat detail {{ $other['name'] }}"></a>

                            <div class="relative h-40 overflow-hidden bg-neon-50 sm:h-44">
                                @if ($other['image'])
                                    <img src="{{ asset($other['image']) }}" alt="{{ $other['name'] }}"
                                        class="h-full w-full transition duration-500 group-hover:scale-110 {{ $other['photo'] ? 'object-cover' : 'object-contain p-4' }}"
                                        width="400" height="300" loading="lazy">
                                @else
                                    <span class="flex h-full w-full items-center justify-center text-center text-[11px] uppercase tracking-[0.18em] text-neon-800/70">
                                        Foto menyusul
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-4 sm:p-5">
                                <span class="text-[10px] uppercase tracking-[0.18em] text-stone-400">{{ $other['stall'] }}</span>
                                <h3 class="mt-1.5 headline text-lg text-stone-900">{{ $other['name'] }}</h3>
                                <div class="mt-4 flex items-center justify-between gap-2 border-t border-stone-100 pt-3">
                                    <span class="headline text-lg text-neon-600">Rp {{ number_format($other['price'], 0, ',', '.') }}</span>
                                    <span class="inline-flex items-center gap-1 text-sm text-stone-400 transition group-hover:text-neon-700">
                                        Lihat
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                                            <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
