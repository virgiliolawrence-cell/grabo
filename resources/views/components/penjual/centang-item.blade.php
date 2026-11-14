@props(['pesanan', 'item'])

{{-- Satu menu dalam pesanan sebagai kotak centang. Klik = tandai sudah/belum disiapkan. --}}
<form method="POST" action="{{ route('penjual.pembelian.centang', [$pesanan, $item]) }}">
    @csrf
    @method('PATCH')
    <button type="submit" @disabled(! $pesanan->masihBerjalan())
        aria-pressed="{{ $item->sudah_disiapkan ? 'true' : 'false' }}"
        title="{{ $item->sudah_disiapkan ? 'Batalkan centang' : 'Tandai sudah disiapkan' }}"
        class="group flex w-full items-start gap-2.5 rounded-lg px-1.5 py-1 text-left transition enabled:hover:bg-neon-50 disabled:cursor-default">
        <span @class([
            'mt-0.5 flex h-[18px] w-[18px] shrink-0 items-center justify-center rounded-md border-2 transition',
            'border-emerald-500 bg-emerald-500 text-white' => $item->sudah_disiapkan,
            'border-stone-300 bg-white text-transparent group-enabled:group-hover:border-neon-500' => ! $item->sudah_disiapkan,
        ])>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-3 w-3" aria-hidden="true">
                <path d="M5 12.5l4.5 4.5L19 7.5" stroke="currentColor" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </span>
        <span class="min-w-0">
            <span @class(['block text-sm', 'text-stone-400 line-through' => $item->sudah_disiapkan, 'text-stone-800' => ! $item->sudah_disiapkan])>
                <strong class="font-semibold">{{ $item->jumlah }}&times;</strong> {{ $item->nama }}
            </span>
            @if ($item->pilihan || $item->catatan)
                <span class="block text-xs text-stone-400">
                    {{ $item->pilihan }}@if ($item->pilihan && $item->catatan) &middot; @endif @if ($item->catatan)<em>&ldquo;{{ $item->catatan }}&rdquo;</em>@endif
                </span>
            @endif
        </span>
    </button>
</form>
