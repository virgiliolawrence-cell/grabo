@extends('tataletak.penjual')

@section('title', 'Data Pembeli — Dasbor Penjual Grabo')

@section('content')
    <x-penjual.judul-halaman judul="Data Pembeli"
        keterangan="Akun murid yang bisa memesan. Buka satu murid untuk melihat semua yang pernah ia beli dan mencentang pesanannya yang masih berjalan."
        :tautan-aksi="route('penjual.pembeli.tambah')" teks-aksi="Daftarkan Murid" />

    <form method="GET" action="{{ route('penjual.pembeli') }}" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-5 shadow-sm">
        <div class="min-w-56 flex-1">
            <label for="cari" class="mb-1.5 block text-[11px] uppercase tracking-[0.15em] text-stone-500">Cari murid</label>
            <input type="search" name="cari" id="cari" value="{{ request('cari') }}" placeholder="Nama, NIS, atau kelas"
                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/15">
        </div>
        <button type="submit" class="rounded-full bg-stone-900 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-700">Terapkan</button>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-stone-900 text-[11px] uppercase tracking-[0.15em] text-stone-900">
                        <th class="px-5 py-4 font-semibold">Murid</th>
                        <th class="px-5 py-4 font-semibold">NIS</th>
                        <th class="px-5 py-4 font-semibold">Kelas</th>
                        <th class="px-5 py-4 text-right font-semibold">Pesanan</th>
                        <th class="px-5 py-4 text-right font-semibold">Total belanja</th>
                        <th class="px-5 py-4 font-semibold">Terakhir pesan</th>
                        <th class="px-5 py-4 text-right font-semibold">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarMurid as $murid)
                        <tr class="border-b border-stone-100 transition hover:bg-neon-50/50">
                            <td class="px-5 py-4">
                                <p class="font-semibold text-stone-900">{{ $murid->nama }}</p>
                                <p class="text-xs text-stone-400">{{ $murid->email }}</p>
                            </td>
                            <td class="px-5 py-4 font-mono text-xs">{{ $murid->nis ?? '—' }}</td>
                            <td class="px-5 py-4">{{ $murid->kelas ?? '—' }}</td>
                            <td class="px-5 py-4 text-right">
                                {{ $murid->jumlah_pesanan }}
                                @if ($murid->jumlah_pesanan_berjalan)
                                    <span class="ml-1"><x-lencana warna="kuning" :label="$murid->jumlah_pesanan_berjalan.' berjalan'" /></span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right font-semibold text-stone-900">Rp {{ number_format((int) $murid->total_belanja, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-stone-500">
                                {{ $murid->pesanan_terakhir ? \Illuminate\Support\Carbon::parse($murid->pesanan_terakhir)->translatedFormat('d M Y, H.i') : 'Belum pernah' }}
                            </td>
                            <td class="px-5 py-4 text-right">
                                <a href="{{ route('penjual.pembeli.rincian', $murid) }}" class="text-xs font-semibold text-stone-900 transition hover:text-neon-700">Lihat pembelian &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-stone-500">Tidak ada murid yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $daftarMurid->links() }}</div>
@endsection
