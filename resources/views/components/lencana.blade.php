@props(['warna' => 'abu', 'label'])

<span @class([
    'inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-semibold',
    'bg-emerald-100 text-emerald-700' => $warna === 'hijau',
    'bg-red-100 text-red-700' => $warna === 'merah',
    'bg-amber-100 text-amber-700' => $warna === 'kuning',
    'bg-sky-100 text-sky-700' => $warna === 'biru',
    'bg-neon-100 text-neon-800' => $warna === 'jingga',
    'bg-stone-100 text-stone-600' => $warna === 'abu',
])>{{ $label }}</span>
