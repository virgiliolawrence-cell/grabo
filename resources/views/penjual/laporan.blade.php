@extends('tataletak.penjual')

@section('title', 'Laporan Transaksi — Dasbor Penjual Grabo')

@php
    use App\Models\Pesanan;

    $kelasIsian = 'w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/15';
@endphp

@section('content')
    <x-penjual.judul-halaman judul="Laporan Transaksi"
        keterangan="Rekap transaksi per periode dan metode bayar. Pesanan yang batal tetap tercatat, tetapi tidak dihitung sebagai pendapatan." />

    {{-- Saringan laporan --}}
    <form method="GET" action="{{ route('penjual.laporan') }}" class="mb-6 rounded-2xl bg-white p-5 shadow-sm sm:p-6">
        <p class="mb-4 text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900">Saring laporan</p>
        <div class="flex flex-wrap items-end gap-3">
            <div class="min-w-40 flex-1">
                <label for="dari" class="mb-1.5 block text-[11px] uppercase tracking-[0.15em] text-stone-500">Dari tanggal</label>
                <input type="date" id="dari" name="dari" value="{{ request('dari') }}" class="{{ $kelasIsian }}">
            </div>
            <div class="min-w-40 flex-1">
                <label for="sampai" class="mb-1.5 block text-[11px] uppercase tracking-[0.15em] text-stone-500">Sampai tanggal</label>
                <input type="date" id="sampai" name="sampai" value="{{ request('sampai') }}" class="{{ $kelasIsian }}">
            </div>
            <div class="min-w-40 flex-1">
                <label for="metode" class="mb-1.5 block text-[11px] uppercase tracking-[0.15em] text-stone-500">Metode bayar</label>
                <select id="metode" name="metode" class="{{ $kelasIsian }}">
                    <option value="">Semua metode</option>
                    @foreach (Pesanan::METODE as $kunci => $label)
                        <option value="{{ $kunci }}" @selected(request('metode') === $kunci)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-full bg-neon-500 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-neon-600">Terapkan</button>
                <a href="{{ route('penjual.laporan') }}" class="rounded-full border-2 border-stone-200 px-6 py-2 text-sm font-semibold text-stone-600 transition hover:border-stone-900 hover:text-stone-900">Atur ulang</a>
            </div>
        </div>
        @if ($errors->any())
            <p class="mt-3 text-sm text-red-600">{{ $errors->first() }}</p>
        @endif
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[840px] text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-stone-900 text-[11px] uppercase tracking-[0.15em] text-stone-900">
                        <th class="px-5 py-4 font-semibold">Tanggal</th>
                        <th class="px-5 py-4 font-semibold">Kode</th>
                        <th class="px-5 py-4 font-semibold">Pembeli</th>
                        <th class="px-5 py-4 font-semibold">Metode</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 text-right font-semibold">Potongan</th>
                        <th class="px-5 py-4 text-right font-semibold">Total</th>
                        <th class="px-5 py-4 text-right font-semibold">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarTransaksi as $transaksi)
                        <tr @class(['border-b border-stone-100 transition hover:bg-neon-50/50', 'text-stone-400' => $transaksi->status === 'batal'])>
                            <td class="px-5 py-4 whitespace-nowrap">{{ $transaksi->created_at->translatedFormat('d M Y, H.i') }}</td>
                            <td class="px-5 py-4 font-mono text-xs">{{ $transaksi->kode }}</td>
                            <td class="px-5 py-4">{{ $transaksi->nama }} <span class="text-xs text-stone-400">&middot; {{ $transaksi->kelas }}</span></td>
                            <td class="px-5 py-4">{{ $transaksi->labelMetode() }}</td>
                            <td class="px-5 py-4">
                                <x-lencana :warna="$transaksi->warnaStatus()" :label="$transaksi->labelStatus()" />
                            </td>
                            <td class="px-5 py-4 text-right">{{ $transaksi->potongan ? 'Rp '.number_format($transaksi->potongan, 0, ',', '.') : '—' }}</td>
                            <td @class(['px-5 py-4 text-right font-semibold', 'text-stone-900' => $transaksi->status !== 'batal', 'line-through' => $transaksi->status === 'batal'])>
                                Rp {{ number_format($transaksi->total, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form method="POST" action="{{ route('penjual.laporan.hapus', $transaksi) }}"
                                    onsubmit="return confirm(@js("Hapus transaksi {$transaksi->kode} atas nama {$transaksi->nama}? Pesanannya juga hilang dari Pembelian dan riwayat murid. Tindakan ini tidak bisa dibatalkan."))">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold text-red-600 transition hover:text-red-800">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-stone-500">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                {{-- Baris ringkasan --}}
                <tfoot>
                    <tr class="bg-stone-900 text-white">
                        <td colspan="5" class="px-5 py-4">
                            <span class="text-[11px] uppercase tracking-[0.15em] text-white/60">Total pendapatan</span>
                            <span class="ml-2 text-xs text-white/60">{{ $ringkasan['jumlahTransaksi'] }} transaksi &middot; {{ $ringkasan['jumlahBatal'] }} batal</span>
                        </td>
                        <td class="px-5 py-4 text-right text-white/70">Rp {{ number_format($ringkasan['totalPotongan'], 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-right judul-besar text-xl text-neon-400">Rp {{ number_format($ringkasan['totalPendapatan'], 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $daftarTransaksi->links() }}</div>
@endsection
