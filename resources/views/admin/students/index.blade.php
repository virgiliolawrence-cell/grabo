@extends('layouts.admin')

@section('title', 'Daftar Siswa')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="headline text-3xl text-stone-900">Daftar Siswa</h1>
            <p class="mt-2 text-stone-500">
                {{ $students->total() }} siswa terdaftar &middot; total saldo Rp {{ number_format($totalBalance, 0, ',', '.') }}
            </p>
        </div>
        <a href="{{ route('admin.students.create') }}" class="rounded-full bg-neon-500 px-6 py-3 font-semibold text-white transition hover:bg-neon-600">
            Tambah siswa
        </a>
    </div>

    <form method="GET" class="mt-6 flex flex-wrap gap-3">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIS"
            class="w-full max-w-xs rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none focus:border-neon-500 focus:ring-4 focus:ring-neon-500/20">
        <select name="kelas" class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 outline-none focus:border-neon-500">
            <option value="">Semua kelas</option>
            @foreach ($classes as $class)
                <option value="{{ $class }}" @selected(request('kelas') === $class)>{{ $class }}</option>
            @endforeach
        </select>
        <button type="submit" class="rounded-xl border-2 border-stone-800 px-5 py-2.5 font-semibold text-stone-800 transition hover:bg-stone-800 hover:text-white">
            Saring
        </button>
    </form>

    <div class="mt-5 overflow-x-auto rounded-2xl bg-white p-2 shadow-sm">
        <table class="w-full min-w-[860px] text-left text-sm">
            <thead class="text-[11px] uppercase tracking-[0.16em] text-stone-400">
                <tr class="border-b border-stone-100">
                    <th class="p-4 font-medium">NIS</th>
                    <th class="p-4 font-medium">Nama</th>
                    <th class="p-4 font-medium">Kelas</th>
                    <th class="p-4 font-medium">Saldo</th>
                    <th class="p-4 font-medium">Pesanan</th>
                    <th class="p-4 font-medium">Status</th>
                    <th class="p-4 text-right font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr class="border-b border-stone-50 last:border-0">
                        <td class="p-4 font-mono text-stone-500">{{ $student->nis }}</td>
                        <td class="p-4">
                            <span class="font-semibold text-stone-900">{{ $student->name }}</span>
                            @if ($student->email)
                                <span class="block text-xs text-stone-400">{{ $student->email }}</span>
                            @endif
                        </td>
                        <td class="p-4 text-stone-500">{{ $student->class }}</td>
                        <td class="p-4 font-semibold text-stone-900">Rp {{ number_format($student->balance, 0, ',', '.') }}</td>
                        <td class="p-4 text-stone-500">{{ $student->orders_count }}</td>
                        <td class="p-4">
                            @if ($student->is_active)
                                <span class="inline-block rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-800">Aktif</span>
                            @else
                                <span class="inline-block rounded-full bg-stone-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-stone-600">Nonaktif</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.students.edit', $student) }}" class="rounded-lg border border-stone-200 px-3 py-1.5 text-sm transition hover:bg-stone-100">Ubah</a>
                                <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                                    onsubmit="return confirm('Hapus {{ $student->name }} dari daftar?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-600 transition hover:bg-red-50">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="p-8 text-center text-stone-400">Tidak ada siswa yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $students->links() }}</div>
@endsection
