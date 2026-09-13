    {{-- Footer --}}
    <footer id="kontak" class="scroll-mt-28 bg-stone-950 text-stone-400">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-10 px-6 py-14 sm:grid-cols-2 lg:grid-cols-4">
            <div class="lg:col-span-2">
                <span class="inline-flex items-center rounded-xl bg-white px-3 py-2">
                    <img src="{{ $graboLogo }}" alt="Grabo &mdash; kantin sekolah digital" class="h-24 w-auto" width="93" height="96">
                </span>
                <p class="mt-4 max-w-sm leading-relaxed">
                    Layanan pemesanan kantin sekolah yang membantu siswa memesan makanan lebih cepat, lebih tertib,
                    dan tanpa antrean panjang.
                </p>
            </div>

            <div>
                <h3 class="text-[11px] uppercase tracking-[0.2em] text-white">Navigasi</h3>
                <ul class="mt-4 space-y-2.5">
                    {{-- Jangkar #home hanya ada di beranda, jadi dari halaman lain pakai rute. --}}
                    <li><a href="{{ request()->routeIs('beranda') ? '#beranda' : route('beranda') }}" class="transition hover:text-neon-400">Beranda</a></li>
                    <li><a href="{{ route('menu') }}" class="transition hover:text-neon-400">Menu</a></li>
                    <li><a href="{{ route('promo') }}" class="transition hover:text-neon-400">Promo</a></li>
                    <li><a href="{{ route('kontak') }}" class="transition hover:text-neon-400">Kontak</a></li>
                    <li><a href="{{ route('pembayaran') }}" class="transition hover:text-neon-400">Pembayaran</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-[11px] uppercase tracking-[0.2em] text-white">Kontak</h3>
                <ul class="mt-4 space-y-2.5">
                    <li>Koperasi &amp; Kantin Sekolah</li>
                    <li>
                        <a href="tel:+62{{ ltrim(preg_replace('/\D/', '', config('grabo.kontak.telepon')), '0') }}" class="transition hover:text-neon-400">
                            (021) 555&ndash;0198
                        </a>
                    </li>
                    <li>
                        <a href="mailto:{{ config('grabo.kontak.email') }}" class="transition hover:text-neon-400">
                            {{ config('grabo.kontak.email') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>


        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-6 py-6 text-[11px] uppercase tracking-[0.16em] sm:flex-row">
                <span>&copy; {{ date('Y') }} Grabo. Seluruh hak cipta dilindungi.</span>
                <span>Dikembangkan untuk kantin sekolah</span>
            </div>
        </div>
    </footer>
