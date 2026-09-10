@extends('layouts.admin')

@section('title', 'Ringkasan')

@section('content')
    <h1 class="headline text-3xl text-stone-900">Ringkasan</h1>
    <p class="mt-2 text-stone-500">Keadaan kantin hari ini, {{ now()->translatedFormat('l d F Y') }}.</p>

    {{-- Tiga kartu angka --}}
    <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-3">
        @foreach ($stats as $stat)
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <p class="text-[11px] uppercase tracking-[0.18em] text-stone-500">{{ $stat['label'] }}</p>
                <p class="headline mt-2 text-3xl text-stone-900">{{ $stat['value'] }}</p>
                <p class="mt-1 text-sm text-stone-400">{{ $stat['note'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 xl:grid-cols-[1.6fr_1fr]">

        {{-- Transaksi terbaru --}}
        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="headline text-xl text-stone-900">Transaksi terbaru</h2>
                <a href="{{ route('admin.transactions.index') }}" class="text-sm font-semibold text-neon-700 underline-offset-4 hover:underline">
                    Lihat semua
                </a>
            </div>

            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="text-[11px] uppercase tracking-[0.16em] text-stone-400">
                        <tr class="border-b border-stone-100">
                            <th class="pb-3 pr-4 font-medium">Kode</th>
                            <th class="pb-3 pr-4 font-medium">Siswa</th>
                            <th class="pb-3 pr-4 font-medium">Porsi</th>
                            <th class="pb-3 pr-4 font-medium">Total</th>
                            <th class="pb-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentOrders as $order)
                            <tr class="border-b border-stone-50 last:border-0">
                                <td class="py-3 pr-4">
                                    <a href="{{ route('admin.transactions.show', $order) }}" class="font-semibold text-stone-900 hover:text-neon-700">
                                        {{ $order->code }}
                                    </a>
                                    <span class="block text-xs text-stone-400">{{ $order->created_at->format('d/m H:i') }}</span>
                                </td>
                                <td class="py-3 pr-4">
                                    {{ $order->student_name }}
                                    <span class="block text-xs text-stone-400">{{ $order->student_class }}</span>
                                </td>
                                <td class="py-3 pr-4 text-stone-500">{{ $order->items->sum('qty') }} porsi</td>
                                <td class="py-3 pr-4 font-semibold text-stone-900">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                <td class="py-3">
                                    <x-status-pill :status="$order->status" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        {{-- Menu terlaris --}}
        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="headline text-xl text-stone-900">Menu terlaris</h2>
            <p class="mt-1 text-sm text-stone-400">Dihitung dari seluruh pesanan yang tercatat.</p>

            <ol class="mt-4 space-y-3">
                @foreach ($topItems as $i => $item)
                    <li class="flex items-center gap-3">
                        <span class="headline w-6 text-lg text-neon-500">{{ $i + 1 }}</span>
                        <span class="flex-1">
                            <span class="block font-semibold text-stone-900">{{ $item->name }}</span>
                            <span class="block text-xs text-stone-400">{{ $item->stall }}</span>
                        </span>
                        <span class="text-right">
                            <span class="block font-semibold text-stone-900">{{ $item->total_qty }}x</span>
                            <span class="block text-xs text-stone-400">Rp {{ number_format($item->total_value, 0, ',', '.') }}</span>
                        </span>
                    </li>
                @endforeach
            </ol>
        </section>
    </div>
@endsection
