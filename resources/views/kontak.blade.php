@extends('tataletak.grabo')

@section('title', 'Kontak — Grabo')
@section('description', 'Hubungi koperasi dan kantin sekolah: jam layanan, daftar stan, pertanyaan yang sering muncul, dan formulir pesan.')

@section('content')

    {{-- Judul halaman --}}
    <section class="bg-krem px-4 pt-12 sm:px-6 lg:pt-16">
        <div class="mx-auto max-w-7xl">
            <span class="text-[11px] uppercase tracking-[0.24em] text-neon-800">Hubungi Kami</span>
            <h1 class="mt-3 judul-besar text-[clamp(2.2rem,5vw,3.75rem)] text-stone-900">Ada yang Bisa Dibantu?</h1>
            <p class="mt-4 max-w-2xl leading-relaxed text-stone-500">
                Koperasi sekolah mengurus akun Grabo, saldo kartu pelajar, dan seluruh stan kantin.
                Datang langsung ke loket pada jam layanan, atau tinggalkan pesan lewat formulir di bawah.
            </p>
        </div>
    </section>

    {{-- Tiga cara menghubungi --}}
    <section class="bg-krem px-4 pt-10 sm:px-6">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-5 md:grid-cols-3">

            <a href="mailto:{{ config('grabo.kontak.email') }}"
                class="group flex flex-col rounded-2xl bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-neon-900/10">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-neon-50 text-neon-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-6 w-6" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8" />
                        <path d="m3.5 7 8.5 6 8.5-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <h2 class="judul-besar mt-4 text-xl text-stone-900">Email</h2>
                <p class="mt-1 flex-1 text-stone-500">Untuk urusan akun, saldo, dan laporan pesanan bermasalah.</p>
                <span class="mt-4 font-semibold text-neon-700 transition group-hover:underline">{{ config('grabo.kontak.email') }}</span>
            </a>

            <a href="tel:+62{{ ltrim(preg_replace('/\D/', '', config('grabo.kontak.telepon')), '0') }}"
                class="group flex flex-col rounded-2xl bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-neon-900/10">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-neon-50 text-neon-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-6 w-6" aria-hidden="true">
                        <path d="M6.5 3.5h3l1.5 4-2 1.5a12 12 0 0 0 6 6l1.5-2 4 1.5v3a2 2 0 0 1-2.2 2A16.5 16.5 0 0 1 4.5 5.7 2 2 0 0 1 6.5 3.5Z"
                            stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                    </svg>
                </span>
                <h2 class="judul-besar mt-4 text-xl text-stone-900">Telepon</h2>
                <p class="mt-1 flex-1 text-stone-500">Sambungan koperasi, aktif selama jam layanan sekolah.</p>
                <span class="mt-4 font-semibold text-neon-700 transition group-hover:underline">(021) 555&ndash;0198</span>
            </a>

            <div class="flex flex-col rounded-2xl bg-white p-7 shadow-sm">
                <span class="flex h-12 w-12 items-center justify-center rounded-full bg-neon-50 text-neon-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-6 w-6" aria-hidden="true">
                        <path d="M12 21s-7-6.1-7-11a7 7 0 1 1 14 0c0 4.9-7 11-7 11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" />
                        <circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.8" />
                    </svg>
                </span>
                <h2 class="judul-besar mt-4 text-xl text-stone-900">Loket Koperasi</h2>
                <p class="mt-1 flex-1 text-stone-500">
                    Gedung kantin lantai 1, sebelah kiri pintu masuk lapangan.
                </p>
                <span class="mt-4 font-semibold text-stone-700">Koperasi &amp; Kantin Sekolah</span>
            </div>
        </div>
    </section>

    {{-- Jam layanan + daftar stan --}}
    <section class="bg-krem px-4 pt-5 sm:px-6">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-5 lg:grid-cols-[1.2fr_1fr]">

            <div class="rounded-2xl bg-white p-7 shadow-sm sm:p-8">
                <h2 class="judul-besar text-2xl text-stone-900">Jam Layanan</h2>

                <dl class="mt-5 divide-y divide-stone-100">
                    @foreach ($jamLayanan as $barisJam)
                        <div class="flex items-baseline justify-between gap-4 py-3">
                            <dt class="text-stone-500">{!! $barisJam['hari'] !!}</dt>
                            <dd class="font-semibold text-stone-900">{!! $barisJam['jam'] !!}</dd>
                        </div>
                    @endforeach
                </dl>

                <p class="mt-6 text-[11px] uppercase tracking-[0.18em] text-stone-500">Jam istirahat</p>
                <div class="mt-3 grid grid-cols-2 gap-3">
                    @foreach ($istirahat as $slotIstirahat)
                        <div class="rounded-xl bg-neon-50 px-4 py-3">
                            <span class="block text-sm text-neon-700">{{ $slotIstirahat['label'] }}</span>
                            <span class="judul-besar block text-lg text-neon-800">{!! $slotIstirahat['jam'] !!}</span>
                        </div>
                    @endforeach
                </div>

                <p class="mt-4 text-sm leading-relaxed text-stone-400">
                    Pesanan lewat Grabo tetap bisa dikirim di luar jam istirahat, tapi baru
                    disiapkan stan ketika loket buka.
                </p>
            </div>

            <div class="rounded-2xl bg-white p-7 shadow-sm sm:p-8">
                <h2 class="judul-besar text-2xl text-stone-900">Stan yang Buka</h2>
                <p class="mt-1 text-stone-500">Tekan salah satu untuk melihat menunya.</p>

                <ul class="mt-5 space-y-2.5">
                    @forelse ($stan as $namaStan)
                        <li>
                            <a href="{{ route('menu', ['stan' => $namaStan]) }}"
                                class="flex items-center justify-between gap-3 rounded-xl border border-stone-200 px-4 py-3 transition hover:border-neon-300 hover:bg-neon-50">
                                <span class="font-semibold text-stone-900">{{ $namaStan }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4 text-stone-400" aria-hidden="true">
                                    <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </li>
                    @empty
                        <li class="text-stone-400">Belum ada stan yang berjualan hari ini.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </section>

    {{-- Pertanyaan yang sering muncul --}}
    <section class="bg-krem px-4 pt-5 sm:px-6">
        <div class="mx-auto max-w-7xl rounded-2xl bg-white p-7 shadow-sm sm:p-8">
            <h2 class="judul-besar text-2xl text-stone-900">Pertanyaan yang Sering Muncul</h2>

            <div class="mt-5 divide-y divide-stone-100">
                @foreach ($tanya as $tanyaJawab)
                    <details class="group py-4">
                        <summary class="flex cursor-pointer items-center justify-between gap-4 font-semibold text-stone-900 marker:content-none">
                            {{ $tanyaJawab['tanya'] }}
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                class="h-5 w-5 shrink-0 text-neon-600 transition group-open:rotate-45" aria-hidden="true">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                            </svg>
                        </summary>
                        <p class="mt-2 max-w-3xl leading-relaxed text-stone-500">{{ $tanyaJawab['jawab'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    {{--
        Formulir pesan.

        Belum ada tabel pesan maupun pengaturan pengiriman email di server,
        jadi tombolnya menyusun surat di aplikasi email siswa. Ganti dengan
        kiriman ke server begitu dasbor pengelola dikerjakan.
    --}}
    <section id="kirim-pesan" class="scroll-mt-28 bg-krem px-4 py-16 sm:px-6 lg:py-20">
        <div class="mx-auto max-w-3xl rounded-2xl bg-white p-7 shadow-sm sm:p-10">
            <h2 class="judul-besar text-2xl text-stone-900 sm:text-3xl">Kirim Pesan</h2>
            <p class="mt-2 leading-relaxed text-stone-500">
                Isi keterangannya, lalu tekan tombol di bawah. Aplikasi emailmu akan terbuka
                dengan surat yang sudah tersusun ke {{ config('grabo.kontak.email') }} &mdash;
                tinggal tekan kirim.
            </p>

            <form id="formKontak" novalidate class="mt-6 space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nama" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Nama</label>
                        <input type="text" id="nama" required placeholder="Nama lengkap"
                            class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                    </div>

                    <div>
                        <label for="kelas" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Kelas (opsional)</label>
                        <input type="text" id="kelas" placeholder="Contoh: XII TKJ 1"
                            class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                    </div>
                </div>

                <div>
                    <label for="topik" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Topik</label>
                    <select id="topik"
                        class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none transition focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                        @foreach ($topik as $pilihanTopik)
                            <option value="{{ $pilihanTopik }}">{{ $pilihanTopik }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="pesan" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Pesan</label>
                    <textarea id="pesan" rows="5" required maxlength="1000"
                        placeholder="Ceritakan kejadiannya. Kalau soal pesanan, sertakan kode pesanannya."
                        class="mt-2 w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20"></textarea>
                    <p class="mt-1.5 text-sm text-stone-400"><span id="hitungHuruf">0</span>/1000 huruf</p>
                </div>

                <p id="pesanGalat" hidden class="rounded-xl border border-red-200 bg-red-50 px-5 py-3.5 text-sm text-red-700"></p>

                <button type="submit"
                    class="flex w-full items-center justify-center gap-2.5 rounded-full bg-neon-500 px-6 py-4 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.4)] transition hover:-translate-y-0.5 hover:bg-neon-600">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2.5" stroke="currentColor" stroke-width="1.8" />
                        <path d="m3.5 7 8.5 6 8.5-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Buka Email &amp; Kirim
                </button>
            </form>
        </div>
    </section>

    <script>
        (function () {
            const formulir = document.getElementById('formKontak');
            const kotak = document.getElementById('pesan');
            const hitung = document.getElementById('hitungHuruf');
            const galat = document.getElementById('pesanGalat');

            /* Penghitung huruf pada kotak pesan. */
            const perbaruiHitungan = () => { hitung.textContent = kotak.value.length; };

            kotak.addEventListener('input', perbaruiHitungan);
            perbaruiHitungan();

            const tampilkanGalat = (teks) => {
                galat.textContent = teks;
                galat.hidden = false;
            };

            formulir.addEventListener('submit', (peristiwa) => {
                peristiwa.preventDefault();
                galat.hidden = true;

                const nama = document.getElementById('nama').value.trim();
                const kelas = document.getElementById('kelas').value.trim();
                const topik = document.getElementById('topik').value;
                const isi = kotak.value.trim();

                if (!nama) {
                    tampilkanGalat('Namanya diisi dulu, ya.');
                    document.getElementById('nama').focus();
                    return;
                }

                if (isi.length < 10) {
                    tampilkanGalat('Pesannya minimal 10 huruf supaya petugas paham maksudmu.');
                    kotak.focus();
                    return;
                }

                const badan = [
                    `Nama: ${nama}`,
                    `Kelas: ${kelas || '-'}`,
                    `Topik: ${topik}`,
                    '',
                    isi,
                ].join('\n');

                window.location.href = @json('mailto:' . config('grabo.kontak.email'))
                    + '?subject=' + encodeURIComponent(`Grabo — ${topik}`)
                    + '&body=' + encodeURIComponent(badan);
            });
        })();
    </script>
@endsection
