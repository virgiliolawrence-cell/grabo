@php
    $tautanPenjual = [
        ['label' => 'Pembelian', 'rute' => 'penjual.pembelian'],
        ['label' => 'Pembeli', 'rute' => 'penjual.pembeli'],
        ['label' => 'Menu', 'rute' => 'penjual.menu'],
        ['label' => 'Diskon', 'rute' => 'penjual.diskon'],
        ['label' => 'Laporan', 'rute' => 'penjual.laporan'],
    ];
@endphp

<header class="kepala-penjual bg-stone-950 text-white">
    <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-4 py-4 sm:px-6">
        {{-- Merek --}}
        <a href="{{ route('penjual.pembelian') }}" class="merek-penjual group flex items-center gap-3">
            <span class="inline-flex rounded-xl bg-white px-2 py-1.5 transition duration-300 group-hover:-rotate-6 group-hover:scale-105">
                <img src="{{ $graboLogo }}" alt="Grabo" class="h-9 w-auto" width="35" height="36">
            </span>
            <span>
                <span class="judul-besar block text-xl leading-none">Dasbor Penjual</span>
                <span class="text-[10px] uppercase tracking-[0.2em] text-white/50">Kantin Digital</span>
            </span>
        </a>

        {{--
            Pil jingga (.penanda-penjual) menandai tab aktif dan meluncur dari tab
            sebelumnya saat berpindah halaman. Pil tipis (.bayangan-penjual) mengikuti
            kursor. Tanpa JavaScript, tab aktif tetap diberi latar lewat .aktif.
        --}}
        <nav id="navigasiPenjual" class="navigasi-penjual tanpa-bilah-gulir relative order-last flex w-full gap-1 overflow-x-auto text-sm md:order-none md:w-auto md:overflow-visible" aria-label="Navigasi penjual">
            <span class="bayangan-penjual" aria-hidden="true"></span>
            <span class="penanda-penjual" aria-hidden="true"></span>
            @foreach ($tautanPenjual as $tautan)
                {{-- Rute persis atau turunannya saja; 'pembeli*' akan ikut mencocokkan 'pembelian'. --}}
                @php($aktif = request()->routeIs($tautan['rute'], $tautan['rute'].'.*'))
                <a href="{{ route($tautan['rute']) }}" data-tab-penjual="{{ $tautan['rute'] }}"
                    @class(['tautan-penjual', 'aktif' => $aktif])
                    @if ($aktif) aria-current="page" @endif>
                    {{ $tautan['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="flex items-center gap-2">
            <a href="{{ route('penjual.profil') }}"
                @class([
                    'flex h-10 items-center gap-2 rounded-full border px-4 text-sm transition duration-300 hover:-translate-y-0.5',
                    'border-neon-500 bg-neon-500 font-semibold text-white' => request()->routeIs('penjual.profil*'),
                    'border-white/20 text-white/80 hover:border-white/50 hover:text-white' => ! request()->routeIs('penjual.profil*'),
                ])>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-4 w-4" aria-hidden="true">
                    <path d="M4 10h16l-1.5-5h-13L4 10Zm0 0v9h16v-9M9 19v-5h6v5" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round" />
                </svg>
                Profil
            </a>
            <form method="POST" action="{{ route('keluar') }}">
                @csrf
                <button type="submit" class="h-10 rounded-full bg-white px-4 text-sm font-semibold text-neon-700 transition duration-300 hover:-translate-y-0.5 hover:bg-neon-50">
                    Keluar
                </button>
            </form>
        </div>
    </div>

    {{-- Garis jingga dengan kilau yang berjalan pelan. --}}
    <div class="garis-penjual h-1" aria-hidden="true"></div>
</header>

<script>
    (() => {
        const navigasi = document.getElementById('navigasiPenjual');
        const penanda = navigasi?.querySelector('.penanda-penjual');
        const bayangan = navigasi?.querySelector('.bayangan-penjual');
        const daftarTab = [...(navigasi?.querySelectorAll('[data-tab-penjual]') ?? [])];
        let tabAktif = daftarTab.find((tab) => tab.classList.contains('aktif'));
        const KUNCI_TAB_TERAKHIR = 'grabo.penjual.tabTerakhir';

        if (!navigasi || !penanda || !bayangan || daftarTab.length === 0) {
            return;
        }

        /* Pindahkan satu pil ke sebuah tab; tanpa animasi bila beranimasi = false. */
        function letakkan(pil, tab, { beranimasi = true } = {}) {
            if (!tab) {
                pil.classList.remove('siap');
                return;
            }

            if (!beranimasi) {
                pil.style.transition = 'none';
            }

            pil.style.setProperty('--x', `${tab.offsetLeft}px`);
            pil.style.setProperty('--y', `${tab.offsetTop}px`);
            pil.style.setProperty('--w', `${tab.offsetWidth}px`);
            pil.style.setProperty('--h', `${tab.offsetHeight}px`);
            pil.classList.add('siap');

            if (!beranimasi) {
                pil.getBoundingClientRect(); // paksa hitung ulang tata letak: posisi awal tidak ikut dianimasikan
                pil.style.transition = '';
            }
        }

        function tandaiAktif(tab, opsi) {
            daftarTab.forEach((lain) => lain.classList.toggle('disorot', lain === tab));
            letakkan(penanda, tab, opsi);
        }

        /* Pil tipis mengikuti kursor; muncul dari posisinya sendiri kalau sudah tampil. */
        function intip(tab) {
            if (!tab || tab === tabAktif) {
                bayangan.classList.remove('siap');
                return;
            }

            letakkan(bayangan, tab, { beranimasi: bayangan.classList.contains('siap') });
        }

        // Pil mengambil alih latar tab aktif.
        navigasi.classList.add('pil-siap');

        // Mulai dari tab halaman sebelumnya, lalu meluncur ke tab sekarang.
        let tabTerakhir = null;
        try {
            tabTerakhir = daftarTab.find((tab) => tab.dataset.tabPenjual === sessionStorage.getItem(KUNCI_TAB_TERAKHIR));
            if (tabAktif) {
                sessionStorage.setItem(KUNCI_TAB_TERAKHIR, tabAktif.dataset.tabPenjual);
            } else {
                sessionStorage.removeItem(KUNCI_TAB_TERAKHIR);
            }
        } catch {
            // sessionStorage bisa diblokir; cukup tanpa animasi masuk.
        }

        tandaiAktif(tabTerakhir && tabAktif ? tabTerakhir : tabAktif, { beranimasi: false });
        tabAktif?.scrollIntoView({ block: 'nearest', inline: 'nearest' });
        requestAnimationFrame(() => requestAnimationFrame(() => tandaiAktif(tabAktif)));

        daftarTab.forEach((tab) => {
            tab.addEventListener('mouseenter', () => intip(tab));
            tab.addEventListener('focus', () => intip(tab));

            /*
             * Saat diklik, cukup teksnya yang langsung menyala. Pil jingga baru
             * meluncur di halaman tujuan (dari tab ini ke tab baru), supaya tidak
             * bergerak dua kali selama transisi halaman.
             */
            tab.addEventListener('click', (peristiwa) => {
                if (peristiwa.metaKey || peristiwa.ctrlKey || peristiwa.shiftKey || peristiwa.button !== 0) {
                    return; // dibuka di tab baru: halaman ini tetap.
                }

                daftarTab.forEach((lain) => lain.classList.toggle('disorot', lain === tab));
            });
        });

        navigasi.addEventListener('mouseleave', () => intip(null));
        navigasi.addEventListener('focusout', (peristiwa) => {
            if (!navigasi.contains(peristiwa.relatedTarget)) {
                intip(null);
            }
        });

        // Lebar tab bisa berubah saat font selesai dimuat atau jendela diubah.
        const susunUlang = () => {
            letakkan(penanda, tabAktif, { beranimasi: false });
            bayangan.classList.remove('siap');
        };
        document.fonts?.ready.then(susunUlang);
        window.addEventListener('resize', susunUlang);

        // Kembali lewat tombol Back (bfcache): kembalikan pil ke tab halaman ini.
        window.addEventListener('pageshow', (peristiwa) => {
            if (peristiwa.persisted) {
                tabAktif = daftarTab.find((tab) => tab.classList.contains('aktif'));
                tandaiAktif(tabAktif, { beranimasi: false });
                intip(null);
            }
        });
    })();
</script>
