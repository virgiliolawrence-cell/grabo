<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Dasbor — Grabo</title>
    <link rel="icon" href="{{ $graboLogo }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="flex min-h-screen items-center justify-center bg-stone-950 px-5 text-stone-800 antialiased">

    <div class="w-full max-w-md">
        <div class="mb-6 flex items-center justify-center gap-3">
            <span class="flex items-center rounded-xl bg-white px-3 py-2">
                <img src="{{ $graboLogo }}" alt="Grabo" class="h-10 w-auto" width="38" height="40">
            </span>
            <span class="leading-tight text-white">
                <span class="block headline text-xl">Dasbor Kantin</span>
                <span class="block text-[10px] uppercase tracking-[0.2em] text-white/50">Khusus pengelola</span>
            </span>
        </div>

        <div class="rounded-2xl bg-white p-7 shadow-2xl sm:p-8">
            <h1 class="headline text-2xl text-stone-900">Masuk</h1>
            <p class="mt-2 text-sm leading-relaxed text-stone-500">
                Gunakan akun admin atau petugas kantin. Halaman ini terpisah dari akun siswa.
            </p>

            @if (session('status'))
                <p class="mt-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('status') }}</p>
            @endif

            @error('email')
                <p class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">{{ $message }}</p>
            @enderror

            <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="email" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Email</label>
                    <input type="email" id="email" name="email" required autofocus value="{{ old('email') }}"
                        autocomplete="username" placeholder="admin@grabo.sch.id"
                        class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none transition focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                </div>

                <div>
                    <label for="password" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Kata sandi</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password"
                        class="mt-2 w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 outline-none transition focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                </div>

                <label for="remember" class="flex items-center gap-2.5 text-sm text-stone-600">
                    <input type="checkbox" id="remember" name="remember" value="1"
                        class="h-4 w-4 rounded border-stone-300 accent-neon-500">
                    Ingat saya
                </label>

                <button type="submit"
                    class="w-full rounded-full bg-neon-500 px-6 py-3.5 font-semibold text-white transition hover:bg-neon-600">
                    Masuk Dasbor
                </button>
            </form>
        </div>

        <p class="mt-5 text-center text-sm text-white/40">
            <a href="{{ route('home') }}" class="underline-offset-4 hover:underline">Kembali ke situs siswa</a>
        </p>
    </div>

</body>

</html>
