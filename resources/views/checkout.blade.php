@extends('layouts.grabo')

@section('title', 'Pembayaran — Grabo')
@section('description', 'Selesaikan pesanan kantin: pilih waktu pengambilan dan metode pembayaran, tunai di loket maupun online.')

@section('content')
    @php
        $waktuPilihan = [
            ['value' => 'sekarang', 'label' => 'Secepatnya', 'note' => 'Disiapkan begitu stan menerima pesanan'],
            ['value' => 'istirahat-1', 'label' => 'Istirahat 1', 'note' => 'Siap diambil pukul 09.30'],
            ['value' => 'istirahat-2', 'label' => 'Istirahat 2', 'note' => 'Siap diambil pukul 12.00'],
        ];

        $metodeTempat = [
            [
                'value' => 'tunai',
                'label' => 'Tunai di loket',
                'note' => 'Bayar langsung ke petugas saat mengambil pesanan.',
                'badge' => null,
            ],
            [
                'value' => 'saldo',
                'label' => 'Saldo kartu pelajar',
                'note' => 'Saldo dipotong otomatis saat pesanan diserahkan.',
                'badge' => 'Tanpa uang kembalian',
            ],
        ];

        $metodeOnline = [
            [
                'value' => 'qris',
                'label' => 'QRIS',
                'note' => 'Bayar dari aplikasi bank atau e-wallet apa pun.',
                'badge' => 'Paling cepat',
            ],
            [
                'value' => 'transfer',
                'label' => 'Transfer bank',
                'note' => 'Nomor virtual account muncul setelah pesanan dikirim.',
                'badge' => null,
            ],
            [
                'value' => 'ewallet',
                'label' => 'E-wallet',
                'note' => 'GoPay, OVO, atau DANA yang terhubung ke akun sekolah.',
                'badge' => null,
            ],
        ];
    @endphp

    <section class="bg-cream px-4 py-10 sm:px-6 lg:py-14">
        <div class="mx-auto max-w-7xl">
            <a href="{{ route('menu') }}" class="inline-flex items-center gap-2 text-sm text-stone-500 transition hover:text-neon-700">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                    <path d="M15 5l-7 7 7 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Kembali ke menu
            </a>

            <h1 class="mt-4 headline text-[clamp(2rem,4.5vw,3.25rem)] text-stone-900">Pembayaran</h1>
            <p class="mt-3 max-w-2xl leading-relaxed text-stone-500">
                Periksa pesananmu, tentukan kapan mau diambil, lalu pilih cara membayar.
                Bisa bayar tunai di loket atau lewat pembayaran online.
            </p>

            {{-- Keranjang kosong: tidak ada yang bisa dibayar --}}
            <div id="checkoutEmpty" class="mt-10 hidden rounded-2xl border border-dashed border-stone-300 bg-white px-6 py-14 text-center">
                <p class="text-stone-500">Keranjangmu masih kosong, jadi belum ada yang bisa dibayar.</p>
                <a href="{{ route('menu') }}" class="mt-5 inline-block rounded-full bg-neon-500 px-7 py-3 font-semibold text-white transition hover:bg-neon-600">
                    Pilih menu dulu
                </a>
            </div>

            <form id="checkoutForm" method="POST" action="{{ route('checkout.submit') }}" novalidate
                class="mt-10 grid grid-cols-1 gap-8 lg:grid-cols-[1.4fr_1fr] lg:items-start">
                @csrf

                {{-- Kolom kiri: data pengambilan + metode --}}
                <div class="space-y-6">

                    {{-- 1. Data pemesan --}}
                    <section class="rounded-2xl bg-white p-6 shadow-sm sm:p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-neon-500 text-sm font-bold text-white">1</span>
                            <h2 class="headline text-xl text-stone-900">Data pemesan</h2>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="nama" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Nama siswa</label>
                                <input type="text" id="nama" name="nama" required value="{{ old('nama') }}" placeholder="Nama lengkap"
                                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                                @error('nama') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="kelas" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Kelas</label>
                                <input type="text" id="kelas" name="kelas" required value="{{ old('kelas') }}" placeholder="Contoh: XII TKJ 1"
                                    class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                                @error('kelas') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    {{-- 2. Waktu pengambilan --}}
                    <section class="rounded-2xl bg-white p-6 shadow-sm sm:p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-neon-500 text-sm font-bold text-white">2</span>
                            <h2 class="headline text-xl text-stone-900">Waktu pengambilan</h2>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-3">
                            @foreach ($waktuPilihan as $i => $waktu)
                                <label class="option-card cursor-pointer rounded-xl border-2 border-stone-200 p-4 transition hover:border-neon-300 hover:bg-neon-50">
                                    <input type="radio" name="waktu" value="{{ $waktu['value'] }}" class="sr-only"
                                        @checked(old('waktu', 'istirahat-1') === $waktu['value'])>
                                    <span class="block font-semibold text-stone-900">{{ $waktu['label'] }}</span>
                                    <span class="mt-1 block text-sm text-stone-500">{{ $waktu['note'] }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('waktu') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </section>

                    {{-- 3. Metode pembayaran --}}
                    <section class="rounded-2xl bg-white p-6 shadow-sm sm:p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-neon-500 text-sm font-bold text-white">3</span>
                            <h2 class="headline text-xl text-stone-900">Metode pembayaran</h2>
                        </div>

                        <p class="mt-5 text-[11px] uppercase tracking-[0.18em] text-stone-500">Bayar di tempat</p>
                        <div class="mt-3 space-y-3">
                            @foreach ($metodeTempat as $metode)
                                <label class="option-card flex cursor-pointer items-start gap-3 rounded-xl border-2 border-stone-200 p-4 transition hover:border-neon-300 hover:bg-neon-50">
                                    <input type="radio" name="metode" value="{{ $metode['value'] }}" class="sr-only"
                                        @checked(old('metode', 'tunai') === $metode['value'])>
                                    <span class="option-dot mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-stone-300"></span>
                                    <span class="flex-1">
                                        <span class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold text-stone-900">{{ $metode['label'] }}</span>
                                            @if ($metode['badge'])
                                                <span class="rounded-full bg-neon-100 px-2.5 py-0.5 text-[10px] uppercase tracking-[0.14em] text-neon-800">{{ $metode['badge'] }}</span>
                                            @endif
                                        </span>
                                        <span class="mt-1 block text-sm text-stone-500">{{ $metode['note'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <p class="mt-6 text-[11px] uppercase tracking-[0.18em] text-stone-500">Bayar online</p>
                        <div class="mt-3 space-y-3">
                            @foreach ($metodeOnline as $metode)
                                <label class="option-card flex cursor-pointer items-start gap-3 rounded-xl border-2 border-stone-200 p-4 transition hover:border-neon-300 hover:bg-neon-50">
                                    <input type="radio" name="metode" value="{{ $metode['value'] }}" class="sr-only"
                                        @checked(old('metode') === $metode['value'])>
                                    <span class="option-dot mt-1 flex h-5 w-5 shrink-0 items-center justify-center rounded-full border-2 border-stone-300"></span>
                                    <span class="flex-1">
                                        <span class="flex flex-wrap items-center gap-2">
                                            <span class="font-semibold text-stone-900">{{ $metode['label'] }}</span>
                                            @if ($metode['badge'])
                                                <span class="rounded-full bg-neon-100 px-2.5 py-0.5 text-[10px] uppercase tracking-[0.14em] text-neon-800">{{ $metode['badge'] }}</span>
                                            @endif
                                        </span>
                                        <span class="mt-1 block text-sm text-stone-500">{{ $metode['note'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('metode') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                        {{-- Rincian yang hanya muncul untuk metode tertentu --}}
                        <div id="detailTransfer" class="mt-4 hidden rounded-xl border border-stone-200 bg-stone-50 p-4">
                            <label for="bank" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Pilih bank</label>
                            <select id="bank" name="bank"
                                class="mt-2 w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-stone-900 outline-none transition focus:border-neon-500 focus:ring-4 focus:ring-neon-500/20">
                                <option value="BRI">BRI</option>
                                <option value="BNI">BNI</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="BCA">BCA</option>
                            </select>
                            <p class="mt-2 text-sm text-stone-500">Nomor virtual account muncul di halaman berikutnya.</p>
                        </div>

                        <div id="detailEwallet" class="mt-4 hidden rounded-xl border border-stone-200 bg-stone-50 p-4">
                            <label for="ewallet" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Pilih e-wallet</label>
                            <select id="ewallet" name="ewallet"
                                class="mt-2 w-full rounded-xl border border-stone-200 bg-white px-4 py-3 text-stone-900 outline-none transition focus:border-neon-500 focus:ring-4 focus:ring-neon-500/20">
                                <option value="GoPay">GoPay</option>
                                <option value="OVO">OVO</option>
                                <option value="DANA">DANA</option>
                            </select>
                        </div>

                        <div id="detailQris" class="mt-4 hidden rounded-xl border border-stone-200 bg-stone-50 p-4">
                            <p class="text-sm text-stone-500">
                                Kode QR akan ditampilkan setelah pesanan dikirim, berlaku 15 menit.
                            </p>
                        </div>

                        <div id="detailSaldo" class="mt-4 hidden rounded-xl border border-stone-200 bg-stone-50 p-4">
                            <div class="flex items-baseline justify-between">
                                <span class="text-sm text-stone-500">Saldo kartu pelajar</span>
                                <span class="headline text-xl text-stone-900">Rp 75.000</span>
                            </div>
                            <p class="mt-1 text-sm text-stone-500">Contoh saldo; belum terhubung ke sistem koperasi.</p>
                        </div>
                    </section>

                    {{-- 4. Catatan --}}
                    <section class="rounded-2xl bg-white p-6 shadow-sm sm:p-7">
                        <div class="flex items-center gap-3">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-neon-500 text-sm font-bold text-white">4</span>
                            <h2 class="headline text-xl text-stone-900">Catatan untuk stan</h2>
                        </div>

                        <textarea id="catatan" name="catatan" rows="3" maxlength="200"
                            placeholder="Contoh: titip ke ketua kelas kalau saya belum sampai"
                            class="mt-4 w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">{{ old('catatan') }}</textarea>
                    </section>
                </div>

                {{-- Kolom kanan: ringkasan --}}
                <aside class="lg:sticky lg:top-28">
                    <div class="rounded-2xl bg-white p-6 shadow-sm sm:p-7">
                        <h2 class="headline text-xl text-stone-900">Ringkasan pesanan</h2>

                        <div id="checkoutItems" class="mt-5 space-y-3"></div>

                        <div class="mt-5 space-y-1.5 border-t border-stone-100 pt-4">
                            <div class="flex items-baseline justify-between text-stone-500">
                                <span>Subtotal</span>
                                <span id="checkoutSubtotal">Rp 0</span>
                            </div>

                            <div id="checkoutDiscountRow" class="hidden items-baseline justify-between text-neon-700">
                                <span>Diskon <span id="checkoutPromoCode" class="text-xs tracking-[0.1em]"></span></span>
                                <span id="checkoutDiscount">&minus;Rp 0</span>
                            </div>

                            <div class="flex items-baseline justify-between pt-1">
                                <span class="text-stone-500">Total bayar</span>
                                <span id="checkoutTotal" class="headline text-2xl text-neon-600">Rp 0</span>
                            </div>
                        </div>

                        {{-- Total dikirim dari klien; server wajib menghitung ulang saat backend siap. --}}
                        <input type="hidden" name="total" id="checkoutTotalInput" value="0">

                        <button type="submit" id="checkoutSubmit"
                            class="mt-6 w-full rounded-full bg-neon-500 px-6 py-4 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.45)] transition hover:bg-neon-600 disabled:cursor-not-allowed disabled:opacity-50 disabled:shadow-none">
                            Konfirmasi &amp; Bayar
                        </button>

                        <p class="mt-3 text-center text-xs leading-relaxed text-stone-400">
                            Dengan menekan tombol ini kamu setuju pesanan diteruskan ke stan kantin.
                        </p>
                    </div>
                </aside>
            </form>
        </div>
    </section>
@endsection
