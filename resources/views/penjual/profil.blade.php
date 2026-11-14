@extends('tataletak.penjual')

@section('title', 'Profil Penjual — Dasbor Penjual Grabo')

@php
    $kelasIsian = 'w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/15';
    $kelasLabel = 'mb-1.5 block text-[11px] font-semibold uppercase tracking-[0.15em] text-stone-900';
@endphp

@section('content')
    <x-penjual.judul-halaman judul="Profil Penjual"
        keterangan="Identitas kantin yang tampil untuk siswa: nama, kontak, lokasi, dan jam layanan." />

    <div class="grid gap-6 lg:grid-cols-[18rem_1fr]">
        {{-- Kartu ringkas --}}
        <aside class="h-fit overflow-hidden rounded-2xl bg-white shadow-sm">
            <div class="kepala-situs relative px-6 py-8 text-white">
                <span class="inline-flex rounded-xl bg-white px-2 py-1.5">
                    <img src="{{ $graboLogo }}" alt="" class="h-12 w-auto" width="47" height="48">
                </span>
                <p class="judul-besar mt-5 text-2xl">{{ $profil->nama }}</p>
                <p class="mt-2 text-sm text-white/80">{{ $profil->pengelola }}</p>
            </div>
            <dl class="space-y-4 p-6 text-sm">
                <div>
                    <dt class="text-[10px] uppercase tracking-[0.18em] text-stone-400">Jam layanan</dt>
                    <dd class="mt-1 font-semibold text-stone-900">{{ $profil->jam_buka }} &ndash; {{ $profil->jam_tutup }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] uppercase tracking-[0.18em] text-stone-400">Lokasi</dt>
                    <dd class="mt-1 text-stone-700">{{ $profil->lokasi }}</dd>
                </div>
                <div>
                    <dt class="text-[10px] uppercase tracking-[0.18em] text-stone-400">Kontak</dt>
                    <dd class="mt-1 text-stone-700">{{ $profil->email }}<br>{{ $profil->telepon }}</dd>
                </div>
            </dl>
        </aside>

        <form method="POST" action="{{ route('penjual.profil.simpan') }}" class="space-y-6 rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            @csrf
            @method('PUT')

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="nama" class="{{ $kelasLabel }}">Nama kantin</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $profil->nama) }}" class="{{ $kelasIsian }}">
                    @error('nama') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="pengelola" class="{{ $kelasLabel }}">Pengelola</label>
                    <input type="text" id="pengelola" name="pengelola" value="{{ old('pengelola', $profil->pengelola) }}" class="{{ $kelasIsian }}">
                    @error('pengelola') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="{{ $kelasLabel }}">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $profil->email) }}" class="{{ $kelasIsian }}">
                    @error('email') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="telepon" class="{{ $kelasLabel }}">Telepon</label>
                    <input type="tel" id="telepon" name="telepon" value="{{ old('telepon', $profil->telepon) }}" class="{{ $kelasIsian }}">
                    @error('telepon') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="lokasi" class="{{ $kelasLabel }}">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" value="{{ old('lokasi', $profil->lokasi) }}" class="{{ $kelasIsian }}">
                @error('lokasi') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div>
                    <label for="jam_buka" class="{{ $kelasLabel }}">Jam buka</label>
                    <input type="text" id="jam_buka" name="jam_buka" value="{{ old('jam_buka', $profil->jam_buka) }}" placeholder="07.00" class="{{ $kelasIsian }}">
                    @error('jam_buka') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="jam_tutup" class="{{ $kelasLabel }}">Jam tutup</label>
                    <input type="text" id="jam_tutup" name="jam_tutup" value="{{ old('jam_tutup', $profil->jam_tutup) }}" placeholder="14.30" class="{{ $kelasIsian }}">
                    @error('jam_tutup') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label for="deskripsi" class="{{ $kelasLabel }}">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" class="{{ $kelasIsian }}">{{ old('deskripsi', $profil->deskripsi) }}</textarea>
                @error('deskripsi') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end border-t border-stone-100 pt-6">
                <button type="submit" class="rounded-full bg-neon-500 px-7 py-3 text-sm font-semibold text-white transition hover:bg-neon-600">Simpan Profil</button>
            </div>
        </form>
    </div>
@endsection
