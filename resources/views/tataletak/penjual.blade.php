<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dasbor Penjual — Grabo')</title>
    <meta name="robots" content="noindex">
    <link rel="icon" href="{{ $graboLogo }}" sizes="any">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{--
        Transisi halus antar-halaman dasbor (View Transitions lintas dokumen).
        Hanya dipasang di sini, jadi halaman siswa tidak ikut. Animasinya di app.css.
    --}}
    <style>
        @view-transition {
            navigation: auto;
        }
    </style>
</head>

{{-- Tata letak dasbor penjual: diadaptasi dari template backend Sistem Sekolah. --}}
<body class="flex min-h-screen flex-col bg-krem text-stone-700 antialiased">

@include('bagian.penjual.kepala')

    <main class="isi-penjual mx-auto w-full max-w-6xl flex-1 px-4 py-10 sm:px-6">
        @if (session('status'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800" role="status">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5 shrink-0" aria-hidden="true">
                    <path d="M5 12.5l4.5 4.5L19 7.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

@include('bagian.penjual.kaki')
</body>

</html>
