@extends('layouts.admin')

@section('title', $item->exists ? 'Ubah Menu' : 'Tambah Menu')

@section('content')
    <a href="{{ route('admin.menu.index') }}" class="text-sm text-stone-500 hover:text-neon-700">&larr; Kembali ke daftar menu</a>
    <h1 class="mt-3 headline text-3xl text-stone-900">{{ $item->exists ? 'Ubah Menu' : 'Tambah Menu' }}</h1>

    <form method="POST" action="{{ $item->exists ? route('admin.menu.update', $item) : route('admin.menu.store') }}"
        class="mt-6 max-w-3xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div class="sm:col-span-2">
                <label for="name" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Nama menu</label>
                <input type="text" id="name" name="name" required value="{{ old('name', $item->name) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="stall" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Stan</label>
                <input type="text" id="stall" name="stall" required value="{{ old('stall', $item->stall) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="category" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Kategori</label>
                <input type="text" id="category" name="category" required value="{{ old('category', $item->category) }}"
                    list="daftar-kategori" placeholder="Makanan Berat"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                <datalist id="daftar-kategori">
                    <option value="Makanan Berat"></option>
                    <option value="Gorengan &amp; Snack"></option>
                    <option value="Minuman"></option>
                </datalist>
            </div>

            <div>
                <label for="type" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Jenis varian</label>
                <select id="type" name="type" class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500">
                    @foreach (['makanan' => 'Makanan (level pedas, porsi)', 'snack' => 'Snack (porsi, saus)', 'minuman' => 'Minuman (suhu, gula, es)'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('type', $item->type) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="price" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Harga (Rp)</label>
                <input type="number" id="price" name="price" required min="0" step="500" value="{{ old('price', $item->price) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="stock" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Stok hari ini</label>
                <input type="number" id="stock" name="stock" required min="0" value="{{ old('stock', $item->stock ?? 0) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="badge" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Label (opsional)</label>
                <input type="text" id="badge" name="badge" value="{{ old('badge', $item->badge) }}" placeholder="Best Seller"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div class="sm:col-span-2">
                <label for="image" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Path gambar</label>
                <input type="text" id="image" name="image" value="{{ old('image', $item->image) }}"
                    placeholder="images/food/photos/nasi-goreng.jpg"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                <p class="mt-1.5 text-xs text-stone-400">Relatif terhadap folder <code>public/</code>. Unggah berkasnya dulu ke sana.</p>
            </div>

            <div class="sm:col-span-2">
                <label for="summary" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Ringkasan</label>
                <input type="text" id="summary" name="summary" value="{{ old('summary', $item->summary) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div class="sm:col-span-2">
                <label for="description" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Deskripsi lengkap</label>
                <textarea id="description" name="description" rows="4"
                    class="mt-2 w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">{{ old('description', $item->description) }}</textarea>
            </div>

            <label class="flex items-center gap-2.5 text-sm text-stone-600 sm:col-span-2">
                <input type="checkbox" name="is_available" value="1" @checked(old('is_available', $item->is_available ?? true))
                    class="h-4 w-4 rounded border-stone-300 accent-neon-500">
                Tampilkan menu ini di halaman siswa
            </label>
        </div>

        <div class="mt-7 flex gap-3">
            <button type="submit" class="rounded-full bg-neon-500 px-7 py-3 font-semibold text-white transition hover:bg-neon-600">
                {{ $item->exists ? 'Simpan perubahan' : 'Tambah menu' }}
            </button>
            <a href="{{ route('admin.menu.index') }}" class="rounded-full border-2 border-stone-300 px-7 py-3 font-semibold text-stone-600 transition hover:border-stone-800 hover:text-stone-900">
                Batal
            </a>
        </div>
    </form>
@endsection
