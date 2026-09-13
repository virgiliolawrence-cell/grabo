@extends('tataletak.autentikasi')

@section('title', 'Masuk — Grabo')

@section('content')
    <div class="flex min-h-screen flex-col lg:flex-row">

        {{-- Kolom kiri: formulir --}}
        <div class="flex w-full flex-col justify-between px-6 py-8 sm:px-10 lg:w-1/2 lg:px-16 lg:py-10">
            <a href="{{ route('beranda') }}" class="inline-flex w-fit items-center gap-3" aria-label="Kembali ke beranda Grabo">
                <img src="{{ $graboLogo }}" alt="Grabo" class="h-11 w-auto" width="42" height="44">
                <span class="border-l border-stone-200 pl-3 text-[10px] uppercase leading-snug tracking-[0.2em] text-neon-700">
                    Kantin<br>Digital
                </span>
            </a>

            <div class="mx-auto w-full max-w-md py-12 lg:py-8">
                <h1 class="judul-besar text-[clamp(2rem,4vw,2.75rem)] text-stone-900">Masuk ke Grabo</h1>
                <p class="mt-3 leading-relaxed text-stone-500">
                    Gunakan akun sekolahmu untuk memesan menu kantin, membayar dengan saldo pelajar,
                    lalu mengambil pesanan tanpa antre.
                </p>

                <form id="formMasuk" method="POST" action="{{ route('masuk.proses') }}" novalidate class="mt-9 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">
                            Email sekolah
                        </label>
                        <input type="email" id="email" name="email" autocomplete="username" required
                            value="{{ old('email') }}"
                            placeholder="nama@grabo.sch.id"
                            aria-describedby="galatEmail"
                            class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3.5 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                        <p id="galatEmail" class="mt-1.5 hidden text-sm text-red-600"></p>
                    </div>

                    <div>
                        <label for="sandi" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">
                            Kata sandi
                        </label>
                        <div class="relative mt-2">
                            <input type="password" id="sandi" name="sandi" autocomplete="current-password" required
                                placeholder="Minimal 8 karakter"
                                aria-describedby="galatSandi"
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3.5 pr-12 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                            <button type="button" id="tampilkanSandi" aria-label="Tampilkan kata sandi" aria-pressed="false"
                                class="absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-stone-400 transition hover:bg-stone-100 hover:text-stone-700">
                                <svg id="mataTerbuka" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                    <path d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12Z" stroke="currentColor" stroke-width="1.7" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7" />
                                </svg>
                                <svg id="mataTertutup" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="hidden h-5 w-5" aria-hidden="true">
                                    <path d="M4 4l16 16M10 5.7A7.9 7.9 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17 17 0 0 1-3.3 4M6.2 8.2A17 17 0 0 0 2.5 12s3.5 6.5 9.5 6.5c1.2 0 2.3-.2 3.3-.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                        <p id="galatSandi" class="mt-1.5 hidden text-sm text-red-600"></p>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <label for="ingatSaya" class="flex items-center gap-2.5 text-sm text-stone-600">
                            <input type="checkbox" id="ingatSaya" name="ingatSaya" value="1"
                                class="h-4 w-4 rounded border-stone-300 text-neon-500 accent-neon-500 focus:ring-neon-500">
                            Ingat saya
                        </label>

                        {{-- Akun siswa diurus koperasi, jadi arahkan ke petugasnya. --}}
                        <a href="mailto:{{ config('grabo.kontak.email') }}?subject={{ rawurlencode('Lupa kata sandi Grabo') }}&body={{ rawurlencode("Nama:\nNIS:\nKelas:\n\nSaya lupa kata sandi akun Grabo dan minta disetel ulang.") }}"
                            class="text-sm font-semibold text-neon-700 underline-offset-4 transition hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full rounded-full bg-neon-500 px-6 py-4 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.4)] transition hover:-translate-y-0.5 hover:bg-neon-600">
                        Masuk
                    </button>

                    <p id="catatanForm" class="hidden rounded-xl bg-neon-50 px-4 py-3 text-center text-sm text-neon-800"></p>
                </form>

                <p class="mt-8 text-center text-stone-500">
                    Belum punya akun? Akun dibuatkan koperasi sekolah &mdash;
                    <a href="mailto:{{ config('grabo.kontak.email') }}?subject={{ rawurlencode('Pendaftaran akun Grabo') }}&body={{ rawurlencode("Nama:\nNIS:\nKelas:\n\nSaya ingin didaftarkan akun Grabo.") }}"
                        class="font-semibold text-neon-700 underline-offset-4 hover:underline">minta didaftarkan</a>.
                </p>
            </div>

            <p class="text-xs text-stone-400">&copy; {{ date('Y') }} Grabo &middot; Kantin sekolah digital</p>
        </div>

        {{-- Kolom kanan: gambar --}}
        <div class="relative hidden overflow-hidden lg:block lg:w-1/2">
            <img src="{{ asset('images/food/photos/nasi-goreng-kampung.jpg') }}" alt=""
                class="absolute inset-0 h-full w-full object-cover" aria-hidden="true">
            {{-- Gradien berat di bawah saja, supaya fotonya tetap terlihat. --}}
            <div class="absolute inset-0 bg-gradient-to-t from-neon-900/95 via-neon-800/70 to-neon-600/30"></div>

            <div class="relative flex h-full flex-col justify-end gap-6 p-12 xl:p-16">
                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/35 bg-white/10 px-4 py-1.5 text-[11px] uppercase tracking-[0.2em] text-white backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    500+ siswa memakai Grabo
                </span>

                <p class="judul-besar max-w-lg text-[clamp(2rem,3.5vw,3.25rem)] text-white">
                    Pesan sebelum bel,<br>makan duluan.
                </p>

                <p class="max-w-md leading-relaxed text-white/80">
                    Tidak ada lagi antrean panjang di jam istirahat. Pesananmu sudah menunggu di loket
                    begitu bel berbunyi.
                </p>

                <dl class="mt-2 flex gap-10 border-t border-white/25 pt-6 text-white">
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.16em] text-white/70">Stan kantin</dt>
                        <dd class="judul-besar mt-1 text-3xl">30</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.16em] text-white/70">Waktu antre</dt>
                        <dd class="judul-besar mt-1 text-3xl">0 menit</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <script>
        const formMasuk = document.getElementById('formMasuk');
        const kolomEmail = document.getElementById('email');
        const kolomSandi = document.getElementById('sandi');
        const galatEmail = document.getElementById('galatEmail');
        const galatSandi = document.getElementById('galatSandi');
        const catatanForm = document.getElementById('catatanForm');

        const tampilkanGalat = (kolom, kotak, pesan) => {
            kotak.textContent = pesan;
            kotak.classList.remove('hidden');
            kolom.classList.add('border-red-400', 'bg-red-50');
            kolom.setAttribute('aria-invalid', 'true');
        };

        const bersihkanGalat = (kolom, kotak) => {
            kotak.textContent = '';
            kotak.classList.add('hidden');
            kolom.classList.remove('border-red-400', 'bg-red-50');
            kolom.removeAttribute('aria-invalid');
        };

        function periksaIsian() {
            let sah = true;
            bersihkanGalat(kolomEmail, galatEmail);
            bersihkanGalat(kolomSandi, galatSandi);

            const email = kolomEmail.value.trim();

            if (!email) {
                tampilkanGalat(kolomEmail, galatEmail, 'Email sekolah wajib diisi.');
                sah = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                tampilkanGalat(kolomEmail, galatEmail, 'Format email belum benar.');
                sah = false;
            }

            if (!kolomSandi.value) {
                tampilkanGalat(kolomSandi, galatSandi, 'Kata sandi wajib diisi.');
                sah = false;
            } else if (kolomSandi.value.length < 8) {
                tampilkanGalat(kolomSandi, galatSandi, 'Kata sandi minimal 8 karakter.');
                sah = false;
            }

            return sah;
        }

        formMasuk?.addEventListener('submit', (peristiwa) => {
            if (!periksaIsian()) {
                peristiwa.preventDefault();
                (document.querySelector('[aria-invalid="true"]'))?.focus();
            }
        });

        [kolomEmail, kolomSandi].forEach((kolom) => {
            kolom.addEventListener('input', () => {
                bersihkanGalat(field, kolom === kolomEmail ? galatEmail : galatSandi);
            });
        });

        /* Tampilkan / sembunyikan kata sandi. */
        const tampilkanSandi = document.getElementById('tampilkanSandi');

        tampilkanSandi?.addEventListener('click', () => {
            const sedangTerlihat = kolomSandi.type === 'text';
            kolomSandi.type = sedangTerlihat ? 'password' : 'text';
            tampilkanSandi.setAttribute('aria-pressed', String(!sedangTerlihat));
            tampilkanSandi.setAttribute('aria-label', sedangTerlihat ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
            document.getElementById('mataTerbuka').classList.toggle('hidden', !sedangTerlihat);
            document.getElementById('mataTertutup').classList.toggle('hidden', sedangTerlihat);
        });

        @if (session('status'))
            catatanForm.textContent = @json(session('status'));
            catatanForm.classList.remove('hidden');
        @endif
    </script>
@endsection
