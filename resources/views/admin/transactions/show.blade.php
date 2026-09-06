@extends('layouts.admin')

@section('title', 'Transaksi ' . $order->code)

@section('content')
    <a href="{{ route('admin.transactions.index') }}" class="text-sm text-stone-500 hover:text-neon-700">&larr; Kembali ke laporan</a>

    <div class="mt-3 flex flex-wrap items-center gap-4">
        <h1 class="headline text-3xl text-stone-900">{{ $order->code }}</h1>
        <x-status-pill :status="$order->status" />
    </div>
    <p class="mt-2 text-stone-500">Dibuat {{ $order->created_at->translatedFormat('l, d F Y H:i') }}</p>

    <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-[1.5fr_1fr]">

        {{-- Rincian item --}}
        <section class="rounded-2xl bg-white p-6 shadow-sm">
            <h2 class="headline text-xl text-stone-900">Rincian pesanan</h2>

            <ul class="mt-4 divide-y divide-stone-100">
                @foreach ($order->items as $item)
                    <li class="flex items-start justify-between gap-4 py-3">
                        <div>
                            <p class="font-semibold text-stone-900">{{ $item->name }}</p>
                            <p class="text-xs text-stone-400">{{ $item->stall }}</p>
                            @if ($item->options)
                                <p class="mt-1 text-xs text-stone-500">{{ $item->options }}</p>
                            @endif
                            @if ($item->note)
                                <p class="mt-1 text-xs italic text-stone-500">Catatan: {{ $item->note }}</p>
                            @endif
                        </div>
                        <div class="shrink-0 text-right">
                            <p class="font-semibold text-stone-900">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</p>
                            <p class="text-xs text-stone-400">{{ $item->qty }} &times; Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>

            <dl class="mt-4 space-y-2 border-t border-stone-100 pt-4 text-sm">
                <div class="flex justify-between">
                    <dt class="text-stone-500">Subtotal</dt>
                    <dd class="text-stone-900">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</dd>
                </div>
                @if ($order->discount_amount > 0)
                    <div class="flex justify-between">
                        <dt class="text-stone-500">Potongan {{ $order->discount?->code ? "({$order->discount->code})" : '' }}</dt>
                        <dd class="text-neon-700">&minus; Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</dd>
                    </div>
                @endif
                <div class="flex justify-between border-t border-stone-100 pt-2 text-base">
                    <dt class="font-semibold text-stone-900">Total</dt>
                    <dd class="headline text-xl text-stone-900">Rp {{ number_format($order->total, 0, ',', '.') }}</dd>
                </div>
            </dl>
        </section>

        {{-- Info siswa & pembayaran --}}
        <section class="space-y-5">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="headline text-lg text-stone-900">Pemesan</h2>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between gap-3"><dt class="text-stone-500">Nama</dt><dd class="text-stone-900">{{ $order->student_name }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-stone-500">Kelas</dt><dd class="text-stone-900">{{ $order->student_class }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-stone-500">Ambil pukul</dt><dd class="text-stone-900">{{ $order->pickup_slot ?: '-' }}</dd></div>
                </dl>
            </div>

            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <h2 class="headline text-lg text-stone-900">Pembayaran</h2>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between gap-3">
                        <dt class="text-stone-500">Metode</dt>
                        <dd class="text-stone-900">{{ $metodeBayar[$order->payment_method] ?? $order->payment_method }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-stone-500">Rincian</dt>
                        <dd class="text-right text-stone-900">{{ $order->payment_detail ?: '-' }}</dd>
                    </div>
                </dl>

                <form method="POST" action="{{ route('admin.transactions.status', $order) }}" class="mt-5 flex gap-2 border-t border-stone-100 pt-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="flex-1 rounded-xl border border-stone-200 px-3 py-2.5 text-sm outline-none focus:border-neon-500">
                        @foreach (['menunggu', 'disiapkan', 'selesai', 'batal'] as $pilihan)
                            <option value="{{ $pilihan }}" @selected($order->status === $pilihan)>{{ ucfirst($pilihan) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-xl bg-neon-500 px-5 py-2.5 font-semibold text-white transition hover:bg-neon-600">Simpan</button>
                </form>
            </div>

            @if ($order->note)
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <h2 class="headline text-lg text-stone-900">Catatan</h2>
                    <p class="mt-2 text-sm text-stone-600">{{ $order->note }}</p>
                </div>
            @endif
        </section>
    </div>
@endsection
