@extends('layouts.admin')

@section('title', 'Manajemen Menu')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="headline text-3xl text-stone-900">Manajemen Menu</h1>
            <p class="mt-2 text-stone-500">Menu yang disembunyikan tidak muncul di halaman siswa.</p>
        </div>
        <a href="{{ route('admin.menu.create') }}" class="rounded-full bg-neon-500 px-6 py-3 font-semibold text-white transition hover:bg-neon-600">
            Tambah menu
        </a>
    </div>

    <form method="GET" class="mt-6 flex flex-wrap gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama menu atau stan"
            class="w-full max-w-xs rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none focus:border-neon-500 focus:ring-4 focus:ring-neon-500/20">
        <select name="kategori" class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none focus:border-neon-500">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category }}" @selected(request('kategori') === $category)>{{ $category }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl border-2 border-stone-800 px-5 py-2.5 font-semibold text-stone-800 transition hover:bg-stone-800 hover:text-white">
            Saring
        </button>
    </form>

    <div class="mt-5 overflow-x-auto rounded-2xl bg-white p-2 shadow-sm">
        <table class="w-full min-w-[900px] text-left text-sm">
            <thead class="text-[11px] uppercase tracking-[0.16em] text-stone-400">
                <tr class="border-b border-stone-100">
                    <th class="p-4 font-medium">Menu</th>
                    <th class="p-4 font-medium">Kategori</th>
                    <th class="p-4 font-medium">Harga</th>
                    <th class="p-4 font-medium">Stok</th>
                    <th class="p-4 font-medium">Status</th>
                    <th class="p-4 text-right font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr class="border-b border-stone-50 last:border-0">
                        <td class="p-4">
                            <span class="font-semibold text-stone-900">{{ $item->name }}</span>
                            <span class="block text-xs text-stone-400">{{ $item->stall }}</span>
                        </td>
                        <td class="p-4 text-stone-500">{{ $item->category }}</td>
                        <td class="p-4 font-semibold text-stone-900">{{ $item->price_label }}</td>
                        <td class="p-4 text-stone-500">{{ $item->stock }}</td>
                        <td class="p-4">
                            @if ($item->is_available)
                                <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-800">Dijual</span>
                            @else
                                <span class="inline-block rounded-full bg-stone-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-stone-600">Disembunyikan</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.menu.edit', $item) }}" class="rounded-lg border border-stone-200 px-3 py-1.5 text-sm transition hover:bg-stone-100">Ubah</a>

                                <form method="POST" action="{{ route('admin.menu.toggle', $item) }}">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="rounded-lg border border-stone-200 px-3 py-1.5 text-sm transition hover:bg-stone-100">
                                        {{ $item->is_available ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.menu.destroy', $item) }}"
                                    onsubmit="return confirm('Hapus menu {{ $item->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-600 transition hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-8 text-center text-stone-400">Tidak ada menu yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $items->links() }}</div>
@endsection
