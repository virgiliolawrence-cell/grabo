@extends('layouts.grabo')

@section('title', 'Menu Kantin — Grabo')
@section('description', 'Telusuri seluruh menu kantin sekolah: makanan berat, gorengan dan snack, hingga minuman. Pesan dari kelas, ambil tanpa antre.')

@section('content')

    {{-- Carousel promo --}}
    <section class="bg-cream px-4 pt-6 sm:px-6 lg:pt-10">
        <div class="relative mx-auto max-w-7xl">
            <div id="promoTrack" class="no-scrollbar flex snap-x snap-mandatory overflow-x-auto rounded-[1.75rem]">
                @foreach ($promos as $promo)
                    <article class="relative flex min-w-full snap-center items-center overflow-hidden bg-gradient-to-br from-neon-800 via-neon-600 to-neon-700">
                        {{-- Padding samping ekstra supaya teks tidak tertimpa tombol panah. --}}
                        <div class="relative z-10 flex w-full flex-col gap-8 px-16 py-12 sm:px-20 lg:flex-row lg:items-center lg:justify-between lg:py-16">
                            <div class="max-w-lg text-white">
                                <span class="text-[11px] uppercase tracking-[0.24em] text-white/80">{{ $promo['eyebrow'] }}</span>
                                <h2 class="mt-3 headline text-[clamp(2rem,5vw,3.75rem)]">{{ $promo['title'] }}</h2>
                                <p class="mt-4 leading-relaxed text-white/85">{{ $promo['text'] }}</p>

                                <div class="mt-7 flex flex-wrap items-center gap-4">
                                    <a href="#makanan-berat" class="rounded-full bg-white px-7 py-3 font-semibold text-neon-700 shadow-lg transition hover:-translate-y-0.5">
                                        Lihat Menu
                                    </a>
                                    @if ($promo['price'])
                                        <span class="headline text-3xl text-white">{{ $promo['price'] }}</span>
                                    @endif
                                </div>
                            </div>

                            <img src="{{ asset($promo['image']) }}" alt="{{ $promo['alt'] }}"
                                class="h-44 w-44 rounded-full object-cover shadow-2xl ring-8 ring-white/25 sm:h-56 sm:w-56 lg:h-64 lg:w-64"
                                width="256" height="256" loading="lazy">
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Kontrol carousel --}}
            <button type="button" id="promoPrev" aria-label="Promo sebelumnya"
                class="absolute left-3 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/40 bg-white/15 text-white backdrop-blur transition hover:bg-white/30">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                    <path d="M15 5l-7 7 7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>
            <button type="button" id="promoNext" aria-label="Promo berikutnya"
                class="absolute right-3 top-1/2 z-20 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full border border-white/40 bg-white/15 text-white backdrop-blur transition hover:bg-white/30">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                    <path d="M9 5l7 7-7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </button>

            <div class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2">
                @foreach ($promos as $i => $promo)
                    <button type="button" data-promo-dot class="promo-dot {{ $i === 0 ? 'is-current' : '' }}"
                        aria-label="Ke promo {{ $i + 1 }}"></button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Kategori menu --}}
    @foreach ($categories as $category)
        <section id="{{ Str::slug($category['label']) }}" class="scroll-mt-28 bg-cream px-4 py-12 sm:px-6 lg:py-16">
            <div class="mx-auto max-w-7xl">
                <div class="flex flex-wrap items-end justify-between gap-4 border-b-2 border-stone-900/10 pb-5">
                    <div>
                        <h2 class="headline text-[clamp(1.6rem,3.5vw,2.5rem)] text-stone-900">{{ $category['label'] }}</h2>
                        <p class="mt-2 text-stone-500">{{ $category['note'] }}</p>
                    </div>
                    <span class="text-[11px] uppercase tracking-[0.2em] text-neon-800">{{ count($category['items']) }} menu</span>
                </div>

                <div class="mt-8 grid grid-cols-2 gap-5 lg:grid-cols-4 lg:gap-7">
                    @foreach ($category['items'] as $item)
                        <article class="group relative flex flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-neon-900/10">
                            {{-- Seluruh kartu menuju halaman deskripsi produk. --}}
                            <a href="{{ route('menu.show', $item['slug']) }}" class="absolute inset-0 z-10"
                                aria-label="Lihat detail {{ $item['name'] }}"></a>

                            <div class="relative h-40 overflow-hidden bg-neon-50 sm:h-48">
                                @if ($item['image'])
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}"
                                        class="h-full w-full transition duration-500 group-hover:scale-110 {{ $item['photo'] ? 'object-cover' : 'object-contain p-4' }}"
                                        width="400" height="300" loading="lazy">
                                @else
                                    {{-- Belum ada foto untuk menu ini. --}}
                                    <span class="flex h-full w-full items-center justify-center text-center text-[11px] uppercase tracking-[0.18em] text-neon-800/70">
                                        Foto menyusul
                                    </span>
                                @endif

                                @if ($item['badge'])
                                    <span class="absolute left-3 top-3 rounded-full bg-neon-500 px-3 py-1 text-[10px] uppercase tracking-[0.14em] text-white">{{ $item['badge'] }}</span>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-4 sm:p-5">
                                <span class="text-[10px] uppercase tracking-[0.18em] text-stone-400">{{ $item['stall'] }}</span>
                                <h3 class="mt-1.5 headline text-lg text-stone-900 sm:text-xl">{{ $item['name'] }}</h3>

                                {{-- Varian dipilih di halaman detail, jadi kartunya tanpa tombol cepat. --}}
                                <div class="mt-4 flex items-center justify-between gap-2 border-t border-stone-100 pt-3">
                                    <span class="headline text-lg text-neon-600 sm:text-xl">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
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
    @endforeach

    {{-- Ajakan kembali ke beranda --}}
    <section class="bg-cream px-4 pb-16 sm:px-6">
        <div class="mx-auto flex max-w-7xl flex-col items-center gap-6 rounded-[1.75rem] bg-stone-900 px-8 py-12 text-center lg:flex-row lg:justify-between lg:text-left">
            <div>
                <h2 class="headline text-[clamp(1.75rem,4vw,2.75rem)] text-white">Sudah menemukan menumu?</h2>
                <p class="mt-3 text-white/70">Masuk dengan akun sekolah, bayar dengan saldo pelajar, lalu tinggal ambil di loket.</p>
            </div>
            <a href="{{ route('home') }}#how" class="shrink-0 rounded-full bg-neon-500 px-8 py-4 font-semibold text-white shadow-[0_0_30px_rgba(255,106,0,0.45)] transition hover:-translate-y-0.5 hover:bg-neon-600">
                Lihat Cara Pesan
            </a>
        </div>
    </section>
@endsection
