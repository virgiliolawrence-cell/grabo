@extends('tataletak.penjual')

@php
    $mengubah = $sajian->exists;
    $kelasIsian = 'w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/15';
    $kelasLabel = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900';
@endphp

@section('title', ($mengubah ? 'Ubah Menu' : 'Tambah Menu').' — Dasbor Penjual Grabo')

@section('content')
    <div class="mb-8 border-b-2 border-stone-900/10 pb-6">
        <a href="{{ route('penjual.menu') }}" class="text-xs uppercase tracking-[0.15em] text-stone-400 transition hover:text-neon-700">&larr; Manajemen Menu</a>
        <h1 class="judul-besar mt-3 text-[clamp(2rem,4vw,2.75rem)] text-stone-900">{{ $mengubah ? "Ubah {$sajian->nama}" : 'Tambah Menu' }}</h1>
        <p class="mt-3 text-sm text-stone-500">Menu yang ditandai dijual langsung muncul di halaman menu siswa.</p>
    </div>

    <form method="POST" enctype="multipart/form-data"
        action="{{ $mengubah ? route('penjual.menu.perbarui', $sajian) : route('penjual.menu.simpan') }}"
        class="max-w-2xl space-y-6 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        @csrf
        @if ($mengubah)
            @method('PUT')
        @endif

        <div>
            <label for="nama" class="{{ $kelasLabel }}">Nama menu</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $sajian->nama) }}" placeholder="Contoh: Soto Ayam Lamongan" class="{{ $kelasIsian }}">
            @error('nama') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="kategori_id" class="{{ $kelasLabel }}">Kategori</label>
                <select id="kategori_id" name="kategori_id" class="{{ $kelasIsian }}">
                    <option value="">Pilih kategori</option>
                    @foreach ($daftarKategori as $kategori)
                        <option value="{{ $kategori->id }}" @selected((int) old('kategori_id', $sajian->kategori_id) === $kategori->id)>{{ $kategori->label }}</option>
                    @endforeach
                </select>
                @error('kategori_id') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="stan" class="{{ $kelasLabel }}">Stan</label>
                <input type="text" id="stan" name="stan" list="daftarStan" value="{{ old('stan', $sajian->stan) }}" placeholder="Pilih atau ketik stan baru" class="{{ $kelasIsian }}">
                <datalist id="daftarStan">
                    @foreach ($daftarStan as $stan)
                        <option value="{{ $stan }}"></option>
                    @endforeach
                </datalist>
                @error('stan') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="harga" class="{{ $kelasLabel }}">Harga (Rp)</label>
                <input type="number" id="harga" name="harga" value="{{ old('harga', $sajian->harga) }}" min="1000" step="500" placeholder="10000" class="{{ $kelasIsian }}">
                @error('harga') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="jenis" class="{{ $kelasLabel }}">Jenis</label>
                <select id="jenis" name="jenis" class="{{ $kelasIsian }}">
                    <option value="makanan" @selected(old('jenis', $sajian->jenis ?? 'makanan') === 'makanan')>Makanan</option>
                    <option value="minuman" @selected(old('jenis', $sajian->jenis) === 'minuman')>Minuman</option>
                </select>
                @error('jenis') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="ringkasan" class="{{ $kelasLabel }}">Ringkasan singkat</label>
            <input type="text" id="ringkasan" name="ringkasan" value="{{ old('ringkasan', $sajian->ringkasan) }}" maxlength="200" placeholder="Satu kalimat yang tampil di kartu menu" class="{{ $kelasIsian }}">
            @error('ringkasan') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="deskripsi" class="{{ $kelasLabel }}">Deskripsi</label>
            <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000" class="{{ $kelasIsian }}">{{ old('deskripsi', $sajian->deskripsi) }}</textarea>
            @error('deskripsi') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="gambar" class="{{ $kelasLabel }}">Foto menu</label>
            <div class="flex items-center gap-4">
                @if ($sajian->gambar)
                    <img src="{{ asset($sajian->gambar) }}" alt="" class="h-16 w-16 rounded-xl object-cover" width="64" height="64">
                @endif
                <input type="file" id="gambar" name="gambar" accept="image/*"
                    class="text-sm text-stone-600 file:mr-3 file:rounded-full file:border-0 file:bg-neon-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-neon-700 hover:file:bg-neon-100">
            </div>
            <p class="mt-1.5 text-xs text-stone-400">JPG/PNG, maksimal 2 MB.{{ $sajian->gambar ? ' Kosongkan kalau tidak ingin mengganti.' : '' }}</p>
            @error('gambar') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-3 text-sm text-stone-700">
            <input type="hidden" name="tersedia" value="0">
            <input type="checkbox" name="tersedia" value="1" @checked(old('tersedia', $sajian->tersedia)) class="h-4 w-4 rounded accent-neon-500">
            Sedang dijual (tampil di halaman siswa)
        </label>

        <div class="flex justify-end gap-3 border-t border-stone-100 pt-6">
            <a href="{{ route('penjual.menu') }}" class="rounded-full px-5 py-3 text-sm font-semibold text-stone-500 transition hover:text-stone-900">Batal</a>
            <button type="submit" class="rounded-full bg-neon-500 px-7 py-3 text-sm font-semibold text-white transition hover:bg-neon-600">
                {{ $mengubah ? 'Simpan Perubahan' : 'Simpan Menu' }}
            </button>
        </div>
    </form>
@endsection
