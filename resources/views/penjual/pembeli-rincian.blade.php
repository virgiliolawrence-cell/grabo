@extends('tataletak.penjual')

@section('title', "{$murid->nama} — Dasbor Penjual Grabo")

@section('content')
    <div class="mb-8 flex flex-wrap items-end justify-between gap-4 border-b-2 border-stone-900/10 pb-6">
        <div>
            <a href="{{ route('penjual.pembeli') }}" class="text-xs uppercase tracking-[0.15em] text-stone-400 transition hover:text-neon-700">&larr; Data Pembeli</a>
            <h1 class="judul-besar mt-3 text-[clamp(2rem,4vw,2.75rem)] text-stone-900">{{ $murid->nama }}</h1>
            <p class="mt-3 text-sm text-stone-500">
                {{ $murid->kelas ?? 'Kelas belum diisi' }} &middot; NIS {{ $murid->nis ?? '—' }} &middot; {{ $murid->email }}
            </p>
        </div>
    </div>

    {{-- Ringkasan belanja --}}
    <div class="mb-8 grid gap-4 sm:grid-cols-3">
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.18em] text-stone-400">Pesanan</p>
            <p class="judul-besar mt-2 text-3xl text-stone-900">{{ $ringkasan['jumlahPesanan'] }}</p>
        </div>
        <div class="rounded-2xl bg-white p-5 shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.18em] text-stone-400">Porsi dibeli</p>
            <p class="judul-besar mt-2 text-3xl text-stone-900">{{ $ringkasan['jumlahItem'] }}</p>
        </div>
        <div class="rounded-2xl bg-stone-900 p-5 text-white shadow-sm">
            <p class="text-[10px] uppercase tracking-[0.18em] text-white/50">Total belanja</p>
            <p class="judul-besar mt-2 text-3xl text-neon-400">Rp {{ number_format($ringkasan['totalBelanja'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1fr_17rem]">
        <div class="space-y-10">
            {{-- Pesanan berjalan: bisa dicentang --}}
            <section>
                <h2 class="judul-besar mb-4 text-2xl text-stone-900">Pesanan berjalan <span class="text-neon-600">{{ $pesananBerjalan->count() }}</span></h2>
                @forelse ($pesananBerjalan as $pesanan)
                    <div class="mb-4">
                        <x-penjual.kartu-pesanan :pesanan="$pesanan" :tampilkan-pembeli="false" />
                    </div>
                @empty
                    <p class="rounded-2xl bg-white px-5 py-8 text-center text-sm text-stone-500 shadow-sm">Tidak ada pesanan yang perlu disiapkan untuk murid ini.</p>
                @endforelse
            </section>

            {{-- Riwayat --}}
            <section>
                <h2 class="judul-besar mb-4 text-2xl text-stone-900">Riwayat pembelian</h2>
                <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px] text-left text-sm">
                            <thead>
                                <tr class="border-b-2 border-stone-900 text-[11px] uppercase tracking-[0.15em] text-stone-900">
                                    <th class="px-5 py-4 font-semibold">Tanggal</th>
                                    <th class="px-5 py-4 font-semibold">Yang dibeli</th>
                                    <th class="px-5 py-4 font-semibold">Status</th>
                                    <th class="px-5 py-4 text-right font-semibold">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($riwayatPesanan as $pesanan)
                                    <tr class="border-b border-stone-100 align-top">
                                        <td class="px-5 py-4">
                                            <p class="whitespace-nowrap">{{ $pesanan->created_at->translatedFormat('d M Y, H.i') }}</p>
                                            <a href="{{ route('penjual.pembelian.rincian', $pesanan) }}" class="font-mono text-xs text-stone-400 hover:text-neon-700">{{ $pesanan->kode }}</a>
                                        </td>
                                        <td class="px-5 py-4 text-stone-600">
                                            @foreach ($pesanan->item as $item)
                                                <p>{{ $item->jumlah }}&times; {{ $item->nama }}</p>
                                            @endforeach
                                        </td>
                                        <td class="px-5 py-4"><x-lencana :warna="$pesanan->warnaStatus()" :label="$pesanan->labelStatus()" /></td>
                                        <td @class(['px-5 py-4 text-right font-semibold', 'text-stone-900' => $pesanan->status !== 'batal', 'text-stone-400 line-through' => $pesanan->status === 'batal'])>
                                            Rp {{ number_format($pesanan->total, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-5 py-10 text-center text-stone-500">Belum ada pesanan yang selesai.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>

        {{-- Menu favorit --}}
        <aside class="h-fit rounded-2xl bg-white p-5 shadow-sm">
            <h2 class="text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900">Paling sering dibeli</h2>
            <ol class="mt-4 space-y-3">
                @forelse ($menuFavorit as $favorit)
                    <li class="flex items-center justify-between gap-3 text-sm">
                        <span class="flex items-center gap-3">
                            <span class="judul-besar w-4 text-lg text-neon-600">{{ $loop->iteration }}</span>
                            <span class="text-stone-800">{{ $favorit->nama }}</span>
                        </span>
                        <span class="text-xs text-stone-400">{{ $favorit->jumlah }}&times;</span>
                    </li>
                @empty
                    <li class="text-sm text-stone-500">Belum ada pembelian.</li>
                @endforelse
            </ol>
        </aside>
    </div>
@endsection
