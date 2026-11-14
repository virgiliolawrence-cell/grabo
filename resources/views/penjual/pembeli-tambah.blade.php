@extends('tataletak.penjual')

@section('title', 'Daftarkan Murid — Dasbor Penjual Grabo')

@php
    $kelasIsian = 'w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/15';
    $kelasLabel = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900';
@endphp

@section('content')
    <div class="mb-8 border-b-2 border-stone-900/10 pb-6">
        <a href="{{ route('penjual.pembeli') }}" class="text-xs uppercase tracking-[0.15em] text-stone-400 transition hover:text-neon-700">&larr; Data Pembeli</a>
        <h1 class="judul-besar mt-3 text-[clamp(2rem,4vw,2.75rem)] text-stone-900">Daftarkan Murid</h1>
        <p class="mt-3 text-sm text-stone-500">Buat akun supaya murid bisa masuk dan memesan dari kelas.</p>
    </div>

    <form method="POST" action="{{ route('penjual.pembeli.simpan') }}" class="max-w-2xl space-y-6 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        @csrf

        <div>
            <label for="nama" class="{{ $kelasLabel }}">Nama lengkap</label>
            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" class="{{ $kelasIsian }}">
            @error('nama') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="nis" class="{{ $kelasLabel }}">NIS</label>
                <input type="text" id="nis" name="nis" value="{{ old('nis') }}" placeholder="2026001" class="{{ $kelasIsian }}">
                @error('nis') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="kelas" class="{{ $kelasLabel }}">Kelas</label>
                <input type="text" id="kelas" name="kelas" value="{{ old('kelas') }}" placeholder="X RPL 1" class="{{ $kelasIsian }}">
                @error('kelas') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="{{ $kelasLabel }}">Email sekolah</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@grabo.sch.id" class="{{ $kelasIsian }}">
            @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="kata_sandi" class="{{ $kelasLabel }}">Kata sandi awal</label>
                <input type="password" id="kata_sandi" name="kata_sandi" autocomplete="new-password" class="{{ $kelasIsian }}">
                @error('kata_sandi') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="ulangi_kata_sandi" class="{{ $kelasLabel }}">Ulangi kata sandi</label>
                <input type="password" id="ulangi_kata_sandi" name="ulangi_kata_sandi" autocomplete="new-password" class="{{ $kelasIsian }}">
                @error('ulangi_kata_sandi') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-stone-100 pt-6">
            <a href="{{ route('penjual.pembeli') }}" class="rounded-full px-5 py-3 text-sm font-semibold text-stone-500 transition hover:text-stone-900">Batal</a>
            <button type="submit" class="rounded-full bg-neon-500 px-7 py-3 text-sm font-semibold text-white transition hover:bg-neon-600">Buat Akun</button>
        </div>
    </form>
@endsection
