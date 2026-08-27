@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="mb-8 border-b border-[#E5E3DB] pb-5">
        <a href="{{ route('classes.index') }}" class="text-xs uppercase tracking-[0.15em] text-slate-400 hover:text-[#A16207]">&larr; Buku
            Induk</a>
        <h1 class="font-display mt-2 text-3xl font-semibold text-[#16213A]">Catat Kelas Baru</h1>
        <p class="mt-1 text-sm text-slate-500">Isi data untuk mendaftarkan kelas ke buku induk.</p>
    </div>

    <form action="" method="POST" class="space-y-6 border border-[#E5E3DB] bg-white p-8">

        <div>
            <label for="name" class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Nama
                Kelas</label>
            <input type="text" id="name" name="name" placeholder="Contoh: X AKL 1"
                class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">
        </div>

        <div>
            <label for="grade"
                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Tingkat</label>
            <input type="text" id="grade" name="grade" placeholder="Contoh: X"
                class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm placeholder:text-slate-400 focus:border-[#A16207] focus:bg-white focus:outline-none">
        </div>

        <div>
            <label for="major"
                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Jurusan</label>
            <select id="major" name="major"
                class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
                <option value="">Pilih jurusan</option>
                <option value="AKL">Akuntansi dan Keuangan Lembaga</option>
                <option value="TKJ">Teknik Komputer dan Jaringan</option>
                <option value="BiD">Bisnis Digital</option>
            </select>
        </div>

        <div>
            <label for="homeroom_teacher"
                class="mb-1.5 block text-xs font-semibold uppercase tracking-[0.1em] text-[#16213A]">Wali Kelas</label>
            <select id="homeroom_teacher" name="homeroom_teacher"
                class="w-full border border-[#D9D6CD] bg-[#FCFBF8] px-3.5 py-2.5 text-sm focus:border-[#A16207] focus:bg-white focus:outline-none">
                <option value="">Pilih wali kelas</option>
                <option value="1">Budi Santoso</option>
                <option value="2">Siti Aminah</option>
            </select>
        </div>

        <div class="flex justify-end gap-4 border-t border-[#EFEDE6] pt-6">
            <a href="{{ route('classes.index') }}" class="px-4 py-2.5 text-sm font-medium text-slate-500 hover:text-[#16213A]">Batal</a>
            <button type="submit"
                class="bg-[#16213A] px-6 py-2.5 text-sm font-medium text-white transition hover:bg-[#26324f]">Simpan
                ke Buku Induk</button>
        </div>
    </form>
@endsection