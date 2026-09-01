@extends('layouts.grabo')

@section('title', 'Promo Kantin — Grabo')
@section('description', 'Promo kantin sekolah bulan ini: paket hemat, diskon stan, dan penawaran minuman. Klaim langsung lewat Grabo.')

@section('content')
    @php
        $promoGroups = [
            [
                'label' => 'Paket Hemat',
                'title' => 'Nasi Goreng + Es Teh',
                'text' => 'Satu paket makan siang lengkap dari Stan Bu Rina. Lebih murah Rp 2.000 dibanding beli terpisah.',
                'price' => 'Rp 14.000',
                'was' => 'Rp 16.000',
                'code' => 'HEMAT14',
                'image' => 'images/food/photos/nasi-goreng.jpg',
                'alt' => 'Sepiring nasi goreng kampung lengkap dengan kerupuk',
                'badge' => 'Paling laris',
            ],
            [
                'label' => 'Beli 2 Gratis 1',
                'title' => 'Roti Bakar Coklat',
                'text' => 'Pesan dua roti bakar dari Stan Snack Corner, dapat satu gratis untuk teman sebangku.',
                'price' => 'Rp 18.000',
                'was' => 'Rp 27.000',
                'code' => 'ROTI21',
                'image' => 'images/food/photos/promo-roti-coklat.jpg',
                'alt' => 'Roti bakar isi coklat yang sudah dipanggang',
                'badge' => 'Menu baru',
            ],
            [
                'label' => 'Promo Minuman',
                'title' => 'Es Cendol Dingin',
                'text' => 'Diskon khusus jam istirahat kedua, selama persediaan di Stan Minuman masih ada.',
                'price' => 'Rp 5.000',
                'was' => 'Rp 6.000',
                'code' => 'SEGAR5',
                'image' => 'images/food/photos/promo-es-cendol.jpg',
                'alt' => 'Semangkuk es cendol dengan serutan es',
                'badge' => null,
            ],
        ];
    @endphp

    {{-- Strip pengumuman --}}
    <div class="bg-neon-50">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-center gap-x-6 gap-y-2 px-6 py-3.5 text-center text-sm text-neon-800">
            <span class="inline-flex items-center gap-2 font-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                    <path d="M12 3v18M5 7h9a3 3 0 0 1 0 6H5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Promo berlaku 1&ndash;30 September
            </span>
            <span class="text-neon-700/80">Tunjukkan kode promo di loket saat mengambil pesanan.</span>
        </div>
    </div>

    {{-- Judul halaman --}}
    <section class="bg-cream px-4 pt-12 sm:px-6 lg:pt-16">
        <div class="mx-auto max-w-7xl">
            <span class="text-[11px] uppercase tracking-[0.24em] text-neon-800">Promo Bulan Ini</span>
            <h1 class="mt-3 headline text-[clamp(2.2rem,5vw,3.75rem)] text-stone-900">Hemat Setiap Istirahat</h1>
            <p class="mt-4 max-w-2xl leading-relaxed text-stone-500">
                Tiga penawaran dari stan kantin sekolah, berlaku sepanjang bulan ini. Pesan lewat Grabo,
                sebutkan kode promonya saat mengambil pesanan, dan bayar dengan saldo pelajar seperti biasa.
            </p>
        </div>
    </section>

    {{-- Tiga kolom promo --}}
    <section class="bg-cream px-4 pb-16 pt-10 sm:px-6 lg:pb-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 md:grid-cols-3 lg:gap-10">
            @foreach ($promoGroups as $promo)
                <article class="flex flex-col">
                    <h2 class="headline text-center text-2xl text-stone-900 lg:text-3xl">{{ $promo['label'] }}</h2>

                    <div class="group mt-5 flex flex-1 flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-neon-900/10">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ asset($promo['image']) }}" alt="{{ $promo['alt'] }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                width="600" height="450" loading="lazy">

                            @if ($promo['badge'])
                                <span class="absolute left-4 top-4 rounded-full bg-neon-500 px-3.5 py-1 text-[10px] uppercase tracking-[0.16em] text-white">
                                    {{ $promo['badge'] }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="headline text-2xl text-stone-900">{{ $promo['title'] }}</h3>
                            <p class="mt-2 flex-1 leading-relaxed text-stone-500">{{ $promo['text'] }}</p>

                            <div class="mt-5 flex items-baseline gap-3">
                                <span class="headline text-2xl text-neon-600">{{ $promo['price'] }}</span>
                                <span class="text-stone-400 line-through">{{ $promo['was'] }}</span>
                            </div>

                            <div class="mt-4 flex items-center gap-2 rounded-xl border border-dashed border-neon-300 bg-neon-50 px-4 py-2.5">
                                <span class="text-[10px] uppercase tracking-[0.16em] text-neon-700">Kode</span>
                                <span class="font-semibold tracking-[0.12em] text-neon-800">{{ $promo['code'] }}</span>
                            </div>

                            <a href="{{ route('menu') }}"
                                class="mt-5 inline-flex items-center justify-center gap-2 rounded-full bg-neon-500 px-6 py-3 font-semibold text-white transition hover:bg-neon-600 hover:shadow-[0_0_24px_rgba(255,106,0,0.45)]">
                                Pesan Sekarang
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    {{-- Syarat singkat --}}
    <section class="bg-white px-4 py-14 sm:px-6">
        <div class="mx-auto max-w-7xl">
            <h2 class="headline text-2xl text-stone-900">Ketentuan Promo</h2>

            @php
                $terms = [
                    'Satu kode promo hanya berlaku untuk satu transaksi per siswa per hari.',
                    'Promo tidak bisa digabung dengan potongan harga lain di stan yang sama.',
                    'Penawaran berhenti lebih awal bila porsi hari itu sudah habis.',
                ];
            @endphp

            <ul class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach ($terms as $i => $term)
                    <li class="flex gap-3">
                        <span class="headline text-xl text-neon-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="leading-relaxed text-stone-500">{{ $term }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
