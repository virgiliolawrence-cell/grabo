@extends('tataletak.penjual')

@section('title', 'Manajemen Pembelian — Dasbor Penjual Grabo')

@php
    use App\Models\Pesanan;
@endphp

@section('content')
    <x-penjual.judul-halaman judul="Manajemen Pembelian"
        keterangan="Pesanan yang masuk dari murid. Centang tiap menu saat sudah disiapkan; pesanan otomatis berstatus siap diambil begitu semua menunya tercentang."
        :tautan-aksi="route('penjual.laporan')" teks-aksi="Laporan Transaksi" ikon-aksi="panah" />

    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        {{-- Tab status --}}
        <nav class="flex gap-2 overflow-x-auto" aria-label="Saring status pesanan">
            <a href="{{ route('penjual.pembelian', array_filter(['cari' => request('cari')])) }}"
                @class(['shrink-0 rounded-full px-4 py-2 text-sm transition', 'bg-stone-900 font-semibold text-white' => ! $statusAktif, 'bg-white text-stone-600 shadow-sm hover:text-stone-900' => $statusAktif])>
                Semua <span class="ml-1 opacity-60">{{ $jumlahSemua }}</span>
            </a>
            @foreach (Pesanan::STATUS as $kunci => $status)
                <a href="{{ route('penjual.pembelian', array_filter(['status' => $kunci, 'cari' => request('cari')])) }}"
                    @class(['shrink-0 rounded-full px-4 py-2 text-sm transition', 'bg-stone-900 font-semibold text-white' => $statusAktif === $kunci, 'bg-white text-stone-600 shadow-sm hover:text-stone-900' => $statusAktif !== $kunci])>
                    {{ $status['label'] }} <span class="ml-1 opacity-60">{{ $jumlahPerStatus[$kunci] ?? 0 }}</span>
                </a>
            @endforeach
        </nav>

        <form method="GET" action="{{ route('penjual.pembelian') }}" class="flex gap-2">
            @if ($statusAktif)
                <input type="hidden" name="status" value="{{ $statusAktif }}">
            @endif
            <label for="cari" class="sr-only">Cari pesanan</label>
            <input type="search" id="cari" name="cari" value="{{ request('cari') }}" placeholder="Kode atau nama murid"
                class="w-56 rounded-full border border-stone-200 bg-white px-4 py-2 text-sm outline-none transition focus:border-neon-500 focus:ring-4 focus:ring-neon-500/15">
            <button type="submit" class="rounded-full bg-stone-900 px-5 py-2 text-sm font-semibold text-white transition hover:bg-stone-700">Cari</button>
        </form>
    </div>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-stone-900 text-[11px] uppercase tracking-[0.15em] text-stone-900">
                        <th class="px-5 py-4 font-semibold">Kode</th>
                        <th class="px-5 py-4 font-semibold">Pembeli</th>
                        <th class="px-5 py-4 font-semibold">Pesanan &middot; centang bila siap</th>
                        <th class="px-5 py-4 text-right font-semibold">Total</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 text-right font-semibold">Ubah status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarPesanan as $pesanan)
                        <tr id="pesanan-{{ $pesanan->kode }}" class="scroll-mt-6 border-b border-stone-100 align-top transition target:bg-neon-50 hover:bg-neon-50/40">
                            <td class="px-5 py-4">
                                <a href="{{ route('penjual.pembelian.rincian', $pesanan) }}" class="whitespace-nowrap font-mono text-xs font-semibold text-stone-900 hover:text-neon-700">{{ $pesanan->kode }}</a>
                                <p class="mt-1 whitespace-nowrap text-xs text-stone-400">{{ $pesanan->created_at->translatedFormat('d M, H.i') }}</p>
                            </td>
                            <td class="px-5 py-4">
                                <a href="{{ route('penjual.pembeli.rincian', $pesanan->pengguna_id) }}" class="font-semibold text-stone-900 hover:text-neon-700">{{ $pesanan->nama }}</a>
                                <p class="text-xs text-stone-400">{{ $pesanan->kelas }}</p>
                                <p class="mt-1 text-xs text-stone-400">{{ $pesanan->labelMetode() }}</p>
                            </td>
                            <td class="min-w-64 px-4 py-3">
                                @foreach ($pesanan->item as $item)
                                    <x-penjual.centang-item :pesanan="$pesanan" :item="$item" />
                                @endforeach
                            </td>
                            <td class="px-5 py-4 text-right">
                                <p class="whitespace-nowrap font-semibold text-stone-900">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                                @if ($pesanan->potongan)
                                    <p class="whitespace-nowrap text-xs text-emerald-600">{{ $pesanan->kode_promo }} &minus;{{ number_format($pesanan->potongan, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <x-lencana :warna="$pesanan->warnaStatus()" :label="$pesanan->labelStatus()" />
                            </td>
                            <td class="px-5 py-4">
                                <form method="POST" action="{{ route('penjual.pembelian.status', $pesanan) }}" class="flex justify-end gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label for="status-{{ $pesanan->kode }}" class="sr-only">Status baru {{ $pesanan->kode }}</label>
                                    <select id="status-{{ $pesanan->kode }}" name="status"
                                        class="rounded-lg border border-stone-200 bg-stone-50 px-2 py-1.5 text-xs outline-none focus:border-neon-500">
                                        @foreach (Pesanan::STATUS as $kunci => $status)
                                            <option value="{{ $kunci }}" @selected($pesanan->status === $kunci)>{{ $status['label'] }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-lg bg-neon-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-neon-600">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-stone-500">Belum ada pesanan di sini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $daftarPesanan->links() }}</div>
@endsection
