@extends('layouts.admin')

@section('title', $student->exists ? 'Ubah Siswa' : 'Tambah Siswa')

@section('content')
    <a href="{{ route('admin.students.index') }}" class="text-sm text-stone-500 hover:text-neon-700">&larr; Kembali ke daftar siswa</a>
    <h1 class="headline mt-3 text-3xl text-stone-900">{{ $student->exists ? 'Ubah Siswa' : 'Tambah Siswa' }}</h1>

    <form method="POST" action="{{ $student->exists ? route('admin.students.update', $student) : route('admin.students.store') }}"
        class="mt-6 max-w-2xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        @csrf
        @if ($student->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="nis" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">NIS</label>
                <input type="text" id="nis" name="nis" required value="{{ old('nis', $student->nis) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="class" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Kelas</label>
                <input type="text" id="class" name="class" required value="{{ old('class', $student->class) }}" placeholder="XII TKJ 1"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div class="sm:col-span-2">
                <label for="name" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Nama lengkap</label>
                <input type="text" id="name" name="name" required value="{{ old('name', $student->name) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div class="sm:col-span-2">
                <label for="email" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Email sekolah (opsional)</label>
                <input type="email" id="email" name="email" value="{{ old('email', $student->email) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="balance" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Saldo kartu (Rp)</label>
                <input type="number" id="balance" name="balance" required min="0" step="1000" value="{{ old('balance', $student->balance ?? 0) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <label class="flex items-end gap-2.5 pb-3 text-sm text-stone-600">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $student->is_active ?? true))
                    class="h-4 w-4 rounded border-stone-300 accent-neon-500">
                Akun aktif
            </label>
        </div>

        <div class="mt-7 flex gap-3">
            <button type="submit" class="rounded-full bg-neon-500 px-7 py-3 font-semibold text-white transition hover:bg-neon-600">
                {{ $student->exists ? 'Simpan perubahan' : 'Tambah siswa' }}
            </button>
            <a href="{{ route('admin.students.index') }}" class="rounded-full border-2 border-stone-300 px-7 py-3 font-semibold text-stone-600 transition hover:border-stone-800 hover:text-stone-900">
                Batal
            </a>
        </div>
    </form>
@endsection
