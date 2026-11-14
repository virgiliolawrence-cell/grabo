@props(['pesanan', 'tampilkanPembeli' => true])

@php
    use App\Models\Pesanan;

    $jumlahSiap = $pesanan->item->where('sudah_disiapkan', true)->count();
@endphp

<article id="pesanan-{{ $pesanan->kode }}" class="scroll-mt-6 overflow-hidden rounded-2xl bg-white shadow-sm ring-1 ring-transparent target:ring-2 target:ring-neon-500">
    <header class="flex flex-wrap items-start justify-between gap-3 border-b border-stone-100 px-5 py-4">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('penjual.pembelian.rincian', $pesanan) }}" class="font-mono text-sm font-semibold text-stone-900 hover:text-neon-700">{{ $pesanan->kode }}</a>
                <x-lencana :warna="$pesanan->warnaStatus()" :label="$pesanan->labelStatus()" />
            </div>
            <p class="mt-1 text-xs text-stone-400">
                {{ $pesanan->created_at->translatedFormat('d M Y, H.i') }} &middot; Ambil {{ Pesanan::WAKTU_AMBIL[$pesanan->waktu_ambil] ?? $pesanan->waktu_ambil }}
            </p>
            @if ($tampilkanPembeli)
                <p class="mt-2 text-sm">
                    @if ($pesanan->pengguna_id)
                        <a href="{{ route('penjual.pembeli.rincian', $pesanan->pengguna_id) }}" class="font-semibold text-stone-900 hover:text-neon-700">{{ $pesanan->nama }}</a>
                    @else
                        <span class="font-semibold text-stone-900">{{ $pesanan->nama }}</span>
                    @endif
                    <span class="text-stone-400">&middot; {{ $pesanan->kelas }}</span>
                </p>
            @endif
        </div>
        <div class="text-right">
            <p class="judul-besar text-2xl text-neon-600">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
            <p class="mt-1 text-xs text-stone-400">{{ $pesanan->labelMetode() }}</p>
            @if ($pesanan->potongan)
                <p class="text-xs text-emerald-600">{{ $pesanan->kode_promo }} &minus;Rp {{ number_format($pesanan->potongan, 0, ',', '.') }}</p>
            @endif
        </div>
    </header>

    <div class="px-4 py-3">
        <div class="mb-1 flex items-center justify-between px-1.5">
            <p class="text-[10px] uppercase tracking-[0.18em] text-stone-400">Daftar siap</p>
            <p class="text-xs font-semibold {{ $jumlahSiap === $pesanan->item->count() ? 'text-emerald-600' : 'text-stone-500' }}">{{ $jumlahSiap }}/{{ $pesanan->item->count() }}</p>
        </div>
        @foreach ($pesanan->item as $item)
            <x-penjual.centang-item :pesanan="$pesanan" :item="$item" />
        @endforeach

        @if ($pesanan->catatan)
            <p class="mx-1.5 mt-2 rounded-lg bg-amber-50 px-3 py-2 text-xs text-amber-800">Catatan: &ldquo;{{ $pesanan->catatan }}&rdquo;</p>
        @endif
    </div>

    <footer class="flex flex-wrap items-center justify-end gap-2 border-t border-stone-100 bg-stone-50/60 px-5 py-3">
        @if ($pesanan->masihBerjalan())
            <form method="POST" action="{{ route('penjual.pembelian.status', $pesanan) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="batal">
                <button type="submit" onclick="return confirm('Batalkan pesanan {{ $pesanan->kode }}?')"
                    class="rounded-full px-4 py-2 text-xs font-semibold text-red-600 transition hover:bg-red-50">Batalkan</button>
            </form>
            <form method="POST" action="{{ route('penjual.pembelian.status', $pesanan) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="selesai">
                <button type="submit" class="rounded-full bg-stone-900 px-5 py-2 text-xs font-semibold text-white transition hover:bg-stone-700">
                    Serahkan &amp; selesai
                </button>
            </form>
        @else
            <p class="text-xs text-stone-400">Pesanan sudah ditutup.</p>
        @endif
    </footer>
</article>
