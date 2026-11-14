@extends('tataletak.penjual')

@section('title', "Pesanan {$pesanan->kode} — Dasbor Penjual Grabo")

@section('content')
    <div class="mb-8 border-b-2 border-stone-900/10 pb-6">
        <a href="{{ route('penjual.pembelian') }}" class="text-xs uppercase tracking-[0.15em] text-stone-400 transition hover:text-neon-700">&larr; Manajemen Pembelian</a>
        <h1 class="judul-besar mt-3 text-[clamp(2rem,4vw,2.75rem)] text-stone-900">Pesanan {{ $pesanan->kode }}</h1>
        <p class="mt-3 text-sm text-stone-500">
            Dari <a href="{{ route('penjual.pembeli.rincian', $pesanan->pengguna_id) }}" class="font-semibold text-stone-900 hover:text-neon-700">{{ $pesanan->nama }}</a>
            ({{ $pesanan->kelas }}@if ($pesanan->pengguna?->nis) &middot; NIS {{ $pesanan->pengguna->nis }}@endif).
        </p>
    </div>

    <div class="max-w-2xl">
        <x-penjual.kartu-pesanan :pesanan="$pesanan" :tampilkan-pembeli="false" />
    </div>
@endsection
