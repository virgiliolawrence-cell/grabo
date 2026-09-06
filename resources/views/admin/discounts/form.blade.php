@extends('layouts.admin')

@section('title', $discount->exists ? 'Ubah Kode Promo' : 'Buat Kode Promo')

@section('content')
    <a href="{{ route('admin.discounts.index') }}" class="text-sm text-stone-500 hover:text-neon-700">&larr; Kembali ke daftar diskon</a>
    <h1 class="headline mt-3 text-3xl text-stone-900">{{ $discount->exists ? 'Ubah Kode Promo' : 'Buat Kode Promo' }}</h1>

    <form method="POST" action="{{ $discount->exists ? route('admin.discounts.update', $discount) : route('admin.discounts.store') }}"
        class="mt-6 max-w-2xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
        @csrf
        @if ($discount->exists)
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="code" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Kode</label>
                <input type="text" id="code" name="code" required value="{{ old('code', $discount->code) }}" placeholder="HEMAT14"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 uppercase tracking-wide outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                <p class="mt-1.5 text-xs text-stone-400">Otomatis disimpan sebagai huruf besar.</p>
            </div>

            <div>
                <label for="label" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Nama promo</label>
                <input type="text" id="label" name="label" required value="{{ old('label', $discount->label) }}" placeholder="Diskon Rp 2.000"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="amount" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Potongan (Rp)</label>
                <input type="number" id="amount" name="amount" required min="0" step="500" value="{{ old('amount', $discount->amount ?? 0) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="min_spend" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Belanja minimal (Rp)</label>
                <input type="number" id="min_spend" name="min_spend" required min="0" step="1000" value="{{ old('min_spend', $discount->min_spend ?? 0) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="starts_at" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Mulai berlaku</label>
                <input type="date" id="starts_at" name="starts_at" value="{{ old('starts_at', $discount->starts_at?->format('Y-m-d')) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <div>
                <label for="ends_at" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Berakhir</label>
                <input type="date" id="ends_at" name="ends_at" value="{{ old('ends_at', $discount->ends_at?->format('Y-m-d')) }}"
                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
            </div>

            <label class="flex items-center gap-2.5 text-sm text-stone-600 sm:col-span-2">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $discount->is_active ?? true))
                    class="h-4 w-4 rounded border-stone-300 accent-neon-500">
                Kode bisa dipakai siswa sekarang
            </label>
        </div>

        <div class="mt-7 flex gap-3">
            <button type="submit" class="rounded-full bg-neon-500 px-7 py-3 font-semibold text-white transition hover:bg-neon-600">
                {{ $discount->exists ? 'Simpan perubahan' : 'Buat kode' }}
            </button>
            <a href="{{ route('admin.discounts.index') }}" class="rounded-full border-2 border-stone-300 px-7 py-3 font-semibold text-stone-600 transition hover:border-stone-800 hover:text-stone-900">
                Batal
            </a>
        </div>
    </form>
@endsection
