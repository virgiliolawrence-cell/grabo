@extends('layouts.grabo')

@section('title', 'Pesanan Diterima — Grabo')
@section('description', 'Pesanan kantin berhasil dikirim. Tunjukkan kode pesanan saat mengambil di loket.')

@section('content')

    <section class="bg-cream px-4 py-12 sm:px-6 lg:py-16">
        <div class="mx-auto max-w-3xl">

            <div class="rounded-2xl bg-white p-7 text-center shadow-sm sm:p-10">
                <span class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-neon-50 text-neon-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-8 w-8" aria-hidden="true">
                        <path d="M5 13l4 4L19 7" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>

                <h1 class="mt-5 headline text-[clamp(1.8rem,4vw,2.75rem)] text-stone-900">Pesanan diterima</h1>
                <p class="mt-3 leading-relaxed text-stone-500">
                    Tunjukkan kode di bawah ini ke petugas saat mengambil pesanan.
                </p>

                <div class="mx-auto mt-6 w-full max-w-xs rounded-2xl border-2 border-dashed border-neon-300 bg-neon-50 px-6 py-5">
                    <p class="text-[11px] uppercase tracking-[0.2em] text-neon-700">Kode pesanan</p>
                    <p class="headline mt-1 text-3xl tracking-[0.08em] text-neon-800">{{ $pesanan['kode'] }}</p>
                </div>
            </div>

            {{-- Rincian --}}
            <div class="mt-6 rounded-2xl bg-white p-7 shadow-sm sm:p-8">
                <h2 class="headline text-xl text-stone-900">Rincian</h2>

                <dl class="mt-5 space-y-3">
                    <div class="flex justify-between gap-6 border-b border-stone-100 pb-3">
                        <dt class="text-stone-500">Nama</dt>
                        <dd class="text-right font-semibold text-stone-900">{{ $pesanan['nama'] }} &middot; {{ $pesanan['kelas'] }}</dd>
                    </div>
                    <div class="flex justify-between gap-6 border-b border-stone-100 pb-3">
                        <dt class="text-stone-500">Diambil</dt>
                        <dd class="text-right font-semibold text-stone-900">{!! $labelWaktu[$pesanan['waktu']] !!}</dd>
                    </div>
                    <div class="flex justify-between gap-6 border-b border-stone-100 pb-3">
                        <dt class="text-stone-500">Metode bayar</dt>
                        <dd class="text-right font-semibold text-stone-900">{{ trim($labelMetode[$pesanan['metode']]) }}</dd>
                    </div>
                    <div class="flex justify-between gap-6">
                        <dt class="text-stone-500">Total</dt>
                        <dd class="headline text-right text-2xl text-neon-600">Rp {{ number_format($pesanan['total'], 0, ',', '.') }}</dd>
                    </div>
                </dl>

                @if (! empty($pesanan['catatan']))
                    <p class="mt-5 rounded-xl bg-stone-50 px-4 py-3 text-sm italic text-stone-500">
                        &ldquo;{{ $pesanan['catatan'] }}&rdquo;
                    </p>
                @endif
            </div>

            {{-- Instruksi sesuai metode --}}
            <div class="mt-6 rounded-2xl bg-white p-7 shadow-sm sm:p-8">
                <h2 class="headline text-xl text-stone-900">Cara membayar</h2>

                @if ($pesanan['metode'] === 'tunai')
                    <p class="mt-4 leading-relaxed text-stone-500">
                        Siapkan uang pas sebesar <strong class="text-stone-900">Rp {{ number_format($pesanan['total'], 0, ',', '.') }}</strong>
                        dan bayar ke petugas saat mengambil pesanan di loket.
                    </p>
                @elseif ($pesanan['metode'] === 'saldo')
                    <p class="mt-4 leading-relaxed text-stone-500">
                        Saldo kartu pelajarmu akan dipotong otomatis ketika pesanan diserahkan.
                        Bawa kartu pelajar saat mengambil.
                    </p>
                @elseif ($pesanan['metode'] === 'qris')
                    <div class="mt-4 flex flex-col items-center gap-4 sm:flex-row sm:items-start">
                        {{-- Kode QR contoh, bukan kode pembayaran sungguhan --}}
                        <svg viewBox="0 0 100 100" class="h-40 w-40 shrink-0 rounded-xl border border-stone-200 bg-white p-2" role="img" aria-label="Contoh kode QR">
                            <rect x="4" y="4" width="26" height="26" fill="none" stroke="#292524" stroke-width="6" />
                            <rect x="70" y="4" width="26" height="26" fill="none" stroke="#292524" stroke-width="6" />
                            <rect x="4" y="70" width="26" height="26" fill="none" stroke="#292524" stroke-width="6" />
                            <g fill="#292524">
                                <rect x="40" y="8" width="8" height="8" /><rect x="52" y="8" width="8" height="8" />
                                <rect x="40" y="20" width="8" height="8" /><rect x="60" y="24" width="8" height="8" />
                                <rect x="8" y="40" width="8" height="8" /><rect x="20" y="40" width="8" height="8" />
                                <rect x="40" y="40" width="8" height="8" /><rect x="56" y="40" width="8" height="8" />
                                <rect x="72" y="40" width="8" height="8" /><rect x="88" y="44" width="8" height="8" />
                                <rect x="40" y="56" width="8" height="8" /><rect x="60" y="56" width="8" height="8" />
                                <rect x="80" y="60" width="8" height="8" /><rect x="44" y="72" width="8" height="8" />
                                <rect x="60" y="76" width="8" height="8" /><rect x="76" y="80" width="8" height="8" />
                                <rect x="88" y="68" width="8" height="8" /><rect x="52" y="88" width="8" height="8" />
                            </g>
                        </svg>

                        <div>
                            <p class="leading-relaxed text-stone-500">
                                Pindai kode ini dari aplikasi bank atau e-wallet, lalu bayar
                                <strong class="text-stone-900">Rp {{ number_format($pesanan['total'], 0, ',', '.') }}</strong>.
                            </p>
                            <p class="mt-2 text-sm text-neon-700">Kode berlaku 15 menit.</p>
                            <p class="mt-3 text-sm text-stone-400">
                                Ini kode contoh &mdash; gerbang pembayaran belum tersambung.
                            </p>
                        </div>
                    </div>
                @elseif ($pesanan['metode'] === 'transfer')
                    <p class="mt-4 leading-relaxed text-stone-500">Transfer ke nomor virtual account berikut:</p>
                    <div class="mt-3 rounded-xl border border-stone-200 bg-stone-50 px-5 py-4">
                        <p class="text-[11px] uppercase tracking-[0.18em] text-stone-500">{{ $pesanan['bank'] ?? 'Bank' }} Virtual Account</p>
                        <p class="headline mt-1 text-2xl tracking-[0.06em] text-stone-900">8808 {{ substr(preg_replace('/\D/', '', $pesanan['kode'] . '00000000'), 0, 8) }}</p>
                    </div>
                    <p class="mt-3 text-sm text-stone-400">Nomor contoh &mdash; belum terhubung ke bank.</p>
                @else
                    <p class="mt-4 leading-relaxed text-stone-500">
                        Buka aplikasi <strong class="text-stone-900">{{ $pesanan['ewallet'] ?? 'e-wallet' }}</strong>,
                        lalu setujui tagihan sebesar
                        <strong class="text-stone-900">Rp {{ number_format($pesanan['total'], 0, ',', '.') }}</strong>.
                    </p>
                    <p class="mt-3 text-sm text-stone-400">Tagihan contoh &mdash; belum terhubung ke penyedia e-wallet.</p>
                @endif
            </div>

            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('menu') }}" class="rounded-full bg-neon-500 px-7 py-3.5 font-semibold text-white transition hover:bg-neon-600">
                    Pesan lagi
                </a>
                <a href="{{ route('home') }}" class="rounded-full border-2 border-stone-300 px-7 py-3.5 font-semibold text-stone-700 transition hover:border-stone-800 hover:text-stone-900">
                    Kembali ke beranda
                </a>
            </div>
        </div>
    </section>

    <script>
        // Pesanan sudah dikirim, jadi keranjang dan kode promonya dikosongkan.
        try {
            localStorage.removeItem('grabo.cart');
            localStorage.removeItem('grabo.promo');
        } catch (error) {
            // Abaikan bila localStorage diblokir.
        }

        document.querySelectorAll('[data-cart-count]').forEach((el) => {
            el.textContent = '0';
        });
    </script>
@endsection
