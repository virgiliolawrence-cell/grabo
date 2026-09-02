@extends('layouts.grabo')

@section('title', 'Pesanan Diterima — Grabo')
@section('description', 'Pesanan kantin berhasil dikirim. Tunjukkan kode pesanan saat mengambil di loket.')

@section('content')
    @php
        $labelMetode = [
            'tunai' => 'Tunai di loket',
            'saldo' => 'Saldo kartu pelajar',
            'qris' => 'QRIS',
            'transfer' => 'Transfer bank ' . ($pesanan['bank'] ?? ''),
            'ewallet' => 'E-wallet ' . ($pesanan['ewallet'] ?? ''),
        ];

        $labelWaktu = [
            'sekarang' => 'Secepatnya',
            'istirahat-1' => 'Istirahat 1 &middot; pukul 09.30',
            'istirahat-2' => 'Istirahat 2 &middot; pukul 12.00',
        ];

        $online = in_array($pesanan['metode'], ['qris', 'transfer', 'ewallet'], true);
    @endphp

    <section class="bg-cream px-4 py-12 sm:px-6 lg:py-16">
        <div class="mx-auto max-w-3xl">

            <div class="rounded-2xl bg-white p-7 text-center shadow-sm sm:p-10">
                {{-- Lingkaran dan centangnya digambar berurutan lewat kelas di app.css. --}}
                <svg viewBox="0 0 60 60" class="mx-auto h-16 w-16 text-emerald-600" aria-hidden="true">
                    <circle cx="30" cy="30" r="26.5" fill="none" stroke="currentColor" stroke-width="3.5"
                        stroke-linecap="round" class="check-ring" transform="rotate(-90 30 30)" />
                    <path d="M18.5 30.5 L26.5 38.5 L41.5 22.5" fill="none" stroke="currentColor" stroke-width="4"
                        stroke-linecap="round" stroke-linejoin="round" class="check-mark" />
                </svg>

                <h1 class="mt-5 headline text-[clamp(1.8rem,4vw,2.75rem)] text-stone-900">Pesanan diterima</h1>
                <p class="mx-auto mt-3 max-w-md leading-relaxed text-stone-500">
                    Terima kasih, {{ Str::before($pesanan['nama'], ' ') }}. Pesananmu sudah diteruskan ke stan kantin.
                    Tunjukkan kode di bawah ini ke petugas saat mengambil.
                </p>

                <div class="mx-auto mt-6 w-full max-w-xs rounded-2xl border-2 border-dashed border-neon-300 bg-neon-50 px-6 py-5">
                    <p class="text-[11px] uppercase tracking-[0.2em] text-neon-700">Kode pesanan</p>
                    <p id="orderCode" class="headline mt-1 text-3xl tracking-[0.08em] text-neon-800">{{ $pesanan['kode'] }}</p>
                    <button type="button" id="copyOrderCode"
                        class="mt-2 text-sm text-neon-700 underline-offset-4 transition hover:underline">
                        Salin kode
                    </button>
                </div>
            </div>

            {{--
                Ringkasan item diambil dari keranjang di localStorage, karena
                pesanannya belum tersimpan di database.
            --}}
            <div id="orderItemsCard" class="mt-6 hidden rounded-2xl bg-white p-7 shadow-sm sm:p-8">
                <h2 class="headline text-xl text-stone-900">Item yang dipesan</h2>
                <div id="orderItems" class="mt-5 space-y-3"></div>
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

            {{-- Langkah selanjutnya --}}
            <div class="mt-6 rounded-2xl bg-white p-7 shadow-sm sm:p-8">
                <h2 class="headline text-xl text-stone-900">Langkah selanjutnya</h2>

                <ol class="mt-5 space-y-5">
                    <li class="flex gap-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                <path d="m20 6-11 11-5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold text-stone-900">Pesanan tercatat</p>
                            <p class="mt-0.5 text-sm text-stone-500">Kode {{ $pesanan['kode'] }} sudah dibuat dan diteruskan ke stan.</p>
                        </div>
                    </li>

                    <li class="flex gap-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-neon-50 text-neon-600">
                            @if ($online)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                    <rect x="2" y="5" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.8" />
                                    <path d="M2 10h20" stroke="currentColor" stroke-width="1.8" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                    <path d="M4 20h16M6 20v-6a6 6 0 0 1 12 0v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M9 6c0-1 .8-1.5.8-2.5M12 6c0-1 .8-1.5.8-2.5M15 6c0-1 .8-1.5.8-2.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                                </svg>
                            @endif
                        </span>
                        <div>
                            <p class="font-semibold text-stone-900">
                                {{ $online ? 'Selesaikan pembayaran' : 'Stan mulai menyiapkan' }}
                            </p>
                            <p class="mt-0.5 text-sm text-stone-500">
                                {{ $online
                                    ? 'Ikuti cara membayar di atas. Pesanan baru dimasak setelah pembayaran masuk.'
                                    : 'Pesanan langsung masuk antrean masak. Siapkan pembayaran saat mengambil.' }}
                            </p>
                        </div>
                    </li>

                    <li class="flex gap-4">
                        <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-stone-100 text-stone-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                <path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                                <path d="m3.3 7 8.7 5 8.7-5M12 22V12" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <div>
                            <p class="font-semibold text-stone-900">Ambil di loket</p>
                            <p class="mt-0.5 text-sm text-stone-500">{!! $labelWaktu[$pesanan['waktu']] !!} &mdash; sebutkan kode pesanan ke petugas.</p>
                        </div>
                    </li>
                </ol>
            </div>

            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('menu') }}" class="rounded-full bg-neon-500 px-7 py-3.5 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.45)] transition hover:-translate-y-0.5 hover:bg-neon-600">
                    Kembali Belanja
                </a>
                <a href="{{ route('home') }}" class="rounded-full border-2 border-stone-300 px-7 py-3.5 font-semibold text-stone-700 transition hover:border-stone-800 hover:text-stone-900">
                    Kembali ke beranda
                </a>
            </div>
        </div>
    </section>

    <script>
        /*
         * Skrip ini berjalan sebelum partials/scripts.blade.php, jadi
         * pemformat rupiahnya dibuat sendiri di sini.
         */
        (function () {
            const format = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

            let items = [];

            try {
                const raw = JSON.parse(localStorage.getItem('grabo.cart'));
                items = Array.isArray(raw) ? raw : [];
            } catch (error) {
                // localStorage diblokir: ringkasan item dilewati saja.
            }

            const box = document.getElementById('orderItems');
            const card = document.getElementById('orderItemsCard');

            if (box && items.length > 0) {
                card.classList.remove('hidden');

                items.forEach((item) => {
                    const row = document.createElement('div');
                    row.className = 'flex items-start justify-between gap-4 border-b border-stone-100 pb-3 last:border-0 last:pb-0';
                    row.innerHTML = `
                        <div class="flex-1">
                            <p class="font-semibold text-stone-900">${item.qty}&times; ${item.name}</p>
                            <p class="text-xs uppercase tracking-[0.14em] text-stone-400">${item.stall}</p>
                            ${item.options ? `<p class="mt-1 text-sm text-stone-500">${item.options}</p>` : ''}
                            ${item.note ? `<p class="mt-0.5 text-sm italic text-stone-400">&ldquo;${item.note}&rdquo;</p>` : ''}
                        </div>
                        <span class="shrink-0 text-stone-900">${format(item.price * item.qty)}</span>`;
                    box.appendChild(row);
                });
            }

            // Ringkasannya sudah tergambar, jadi keranjang dan promonya boleh dikosongkan.
            try {
                localStorage.removeItem('grabo.cart');
                localStorage.removeItem('grabo.promo');
            } catch (error) {
                // Abaikan bila localStorage diblokir.
            }

            const copyButton = document.getElementById('copyOrderCode');

            copyButton?.addEventListener('click', () => {
                const code = document.getElementById('orderCode').textContent.trim();

                navigator.clipboard?.writeText(code).then(
                    () => { copyButton.textContent = 'Kode tersalin'; },
                    () => { copyButton.textContent = 'Salin manual: ' + code; },
                );
            });
        })();
    </script>
@endsection
