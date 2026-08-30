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
                    <li><a href="#home" class="transition hover:text-neon-400">Home</a></li>
                    <li><a href="#menu" class="transition hover:text-neon-400">Menu</a></li>
                    <li><a href="#how" class="transition hover:text-neon-400">Orders</a></li>
                    <li><a href="#kontak" class="transition hover:text-neon-400">Login</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-[11px] uppercase tracking-[0.2em] text-white">Kontak</h3>
                <ul class="mt-4 space-y-2.5">
                    <li>Koperasi &amp; Kantin Sekolah</li>
                    <li>(021) 555&ndash;0198</li>
                    <li>halo@grabo.sch.id</li>
                </ul>
            </div>
        </div>

        {{-- Kredit foto (wajib untuk lisensi CC BY / CC BY-SA) --}}
        @php
            $photoCredits = [
                ['dish' => 'Nasi goreng', 'author' => 'shankar s.', 'license' => 'CC BY 2.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Nasi_Goreng_Kampung_(11967588375).jpg'],
                ['dish' => 'Mie ayam', 'author' => 'Midori', 'license' => 'CC BY-SA 3.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Mi_ayam_jamur.JPG'],
                ['dish' => 'Ayam geprek', 'author' => 'Supardisahabu', 'license' => 'CC BY-SA 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Ayam_Geprek_Uyat.jpg'],
                ['dish' => 'Batagor', 'author' => 'Ignatiaadela', 'license' => 'CC BY-SA 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Batagor_dan_Bumbu_Kacang.jpg'],
                ['dish' => 'Roti bakar', 'author' => 'Supardisahabu', 'license' => 'CC BY-SA 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Roti_Panggang.jpg'],
                ['dish' => 'Es teh manis', 'author' => 'Cendy00', 'license' => 'CC BY 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Es_teh_manis.jpg'],
                ['dish' => 'Mie goreng jawa', 'author' => 'Cun Cun', 'license' => 'CC BY-SA 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Making_mie_goreng_jawa2.jpg'],
                ['dish' => 'Roti bakar coklat', 'author' => 'Indonesiagood', 'license' => 'CC BY 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Roti_Bakar_Rasa_Durian_dan_Coklat.jpg'],
                ['dish' => 'Es cendol', 'author' => 'Dd993f2', 'license' => 'CC BY-SA 4.0', 'url' => 'https://commons.wikimedia.org/wiki/File:Akaka_Cendol_Melaka.jpg'],
            ];
        @endphp
        <div class="border-t border-white/10">
            <div class="mx-auto max-w-7xl px-6 py-6 text-xs leading-relaxed text-stone-500">
                <span class="text-stone-400">Kredit foto menu (via Wikimedia Commons):</span>
                @foreach ($photoCredits as $credit)
                    <a href="{{ $credit['url'] }}" target="_blank" rel="noopener noreferrer" class="transition hover:text-neon-400">{{ $credit['dish'] }} &mdash; {{ $credit['author'] }} ({{ $credit['license'] }})</a>@if (! $loop->last) &middot; @endif
                @endforeach
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-6 py-6 text-[11px] uppercase tracking-[0.16em] sm:flex-row">
                <span>&copy; {{ date('Y') }} Grabo. Seluruh hak cipta dilindungi.</span>
                <span>Dikembangkan untuk kantin sekolah</span>
            </div>
        </div>
    </footer>
