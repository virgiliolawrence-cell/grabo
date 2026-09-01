@extends('layouts.auth')

@section('title', 'Masuk — Grabo')

@section('content')
    <div class="flex min-h-screen flex-col lg:flex-row">

        {{-- Kolom kiri: formulir --}}
        <div class="flex w-full flex-col justify-between px-6 py-8 sm:px-10 lg:w-1/2 lg:px-16 lg:py-10">
            <a href="{{ route('home') }}" class="inline-flex w-fit items-center gap-3" aria-label="Kembali ke beranda Grabo">
                <img src="{{ $graboLogo }}" alt="Grabo" class="h-11 w-auto" width="42" height="44">
                <span class="border-l border-stone-200 pl-3 text-[10px] uppercase leading-snug tracking-[0.2em] text-neon-700">
                    Kantin<br>Digital
                </span>
            </a>

            <div class="mx-auto w-full max-w-md py-12 lg:py-8">
                <h1 class="headline text-[clamp(2rem,4vw,2.75rem)] text-stone-900">Masuk ke Grabo</h1>
                <p class="mt-3 leading-relaxed text-stone-500">
                    Gunakan akun sekolahmu untuk memesan menu kantin, membayar dengan saldo pelajar,
                    lalu mengambil pesanan tanpa antre.
                </p>

                <form id="loginForm" method="POST" action="{{ route('login.attempt') }}" novalidate class="mt-9 space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">
                            Email sekolah
                        </label>
                        <input type="email" id="email" name="email" autocomplete="username" required
                            value="{{ old('email') }}"
                            placeholder="nama@grabo.sch.id"
                            aria-describedby="emailError"
                            class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3.5 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                        <p id="emailError" class="mt-1.5 hidden text-sm text-red-600"></p>
                    </div>

                    <div>
                        <label for="password" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">
                            Kata sandi
                        </label>
                        <div class="relative mt-2">
                            <input type="password" id="password" name="password" autocomplete="current-password" required
                                placeholder="Minimal 8 karakter"
                                aria-describedby="passwordError"
                                class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3.5 pr-12 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                            <button type="button" id="togglePassword" aria-label="Tampilkan kata sandi" aria-pressed="false"
                                class="absolute right-2 top-1/2 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-lg text-stone-400 transition hover:bg-stone-100 hover:text-stone-700">
                                <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                                    <path d="M2.5 12s3.5-6.5 9.5-6.5S21.5 12 21.5 12s-3.5 6.5-9.5 6.5S2.5 12 2.5 12Z" stroke="currentColor" stroke-width="1.7" />
                                    <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.7" />
                                </svg>
                                <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="hidden h-5 w-5" aria-hidden="true">
                                    <path d="M4 4l16 16M10 5.7A7.9 7.9 0 0 1 12 5.5c6 0 9.5 6.5 9.5 6.5a17 17 0 0 1-3.3 4M6.2 8.2A17 17 0 0 0 2.5 12s3.5 6.5 9.5 6.5c1.2 0 2.3-.2 3.3-.6" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                </svg>
                            </button>
                        </div>
                        <p id="passwordError" class="mt-1.5 hidden text-sm text-red-600"></p>
                    </div>

                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <label for="remember" class="flex items-center gap-2.5 text-sm text-stone-600">
                            <input type="checkbox" id="remember" name="remember" value="1"
                                class="h-4 w-4 rounded border-stone-300 text-neon-500 accent-neon-500 focus:ring-neon-500">
                            Ingat saya
                        </label>

                        <a href="#" class="text-sm font-semibold text-neon-700 underline-offset-4 transition hover:underline">
                            Lupa kata sandi?
                        </a>
                    </div>

                    <button type="submit"
                        class="w-full rounded-full bg-neon-500 px-6 py-4 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.4)] transition hover:-translate-y-0.5 hover:bg-neon-600">
                        Masuk
                    </button>

                    <p id="formNote" class="hidden rounded-xl bg-neon-50 px-4 py-3 text-center text-sm text-neon-800"></p>
                </form>

                <p class="mt-8 text-center text-stone-500">
                    Belum punya akun?
                    <a href="#" class="font-semibold text-neon-700 underline-offset-4 hover:underline">Daftar sekarang</a>
                </p>
            </div>

            <p class="text-xs text-stone-400">&copy; {{ date('Y') }} Grabo &middot; Kantin sekolah digital</p>
        </div>

        {{-- Kolom kanan: gambar --}}
        <div class="relative hidden overflow-hidden lg:block lg:w-1/2">
            <img src="{{ asset('images/food/photos/nasi-goreng.jpg') }}" alt=""
                class="absolute inset-0 h-full w-full object-cover" aria-hidden="true">
            {{-- Gradien berat di bawah saja, supaya fotonya tetap terlihat. --}}
            <div class="absolute inset-0 bg-gradient-to-t from-neon-900/95 via-neon-800/70 to-neon-600/30"></div>

            <div class="relative flex h-full flex-col justify-end gap-6 p-12 xl:p-16">
                <span class="inline-flex w-fit items-center gap-2 rounded-full border border-white/35 bg-white/10 px-4 py-1.5 text-[11px] uppercase tracking-[0.2em] text-white backdrop-blur">
                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                    500+ siswa memakai Grabo
                </span>

                <p class="headline max-w-lg text-[clamp(2rem,3.5vw,3.25rem)] text-white">
                    Pesan sebelum bel,<br>makan duluan.
                </p>

                <p class="max-w-md leading-relaxed text-white/80">
                    Tidak ada lagi antrean panjang di jam istirahat. Pesananmu sudah menunggu di loket
                    begitu bel berbunyi.
                </p>

                <dl class="mt-2 flex gap-10 border-t border-white/25 pt-6 text-white">
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.16em] text-white/70">Stan kantin</dt>
                        <dd class="headline mt-1 text-3xl">30</dd>
                    </div>
                    <div>
                        <dt class="text-[11px] uppercase tracking-[0.16em] text-white/70">Waktu antre</dt>
                        <dd class="headline mt-1 text-3xl">0 menit</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <script>
        const loginForm = document.getElementById('loginForm');
        const emailField = document.getElementById('email');
        const passwordField = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');
        const formNote = document.getElementById('formNote');

        const showError = (field, box, message) => {
            box.textContent = message;
            box.classList.remove('hidden');
            field.classList.add('border-red-400', 'bg-red-50');
            field.setAttribute('aria-invalid', 'true');
        };

        const clearError = (field, box) => {
            box.textContent = '';
            box.classList.add('hidden');
            field.classList.remove('border-red-400', 'bg-red-50');
            field.removeAttribute('aria-invalid');
        };

        function validateLogin() {
            let valid = true;
            clearError(emailField, emailError);
            clearError(passwordField, passwordError);

            const email = emailField.value.trim();

            if (!email) {
                showError(emailField, emailError, 'Email sekolah wajib diisi.');
                valid = false;
            } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showError(emailField, emailError, 'Format email belum benar.');
                valid = false;
            }

            if (!passwordField.value) {
                showError(passwordField, passwordError, 'Kata sandi wajib diisi.');
                valid = false;
            } else if (passwordField.value.length < 8) {
                showError(passwordField, passwordError, 'Kata sandi minimal 8 karakter.');
                valid = false;
            }

            return valid;
        }

        loginForm?.addEventListener('submit', (event) => {
            if (!validateLogin()) {
                event.preventDefault();
                (document.querySelector('[aria-invalid="true"]'))?.focus();
            }
        });

        [emailField, passwordField].forEach((field) => {
            field.addEventListener('input', () => {
                clearError(field, field === emailField ? emailError : passwordError);
            });
        });

        /* Tampilkan / sembunyikan kata sandi. */
        const togglePassword = document.getElementById('togglePassword');

        togglePassword?.addEventListener('click', () => {
            const revealed = passwordField.type === 'text';
            passwordField.type = revealed ? 'password' : 'text';
            togglePassword.setAttribute('aria-pressed', String(!revealed));
            togglePassword.setAttribute('aria-label', revealed ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
            document.getElementById('eyeOpen').classList.toggle('hidden', !revealed);
            document.getElementById('eyeClosed').classList.toggle('hidden', revealed);
        });

        @if (session('status'))
            formNote.textContent = @json(session('status'));
            formNote.classList.remove('hidden');
        @endif
    </script>
@endsection
