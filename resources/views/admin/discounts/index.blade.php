@extends('layouts.admin')

@section('title', 'Manajemen Diskon')

@section('content')
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="headline text-3xl text-stone-900">Manajemen Diskon</h1>
            <p class="mt-2 text-stone-500">Kode promo yang bisa dipakai siswa saat checkout.</p>
        </div>
        <a href="{{ route('admin.discounts.create') }}" class="rounded-full bg-neon-500 px-6 py-3 font-semibold text-white transition hover:bg-neon-600">
            Buat kode
        </a>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($discounts as $discount)
            <div class="flex flex-col rounded-2xl bg-white p-6 shadow-sm {{ $discount->is_active ? '' : 'opacity-60' }}">
                <div class="flex items-start justify-between gap-3">
                    <span class="headline rounded-lg bg-stone-950 px-3 py-1.5 text-lg tracking-wide text-white">{{ $discount->code }}</span>
                    @if ($discount->is_active)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-emerald-800">Aktif</span>
                    @else
                        <span class="rounded-full bg-stone-200 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-stone-600">Nonaktif</span>
                    @endif
                </div>

                <p class="mt-4 font-semibold text-stone-900">{{ $discount->label }}</p>

                <dl class="mt-3 space-y-1.5 text-sm text-stone-500">
                    <div class="flex justify-between gap-3">
                        <dt>Potongan</dt>
                        <dd class="font-semibold text-neon-700">Rp {{ number_format($discount->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt>Belanja minimal</dt>
                        <dd class="text-stone-700">Rp {{ number_format($discount->min_spend, 0, ',', '.') }}</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt>Sudah dipakai</dt>
                        <dd class="text-stone-700">{{ $discount->used_count }}x</dd>
                    </div>
                    <div class="flex justify-between gap-3">
                        <dt>Berlaku</dt>
                        <dd class="text-right text-stone-700">
                            {{ $discount->starts_at?->format('d/m/Y') ?? 'kapan saja' }}
                            &ndash;
                            {{ $discount->ends_at?->format('d/m/Y') ?? 'tanpa batas' }}
                        </dd>
                    </div>
                </dl>

                <div class="mt-5 flex flex-wrap gap-2 border-t border-stone-100 pt-4">
                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="rounded-lg border border-stone-200 px-3 py-1.5 text-sm transition hover:bg-stone-100">Ubah</a>
                    <form method="POST" action="{{ route('admin.discounts.toggle', $discount) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded-lg border border-stone-200 px-3 py-1.5 text-sm transition hover:bg-stone-100">
                            {{ $discount->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.discounts.destroy', $discount) }}"
                        onsubmit="return confirm('Hapus kode {{ $discount->code }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded-lg border border-red-200 px-3 py-1.5 text-sm text-red-600 transition hover:bg-red-50">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-stone-400">Belum ada kode promo.</p>
        @endforelse
    </div>
@endsection
