@extends('tataletak.grabo')

@section('title', 'Promo Kantin — Grabo')
@section('description', 'Promo kantin sekolah bulan ini: paket hemat, diskon stan, dan penawaran minuman. Klaim langsung lewat Grabo.')

@section('content')

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
    <section class="bg-krem px-4 pt-12 sm:px-6 lg:pt-16">
        <div class="mx-auto max-w-7xl">
            <span class="text-[11px] uppercase tracking-[0.24em] text-neon-800">Promo Bulan Ini</span>
            <h1 class="mt-3 judul-besar text-[clamp(2.2rem,5vw,3.75rem)] text-stone-900">Hemat Setiap Istirahat</h1>
            <p class="mt-4 max-w-2xl leading-relaxed text-stone-500">
                Tiga penawaran dari stan kantin sekolah, berlaku sepanjang bulan ini. Pesan lewat Grabo,
                sebutkan kode promonya saat mengambil pesanan, dan bayar dengan saldo pelajar seperti biasa.
            </p>
        </div>
    </section>

    {{-- Tiga kolom promo --}}
    <section class="bg-krem px-4 pb-16 pt-10 sm:px-6 lg:pb-20">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-8 md:grid-cols-3 lg:gap-10">
            @foreach ($kelompokPromo as $promo)
                <article class="flex flex-col" data-kartu-promo="{{ $promo['kode'] }}">
                    <h2 class="judul-besar text-center text-2xl text-stone-900 lg:text-3xl">{{ $promo['label'] }}</h2>

                    {{--
                        Kelas .is-dipakai dipasang JavaScript pada kartu yang kodenya
                        sedang tersimpan di keranjang; gayanya ada di app.css.
                    --}}
                    <div class="kartu-promo group relative mt-5 flex flex-1 flex-col overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1.5 hover:shadow-xl hover:shadow-neon-900/10">
                        {{-- Pita penanda kode yang sedang dipakai. --}}
                        <span data-pita-dipakai hidden
                            class="absolute right-4 top-4 z-10 flex items-center gap-1.5 rounded-full bg-emerald-600 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-white shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-3.5 w-3.5" aria-hidden="true">
                                <path d="M5 12.5l4.5 4.5L19 7.5" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            Sedang dipakai
                        </span>

                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ asset($promo['gambar']) }}" alt="{{ $promo['alt'] }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-110"
                                width="600" height="450" loading="lazy">

                            @if ($promo['sematan'])
                                <span class="absolute left-4 top-4 rounded-full bg-neon-500 px-3.5 py-1 text-[10px] uppercase tracking-[0.16em] text-white">
                                    {{ $promo['sematan'] }}
                                </span>
                            @endif
                        </div>

                        <div class="flex flex-1 flex-col p-6">
                            <h3 class="judul-besar text-2xl text-stone-900">{{ $promo['judul'] }}</h3>
                            <p class="mt-2 flex-1 leading-relaxed text-stone-500">{{ $promo['teks'] }}</p>

                            <div class="mt-5 flex items-baseline gap-3">
                                <span class="judul-besar text-2xl text-neon-600">{{ $promo['harga'] }}</span>
                                <span class="text-stone-400 line-through">{{ $promo['hargaAsli'] }}</span>
                            </div>

                            <div data-kotak-kode class="mt-4 flex items-center gap-2 rounded-xl border border-dashed border-neon-300 bg-neon-50 px-4 py-2.5 transition">
                                <span class="text-[10px] uppercase tracking-[0.16em] text-neon-700">Kode</span>
                                <span class="font-semibold tracking-[0.12em] text-neon-800">{{ $promo['kode'] }}</span>
                                {{--
                                    Sekali tekan: kodenya disalin ke papan klip sekaligus
                                    disimpan ke keranjang, jadi tidak perlu diketik ulang.
                                --}}
                                <button type="button" data-pakai-promo="{{ $promo['kode'] }}"
                                    class="ml-auto inline-flex shrink-0 items-center gap-1.5 rounded-full bg-white px-4 py-1.5 text-sm font-semibold text-neon-700 shadow-sm transition hover:bg-neon-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" data-ikon-salin aria-hidden="true">
                                        <rect x="9" y="9" width="11" height="11" rx="2.5" stroke="currentColor" stroke-width="1.8" />
                                        <path d="M15 6.5V5.5A2.5 2.5 0 0 0 12.5 3h-7A2.5 2.5 0 0 0 3 5.5v7A2.5 2.5 0 0 0 5.5 15h1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" data-ikon-centang hidden aria-hidden="true">
                                        <path d="M5 12.5l4.5 4.5L19 7.5" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <span data-teks-tombol>Salin kode</span>
                                </button>
                            </div>

                            {{-- Keterangan hasil: tersalin, sudah dipakai, atau syarat belanja. --}}
                            <p data-pesan-kode hidden class="mt-2 flex items-start gap-1.5 text-sm text-emerald-700"></p>

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
            <h2 class="judul-besar text-2xl text-stone-900">Ketentuan Promo</h2>


            <ul class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">
                @foreach ($ketentuan as $i => $satuKetentuan)
                    <li class="flex gap-3">
                        <span class="judul-besar text-xl text-neon-500">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <p class="leading-relaxed text-stone-500">{{ $satuKetentuan }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endsection
