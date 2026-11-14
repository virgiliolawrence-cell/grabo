@extends('tataletak.penjual')

@section('title', 'Manajemen Menu — Dasbor Penjual Grabo')

@section('content')
    <x-penjual.judul-halaman judul="Manajemen Menu"
        keterangan="Daftar sajian di setiap stan. Menu yang dijual langsung tampil di halaman siswa; tandai habis untuk menyembunyikannya sementara."
        :tautan-aksi="route('penjual.menu.tambah')" teks-aksi="Tambah Menu" />

    <form method="GET" action="{{ route('penjual.menu') }}" class="mb-6 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-5 shadow-sm">
        <div class="min-w-56 flex-1">
            <label for="cari" class="mb-1.5 block text-[11px] uppercase tracking-[0.15em] text-stone-500">Cari</label>
            <input type="search" name="cari" id="cari" value="{{ request('cari') }}" placeholder="Nama menu atau stan"
                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/15">
        </div>
        <div>
            <label for="kategori" class="mb-1.5 block text-[11px] uppercase tracking-[0.15em] text-stone-500">Kategori</label>
            <select name="kategori" id="kategori"
                class="rounded-xl border border-stone-200 bg-stone-50 px-3.5 py-2.5 text-sm outline-none transition focus:border-neon-500 focus:bg-white">
                <option value="">Semua kategori</option>
                @foreach ($daftarKategori as $kategori)
                    <option value="{{ $kategori->id }}" @selected((int) request('kategori') === $kategori->id)>{{ $kategori->label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="rounded-full bg-stone-900 px-6 py-2.5 text-sm font-semibold text-white transition hover:bg-stone-700">Terapkan</button>
    </form>

    <div class="overflow-hidden rounded-2xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead>
                    <tr class="border-b-2 border-stone-900 text-[11px] uppercase tracking-[0.15em] text-stone-900">
                        <th class="w-14 px-5 py-4 font-semibold">No.</th>
                        <th class="px-5 py-4 font-semibold">Menu</th>
                        <th class="px-5 py-4 font-semibold">Kategori</th>
                        <th class="px-5 py-4 text-right font-semibold">Harga</th>
                        <th class="px-5 py-4 text-right font-semibold">Terjual</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 text-right font-semibold">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($daftarMenu as $sajian)
                        <tr @class(['border-b border-stone-100 transition hover:bg-neon-50/50', 'opacity-60' => ! $sajian->tersedia])>
                            <td class="px-5 py-4 judul-besar text-lg text-neon-600">{{ $daftarMenu->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($sajian->gambar)
                                        <img src="{{ asset($sajian->gambar) }}" alt="" class="h-11 w-11 rounded-xl object-cover" width="44" height="44" loading="lazy">
                                    @else
                                        <span class="flex h-11 w-11 items-center justify-center rounded-xl bg-neon-50 text-[9px] uppercase tracking-wider text-neon-700">Foto</span>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-stone-900">{{ $sajian->nama }}</p>
                                        <p class="text-xs text-stone-400">{{ $sajian->stan }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">{{ $sajian->kategori->label }}</td>
                            <td class="px-5 py-4 text-right font-semibold text-stone-900">Rp {{ number_format($sajian->harga, 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-right">{{ number_format($sajian->terjual, 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                @if ($sajian->tersedia)
                                    <x-lencana warna="hijau" label="Dijual" />
                                @else
                                    <x-lencana warna="merah" label="Habis" />
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4 text-xs font-semibold">
                                    <a href="{{ route('penjual.menu.ubah', $sajian) }}" class="text-stone-900 transition hover:text-neon-700">Ubah</a>
                                    <form method="POST" action="{{ route('penjual.menu.ketersediaan', $sajian) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $sajian->tersedia ? 'text-red-600 hover:text-red-800' : 'text-emerald-700 hover:text-emerald-900' }}">
                                            {{ $sajian->tersedia ? 'Tandai habis' : 'Jual lagi' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-stone-500">Tidak ada menu yang cocok dengan pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $daftarMenu->links() }}</div>
@endsection
