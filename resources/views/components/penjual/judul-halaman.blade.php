@props([
    'judul',
    'keterangan' => null,
    'label' => 'Dasbor Penjual',
    'tautanAksi' => null,
    'teksAksi' => null,
    'ikonAksi' => 'tambah',
])

{{-- Kepala halaman penjual: judul + keterangan di kiri, tombol aksi di kanan. --}}
<div class="mb-8 flex flex-wrap items-end justify-between gap-4 border-b-2 border-stone-900/10 pb-6">
    <div class="max-w-2xl">
        <p class="mb-2 text-[11px] uppercase tracking-[0.2em] text-neon-700">{{ $label }}</p>
        <h1 class="judul-besar text-[clamp(2rem,4vw,2.75rem)] text-stone-900">{{ $judul }}</h1>
        @if ($keterangan)
            <p class="mt-3 text-sm leading-relaxed text-stone-500">{{ $keterangan }}</p>
        @endif
    </div>

    @if ($tautanAksi && $teksAksi)
        <a href="{{ $tautanAksi }}"
            class="inline-flex items-center gap-2 rounded-full bg-neon-500 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-neon-900/20 transition hover:-translate-y-0.5 hover:bg-neon-600">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                @if ($ikonAksi === 'panah')
                    <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                @else
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" />
                @endif
            </svg>
            {{ $teksAksi }}
        </a>
    @endif

    {{ $slot }}
</div>
