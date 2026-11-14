@extends('tataletak.grabo')

@section('title', 'Pesanan Saya — Grabo')
@section('description', 'Pantau status pesanan kantinmu: menunggu, sedang disiapkan, atau siap diambil.')

@php
    use App\Models\Pesanan;
@endphp

@section('content')
    <section class="bg-krem px-4 py-12 sm:px-6 lg:py-16">
        <div class="mx-auto max-w-3xl">
            <div class="flex flex-wrap items-end justify-between gap-4 border-b-2 border-stone-900/10 pb-5">
                <div>
                    <h1 class="judul-besar text-[clamp(2rem,4vw,3rem)] text-stone-900">Pesanan Saya</h1>
                    <p class="mt-2 text-stone-500">Centang hijau berarti menunya sudah disiapkan stan.</p>
                </div>
                <a href="{{ route('menu') }}" class="rounded-full bg-neon-500 px-6 py-3 text-sm font-semibold text-white transition hover:bg-neon-600">Pesan lagi</a>
            </div>

            <div class="mt-8 space-y-5">
                @forelse ($daftarPesanan as $pesanan)
                    @php($jumlahSiap = $pesanan->item->where('sudah_disiapkan', true)->count())
                    <article class="overflow-hidden rounded-2xl bg-white shadow-sm">
                        <header class="flex flex-wrap items-start justify-between gap-3 border-b border-stone-100 px-6 py-4">
                            <div>
                                <p class="font-mono text-sm font-semibold tracking-wider text-stone-900">{{ $pesanan->kode }}</p>
                                <p class="mt-1 text-xs text-stone-400">
                                    {{ $pesanan->created_at->translatedFormat('d M Y, H.i') }} &middot; Ambil {{ Pesanan::WAKTU_AMBIL[$pesanan->waktu_ambil] ?? $pesanan->waktu_ambil }}
                                </p>
                            </div>
                            <x-lencana :warna="$pesanan->warnaStatus()" :label="$pesanan->labelStatus()" />
                        </header>

                        <ul class="space-y-2 px-6 py-4">
                            @foreach ($pesanan->item as $item)
                                <li class="flex items-start justify-between gap-3 text-sm">
                                    <span class="flex items-start gap-2.5">
                                        <span @class([
                                            'mt-0.5 flex h-[18px] w-[18px] shrink-0 items-center justify-center rounded-full',
                                            'bg-emerald-500 text-white' => $item->sudah_disiapkan,
                                            'border-2 border-stone-200 text-transparent' => ! $item->sudah_disiapkan,
                                        ])>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-3 w-3" aria-hidden="true">
                                                <path d="M5 12.5l4.5 4.5L19 7.5" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        <span>
                                            <span class="text-stone-800">{{ $item->jumlah }}&times; {{ $item->nama }}</span>
                                            @if ($item->pilihan)
                                                <span class="block text-xs text-stone-400">{{ $item->pilihan }}</span>
                                            @endif
                                            <span class="sr-only">{{ $item->sudah_disiapkan ? '(sudah disiapkan)' : '(belum disiapkan)' }}</span>
                                        </span>
                                    </span>
                                    <span class="shrink-0 text-stone-500">Rp {{ number_format($item->harga * $item->jumlah, 0, ',', '.') }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <footer class="flex flex-wrap items-center justify-between gap-3 border-t border-stone-100 bg-stone-50/60 px-6 py-4">
                            <p class="text-sm text-stone-500">
                                @if ($pesanan->status === 'siap')
                                    <strong class="text-neon-700">Siap diambil!</strong> Sebutkan kode pesanan di loket.
                                @elseif ($pesanan->masihBerjalan())
                                    {{ $jumlahSiap }} dari {{ $pesanan->item->count() }} menu sudah disiapkan.
                                @else
                                    {{ $pesanan->labelMetode() }}
                                @endif
                            </p>
                            <p class="judul-besar text-2xl text-stone-900">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                        </footer>
                    </article>
                @empty
                    <div class="rounded-2xl bg-white px-8 py-12 text-center shadow-sm">
                        <h2 class="judul-besar text-2xl text-stone-900">Belum ada pesanan</h2>
                        <p class="mt-2 text-stone-500">Pesananmu akan muncul di sini setelah kamu membayar.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">{{ $daftarPesanan->links() }}</div>
        </div>
    </section>
@endsection
