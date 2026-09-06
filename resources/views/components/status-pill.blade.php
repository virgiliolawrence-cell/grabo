@props(['status'])

@php
    $gaya = match ($status) {
        'selesai' => 'bg-emerald-100 text-emerald-800',
        'disiapkan' => 'bg-amber-100 text-amber-800',
        'batal' => 'bg-red-100 text-red-700',
        default => 'bg-stone-100 text-stone-600',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-block rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] {$gaya}"]) }}>
    {{ $status }}
</span>
