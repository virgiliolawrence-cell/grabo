@extends('tataletak.penjual')

@section('title', 'Manajemen Diskon — Dasbor Penjual Grabo')

@section('content')
    <x-penjual.judul-halaman judul="Manajemen Diskon"
        keterangan="Kode promo yang bisa dimasukkan murid di keranjang. Potongan hanya berlaku bila belanja mencapai batas minimal."
        :tautan-aksi="route('penjual.diskon.tambah')" teks-aksi="Tambah Diskon" />

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-stone-900 text-[11px] uppercase tracking-[0.15em] text-stone-900">
                        <th class="w-14 px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Kode</th>
                        <th class="px-5 py-4 font-semibold">Nama promo</th>
                        <th class="px-5 py-4 text-right font-semibold">Potongan</th>
                        <th class="px-5 py-4 text-right font-semibold">Belanja minimal</th>
                        <th class="px-5 py-4 text-right font-semibold">Dipakai</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 text-right font-semibold">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarDiskon as $diskon)
                        <tr @class(['border-b border-stone-100 transition hover:bg-neon-50/50', 'opacity-60' => ! $diskon->aktif])>
                            <td class="px-5 py-4 judul-besar text-lg text-neon-600">{{ $daftarDiskon->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-lg border border-dashed border-neon-400 bg-neon-50 px-2.5 py-1 font-mono text-xs font-semibold tracking-wider text-neon-800">{{ $diskon->kode }}</span>
                            </td>
                            <td class="px-5 py-4 font-semibold text-stone-900">{{ $diskon->label }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-emerald-700">Rp {{ number_format($diskon->potongan, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right">Rp {{ number_format($diskon->belanja_minimal, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right">{{ $diskon->dipakai }}&times;</td>
                            <td class="px-5 py-4">
                                @if ($diskon->aktif)
                                    <x-lencana warna="hijau" label="Aktif" />
                                @else
                                    <x-lencana label="Nonaktif" />
                                @endif
                            </td>
                            <td class="px-5 py-4 text-right">
                                <form method="POST" action="{{ route('penjual.diskon.aktif', $diskon) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs font-semibold {{ $diskon->aktif ? 'text-red-600 hover:text-red-800' : 'text-emerald-700 hover:text-emerald-900' }}">
                                        {{ $diskon->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center text-stone-500">Belum ada kode diskon.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $daftarDiskon->links() }}</div>
@endsection
