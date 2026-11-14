@extends('tataletak.penjual')

@section('title', 'Tambah Diskon — Dasbor Penjual Grabo')

@php
    $kelasIsian = 'w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/15';
@endphp

@section('content')
    <div class="mb-8 border-b-2 border-stone-900/10 pb-6">
        <a href="{{ route('penjual.diskon') }}" class="text-xs uppercase tracking-[0.15em] text-stone-400 transition hover:text-neon-700">&larr; Manajemen Diskon</a>
        <h1 class="judul-besar mt-3 text-[clamp(2rem,4vw,2.75rem)] text-stone-900">Tambah Diskon</h1>
        <p class="mt-3 text-sm text-stone-500">Kode baru langsung aktif dan bisa dipakai murid di keranjang.</p>
    </div>

    <form method="POST" action="{{ route('penjual.diskon.simpan') }}" class="max-w-2xl space-y-6 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        @csrf

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="kode" class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900">Kode promo</label>
                <input type="text" id="kode" name="kode" value="{{ old('kode') }}" maxlength="12" placeholder="Contoh: KENYANG10" class="{{ $kelasIsian }} font-mono uppercase">
                @error('kode') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="label" class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900">Nama promo</label>
                <input type="text" id="label" name="label" value="{{ old('label') }}" placeholder="Contoh: Promo akhir pekan" class="{{ $kelasIsian }}">
                @error('label') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2">
            <div>
                <label for="potongan" class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900">Potongan (Rp)</label>
                <input type="number" id="potongan" name="potongan" value="{{ old('potongan') }}" min="500" step="500" placeholder="2000" class="{{ $kelasIsian }}">
                @error('potongan') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="belanja_minimal" class="mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900">Belanja minimal (Rp)</label>
                <input type="number" id="belanja_minimal" name="belanja_minimal" value="{{ old('belanja_minimal') }}" min="0" step="500" placeholder="15000" class="{{ $kelasIsian }}">
                @error('belanja_minimal') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-stone-100 pt-6">
            <a href="{{ route('penjual.diskon') }}" class="rounded-full px-5 py-3 text-sm font-semibold text-stone-500 transition hover:text-stone-900">Batal</a>
            <button type="submit" class="rounded-full bg-neon-500 px-7 py-3 text-sm font-semibold text-white transition hover:bg-neon-600">Simpan Diskon</button>
        </div>
    </form>
@endsection
