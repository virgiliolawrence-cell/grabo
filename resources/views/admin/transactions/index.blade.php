@extends('layouts.admin')

@section('title', 'Laporan Transaksi')

@section('content')
    <h1 class="headline text-3xl text-stone-900">Laporan Transaksi</h1>
    <p class="mt-2 text-stone-500">
        Rentang {{ \Carbon\Carbon::parse($dari)->translatedFormat('d M Y') }}
        &ndash; {{ \Carbon\Carbon::parse($sampai)->translatedFormat('d M Y') }}.
    </p>

    {{-- Penyaring --}}
    <form method="GET" class="mt-6 grid grid-cols-1 gap-3 rounded-2xl bg-white p-5 shadow-sm sm:grid-cols-2 lg:grid-cols-5">
        <label class="block">
            <span class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Dari</span>
            <input type="date" name="dari" value="{{ $dari }}"
                class="mt-1.5 w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 outline-none focus:border-neon-500">
        </label>
        <label class="block">
            <span class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Sampai</span>
            <input type="date" name="sampai" value="{{ $sampai }}"
                class="mt-1.5 w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 outline-none focus:border-neon-500">
        </label>
        <label class="block">
            <span class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Status</span>
            <select name="status" class="mt-1.5 w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 outline-none focus:border-neon-500">
                <option value="">Semua</option>
                @foreach (['menunggu', 'disiapkan', 'selesai', 'batal'] as $pilihan)
                    <option value="{{ $pilihan }}" @selected(request('status') === $pilihan)>{{ ucfirst($pilihan) }}</option>
                @endforeach
            </select>
        </label>
        <label class="block">
            <span class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Metode bayar</span>
            <select name="metode" class="mt-1.5 w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 outline-none focus:border-neon-500">
                <option value="">Semua</option>
                @foreach ($metodeBayar as $value => $label)
                    <option value="{{ $value }}" @selected(request('metode') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <div class="flex items-end gap-2">
            <button type="submit" class="rounded-xl bg-stone-900 px-5 py-2.5 font-semibold text-white transition hover:bg-stone-700">Terapkan</button>
            <a href="{{ route('admin.transactions.index') }}" class="rounded-xl border border-stone-200 px-4 py-2.5 text-stone-600 transition hover:bg-stone-100">Reset</a>
        </div>
    </form>

    {{-- Ringkasan rentang --}}
    <div class="mt-5 grid grid-cols-1 gap-5 lg:grid-cols-[2fr_1fr]">
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.18em] text-stone-500">Omzet (selesai)</p>
                <p class="headline mt-2 text-3xl text-stone-900">Rp {{ number_format($ringkasan['omzet_selesai'], 0, ',', '.') }}</p>
                <p class="mt-1 text-sm text-stone-400">Nilai semua status: Rp {{ number_format($ringkasan['omzet'], 0, ',', '.') }}</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.18em] text-stone-500">Jumlah transaksi</p>
                <p class="headline mt-2 text-3xl text-stone-900">{{ $ringkasan['jumlah'] }}</p>
                <p class="mt-1 text-sm text-stone-400">Total potongan promo Rp {{ number_format($ringkasan['diskon'], 0, ',', '.') }}</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="headline text-lg text-stone-900">Per metode bayar</h2>
            <ul class="mt-3 space-y-2 text-sm">
                @forelse ($perMetode as $baris)
                    <li class="flex items-center justify-between gap-3 border-b border-stone-50 pb-2 last:border-0">
                        <span class="text-stone-600">{{ $metodeBayar[$baris->payment_method] ?? $baris->payment_method }}</span>
                        <span class="text-right">
                            <span class="block font-semibold text-stone-900">Rp {{ number_format($baris->nilai, 0, ',', '.') }}</span>
                            <span class="block text-xs text-stone-400">{{ $baris->jumlah }} transaksi</span>
                        </span>
                    </li>
                @empty
                    <li class="text-stone-400">Belum ada data pada rentang ini.</li>
                @endforelse
            </ul>
        </div>
    </div>

    {{-- Tabel transaksi --}}
    <div class="mt-5 overflow-x-auto rounded-2xl bg-white p-2 shadow-sm">
        <table class="w-full min-w-[980px] text-left text-sm">
            <thead class="text-[11px] uppercase tracking-[0.16em] text-stone-400">
                <tr class="border-b border-stone-100">
                    <th class="p-4 font-medium">Kode</th>
                    <th class="p-4 font-medium">Waktu</th>
                    <th class="p-4 font-medium">Siswa</th>
                    <th class="p-4 font-medium">Item</th>
                    <th class="p-4 font-medium">Metode</th>
                    <th class="p-4 font-medium">Total</th>
                    <th class="p-4 font-medium">Status</th>
                    <th class="p-4 text-right font-medium">Ubah status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-b border-stone-50 last:border-0">
                        <td class="p-4">
                            <a href="{{ route('admin.transactions.show', $order) }}" class="font-semibold text-stone-900 hover:text-neon-700">{{ $order->code }}</a>
                        </td>
                        <td class="p-4 text-stone-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-4">
                            {{ $order->student_name }}
                            <span class="block text-xs text-stone-400">{{ $order->student_class }}</span>
                        </td>
                        <td class="p-4 text-stone-500">{{ $order->items->sum('qty') }} porsi</td>
                        <td class="p-4 text-stone-500">{{ $metodeBayar[$order->payment_method] ?? $order->payment_method }}</td>
                        <td class="p-4 font-semibold text-stone-900">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="p-4"><x-status-pill :status="$order->status" /></td>
                        <td class="p-4">
                            <form method="POST" action="{{ route('admin.transactions.status', $order) }}" class="flex justify-end gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="rounded-lg border border-stone-200 px-2 py-1.5 text-sm outline-none focus:border-neon-500">
                                    @foreach (['menunggu', 'disiapkan', 'selesai', 'batal'] as $pilihan)
                                        <option value="{{ $pilihan }}" @selected($order->status === $pilihan)>{{ ucfirst($pilihan) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="rounded-lg border border-stone-200 px-3 py-1.5 text-sm transition hover:bg-stone-100">Simpan</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="p-8 text-center text-stone-400">Tidak ada transaksi pada rentang ini.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $orders->links() }}</div>
@endsection
