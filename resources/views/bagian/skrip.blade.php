    <script>
        /*
         * Dropdown bilah atas (pilih stan, bahasa).
         * Satu penanganan untuk semua [data-dropdown]: klik membuka, klik di
         * luar atau Escape menutup, dan hanya satu yang terbuka sekaligus.
         */
        const daftarDropdown = [...document.querySelectorAll('[data-dropdown]')].map((akar) => ({
            akar,
            tombol: akar.querySelector('[data-dropdown-tombol]'),
            panel: akar.querySelector('[data-dropdown-panel]'),
            panah: akar.querySelector('[data-dropdown-panah]'),
        }));

        function tutupSemuaDropdown(kecuali = null) {
            daftarDropdown.forEach((dropdown) => {
                if (dropdown === kecuali || dropdown.panel.hidden) {
                    return;
                }

                dropdown.panel.hidden = true;
                dropdown.tombol.setAttribute('aria-expanded', 'false');
                dropdown.panah?.classList.remove('rotate-180');
            });
        }

        daftarDropdown.forEach((dropdown) => {
            dropdown.tombol?.addEventListener('click', (peristiwa) => {
                peristiwa.stopPropagation();
                tutupSemuaDropdown(dropdown);

                const sedangDibuka = dropdown.panel.hidden;
                dropdown.panel.hidden = !sedangDibuka;
                dropdown.tombol.setAttribute('aria-expanded', String(sedangDibuka));
                dropdown.panah?.classList.toggle('rotate-180', sedangDibuka);
            });
        });

        if (daftarDropdown.length > 0) {
            document.addEventListener('click', (peristiwa) => {
                if (!peristiwa.target.closest('[data-dropdown]')) {
                    tutupSemuaDropdown();
                }
            });

            document.addEventListener('keydown', (peristiwa) => {
                if (peristiwa.key === 'Escape') {
                    tutupSemuaDropdown();
                }
            });
        }

        const tombolMenu = document.getElementById('tombolMenu');
        const menuPonsel = document.getElementById('menuPonsel');

        tombolMenu?.addEventListener('click', () => {
            const sedangTersembunyi = menuPonsel.classList.toggle('hidden');
            tombolMenu.setAttribute('aria-expanded', String(!sedangTersembunyi));
        });

        menuPonsel?.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                menuPonsel.classList.add('hidden');
                tombolMenu?.setAttribute('aria-expanded', 'false');
            });
        });

        /*
         * Tab aktif ditentukan oleh halaman yang sedang dibuka (dari server),
         * bukan oleh posisi scroll. Tautan seperti Kontak hanya
         * menggulirkan halaman tanpa memindahkan sorotan.
         */
        const kepalaSitus = document.querySelector('header');

        /* Penanda pil yang meluncur ke tab aktif. */
        const kapsulNav = document.querySelector('nav.kapsul-nav');
        const penandaNav = kapsulNav?.querySelector('.penanda-nav');

        function geserPenandaNav({ beranimasi = true } = {}) {
            if (!kapsulNav || !penandaNav) {
                return;
            }

            const tautanAktif = kapsulNav.querySelector('.tautan-nav.is-aktif');

            // Kapsul disembunyikan di layar kecil; lewati saat tidak terlihat.
            if (!tautanAktif || kapsulNav.offsetParent === null || kapsulNav.clientWidth === 0) {
                penandaNav.classList.remove('is-siap');
                return;
            }

            const kotakKapsul = kapsulNav.getBoundingClientRect();
            const kotakTautan = tautanAktif.getBoundingClientRect();
            const gaya = getComputedStyle(kapsulNav);
            const tepiKiri = parseFloat(gaya.borderLeftWidth) || 0;
            const tepiAtas = parseFloat(gaya.borderTopWidth) || 0;

            if (!beranimasi) {
                penandaNav.style.transition = 'none';
            }

            penandaNav.style.setProperty('--nav-x', `${kotakTautan.left - kotakKapsul.left - tepiKiri}px`);
            penandaNav.style.setProperty('--nav-y', `${kotakTautan.top - kotakKapsul.top - tepiAtas}px`);
            penandaNav.style.setProperty('--nav-w', `${kotakTautan.width}px`);
            penandaNav.style.setProperty('--nav-h', `${kotakTautan.height}px`);
            penandaNav.classList.add('is-siap');

            if (!beranimasi) {
                // Paksa reflow supaya posisi awal tidak ikut dianimasikan.
                void penandaNav.offsetWidth;
                penandaNav.style.transition = '';
            }
        }

        /* Navbar merapat saat halaman digulir. */
        function seleraskanBayangKepala() {
            kepalaSitus?.classList.toggle('is-tergulir', window.scrollY > 16);
        }

        window.addEventListener('scroll', seleraskanBayangKepala, { passive: true });
        seleraskanBayangKepala();

        window.addEventListener('resize', () => geserPenandaNav({ beranimasi: false }));

        /*
         * Ukur ulang lewat ResizeObserver: pemanggilan saat parsing bisa terjadi
         * sebelum kapsul selesai di-layout, dan observer ini juga menangkap
         * perubahan lebar setelah webfont dimuat.
         */
        if (kapsulNav && 'ResizeObserver' in window) {
            new ResizeObserver(() => geserPenandaNav({ beranimasi: false })).observe(kapsulNav);
        }

        document.fonts?.ready.then(() => geserPenandaNav({ beranimasi: false }));
        window.addEventListener('load', () => geserPenandaNav({ beranimasi: false }));

        geserPenandaNav({ beranimasi: false });

        /*
         * ------------------------------------------------------------------
         * Keranjang
         * Disimpan di localStorage supaya isinya bertahan antar halaman.
         * ------------------------------------------------------------------
         */
        const KUNCI_KERANJANG = 'grabo.keranjang';
        const KUNCI_PROMO = 'grabo.promo';
        const rupiah = (nilai) => 'Rp ' + new Intl.NumberFormat('id-ID').format(nilai);

        /*
         * Kode promo yang berlaku, dari PromoController::kodePromo().
         * Hanya untuk menghitung tampilan; potongan sebenarnya dihitung ulang
         * di server saat pesanan dikirim.
         */
        const KODE_PROMO = @json(\App\Http\Controllers\PromoController::kodePromo());

        function bacaPromo() {
            try {
                const kode = localStorage.getItem(KUNCI_PROMO);
                return kode && KODE_PROMO[kode] ? kode : null;
            } catch (galat) {
                return null;
            }
        }

        function simpanPromo(kode) {
            try {
                kode ? localStorage.setItem(KUNCI_PROMO, kode) : localStorage.removeItem(KUNCI_PROMO);
            } catch (galat) {
                // Abaikan: promo hanya tidak bertahan setelah halaman ditutup.
            }

            /*
             * Kode promo bisa berubah dari mana saja: kartu di halaman promo,
             * kotak isian di keranjang, atau tombol kosongkan. Kabarkan lewat
             * satu peristiwa supaya penanda kartu promo tidak perlu menebak
             * urutan pemasangan listener.
             */
            document.dispatchEvent(new CustomEvent('grabo:promo-berubah', { detail: { kode } }));
        }

        function bacaKeranjang() {
            // localStorage bisa dilarang (mode penyamaran), jadi selalu dibungkus try.
            try {
                const mentah = JSON.parse(localStorage.getItem(KUNCI_KERANJANG));
                return Array.isArray(mentah) ? mentah : [];
            } catch (galat) {
                return [];
            }
        }

        function simpanKeranjang(daftarBaris) {
            try {
                localStorage.setItem(KUNCI_KERANJANG, JSON.stringify(daftarBaris));
            } catch (galat) {
                // Tetap lanjut: keranjang hanya tidak bertahan setelah halaman ditutup.
            }

            gambarKeranjang(daftarBaris);
        }

        const panelKeranjang = document.getElementById('panelKeranjang');
        const latarKeranjang = document.getElementById('latarKeranjang');
        const kotakIsiKeranjang = document.getElementById('isiKeranjang');
        const kotakKeranjangKosong = document.getElementById('keranjangKosong');
        const kakiKeranjang = document.getElementById('kakiKeranjang');
        const elemenTotalKeranjang = document.getElementById('totalKeranjang');
        const tombolKeranjang = document.getElementById('tombolKeranjang');

        function gambarKeranjang(daftarBaris = bacaKeranjang()) {
            const totalJumlah = daftarBaris.reduce((akumulasi, baris) => akumulasi + baris.jumlah, 0);
            const subtotal = daftarBaris.reduce((akumulasi, baris) => akumulasi + (baris.harga * baris.jumlah), 0);

            document.querySelectorAll('[data-jumlah-keranjang]').forEach((el) => {
                el.textContent = totalJumlah;
            });

            if (!kotakIsiKeranjang) {
                return;
            }

            const kosong = daftarBaris.length === 0;
            kotakIsiKeranjang.classList.toggle('hidden', kosong);
            kotakKeranjangKosong?.classList.toggle('hidden', !kosong);
            kakiKeranjang?.classList.toggle('hidden', kosong);

            gambarTotal(subtotal);
            kotakIsiKeranjang.innerHTML = '';

            daftarBaris.forEach((baris, indeks) => {
                const barisTampilan = document.createElement('div');
                // group + focus-within: seluruh baris ikut menyala saat di-hover
                // atau saat tombol ubahnya mendapat fokus keyboard.
                barisTampilan.className = 'group flex items-start gap-3 rounded-2xl border border-stone-100 p-3 transition '
                    + 'hover:border-neon-300 hover:bg-neon-50 hover:shadow-sm '
                    + 'focus-within:border-neon-300 focus-within:bg-neon-50';
                barisTampilan.innerHTML = `
                    <button type="button" data-keranjang-ubah="${indeks}" aria-label="Ubah pesanan ${baris.nama}"
                        class="flex-1 cursor-pointer rounded-xl px-1 py-0.5 text-left outline-none">
                        <span class="block font-semibold text-stone-900">${baris.nama}</span>
                        <span class="block text-xs uppercase tracking-[0.14em] text-stone-400">${baris.stan}</span>
                        ${baris.pilihan ? `<span class="mt-1 block text-sm text-stone-500">${baris.pilihan}</span>` : ''}
                        ${baris.catatan ? `<span class="mt-0.5 block text-sm italic text-stone-400">&ldquo;${baris.catatan}&rdquo;</span>` : ''}
                        <span class="mt-1 block text-neon-600">${rupiah(baris.harga * baris.jumlah)}</span>
                        <span class="mt-1 block text-xs text-stone-400 transition group-hover:text-neon-700">Ketuk untuk mengubah</span>
                    </button>
                    <div class="flex items-center gap-2">
                        <button type="button" data-keranjang-kurang="${indeks}" aria-label="Kurangi ${baris.nama}"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">&minus;</button>
                        <span class="w-6 text-center font-semibold">${baris.jumlah}</span>
                        <button type="button" data-keranjang-tambah="${indeks}" aria-label="Tambah ${baris.nama}"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">+</button>
                    </div>`;
                kotakIsiKeranjang.appendChild(barisTampilan);
            });
        }

        /* Hitung diskon dan tampilkan rincian harga. */
        function gambarTotal(subtotal) {
            const kode = bacaPromo();
            const promo = kode ? KODE_PROMO[kode] : null;
            const memenuhiSyarat = promo && subtotal >= promo.belanjaMinimal;
            const potongan = memenuhiSyarat ? Math.min(promo.potongan, subtotal) : 0;

            document.getElementById('subtotalKeranjang') && (document.getElementById('subtotalKeranjang').textContent = rupiah(subtotal));
            document.getElementById('barisDiskonKeranjang')?.classList.toggle('hidden', potongan === 0);
            document.getElementById('diskonKeranjang') && (document.getElementById('diskonKeranjang').textContent = '−' + rupiah(potongan));

            if (elemenTotalKeranjang) {
                elemenTotalKeranjang.textContent = rupiah(subtotal - potongan);
            }

            const kotakPromoTerpakai = document.getElementById('promoTerpakai');
            const formPromo = document.getElementById('formPromo');

            if (kotakPromoTerpakai && formPromo) {
                kotakPromoTerpakai.classList.toggle('hidden', !kode);
                kotakPromoTerpakai.classList.toggle('flex', !!kode);
                formPromo.classList.toggle('hidden', !!kode);

                if (kode) {
                    document.getElementById('kodePromoTerpakai').textContent = kode;
                    document.getElementById('labelPromoTerpakai').textContent = memenuhiSyarat
                        ? ` · ${promo.label}`
                        : ` · minimal belanja ${rupiah(promo.belanjaMinimal)}`;
                }
            }
        }

        /*
         * Varian dan catatan ikut menentukan baris keranjang: dua pesanan dengan
         * pilihan berbeda tidak digabung jadi satu.
         */
        function tambahKeKeranjang({ slug = null, nama, stan, harga, jumlah = 1, pilihan = '', catatan = '', hargaDasar = null, jenis = 'makanan', gambar = null, foto = true, pilihanVarian = {} }) {
            const daftarBaris = bacaKeranjang();
            const barisSama = daftarBaris.find((baris) =>
                baris.nama === nama && (baris.pilihan ?? '') === pilihan && (baris.catatan ?? '') === catatan);

            if (barisSama) {
                barisSama.jumlah += jumlah;
            } else {
                daftarBaris.push({ slug, nama, stan, harga, jumlah, pilihan, catatan, hargaDasar: hargaDasar ?? harga, jenis, gambar, foto, pilihanVarian });
            }

            simpanKeranjang(daftarBaris);
        }

        function bukaKeranjang() {
            if (!panelKeranjang) {
                return;
            }

            latarKeranjang.hidden = false;
            latarKeranjang.classList.remove('hidden');
            panelKeranjang.classList.remove('translate-x-full');
            panelKeranjang.setAttribute('aria-hidden', 'false');
            tombolKeranjang?.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            document.getElementById('tutupKeranjang')?.focus();
        }

        function tutupPanelKeranjang() {
            if (!panelKeranjang) {
                return;
            }

            latarKeranjang.classList.add('hidden');
            latarKeranjang.hidden = true;
            panelKeranjang.classList.add('translate-x-full');
            panelKeranjang.setAttribute('aria-hidden', 'true');
            tombolKeranjang?.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            tombolKeranjang?.focus();
        }

        document.querySelectorAll('[data-tambah-keranjang]').forEach((button) => {
            button.addEventListener('click', () => {
                tambahKeKeranjang({
                    // slug dibawa serta supaya baris ini bisa dibuka lagi di halaman detail.
                    slug: button.dataset.slug,
                    nama: button.dataset.nama,
                    stan: button.dataset.stan,
                    harga: Number(button.dataset.harga) || 0,
                    jenis: button.dataset.jenis || 'makanan',
                    gambar: button.dataset.gambar || null,
                });

                // Umpan balik singkat pada tombolnya sendiri.
                button.classList.add('bg-neon-500', 'text-white');
                setTimeout(() => button.classList.remove('bg-neon-500', 'text-white'), 450);

                if (tombolKeranjang) {
                    tombolKeranjang.classList.add('scale-110');
                    setTimeout(() => tombolKeranjang.classList.remove('scale-110'), 250);
                }
            });
        });

        kotakIsiKeranjang?.addEventListener('click', (peristiwa) => {
            // Menekan baris pesanan membuka kembali halaman deskripsi produknya.
            const ubah = peristiwa.target.closest('[data-keranjang-ubah]');

            if (ubah) {
                const daftarBaris = bacaKeranjang();
                const indeks = Number(ubah.dataset.cartEdit);
                const baris = daftarBaris[indeks];

                /*
                 * Baris keranjang lama (dari sebelum ada halaman detail) belum
                 * menyimpan slug; untuk itu cukup kembalikan ke daftar menu.
                 */
                if (baris?.slug) {
                    const pola = @json(route('menu.rincian', ['slug' => '__SLUG__']));
                    window.location.href = pola.replace('__SLUG__', encodeURIComponent(baris.slug)) + '?ubah=' + indeks;
                } else {
                    window.location.href = @json(route('menu'));
                }

                return;
            }

            const menambah = peristiwa.target.closest('[data-keranjang-tambah]');
            const mengurangi = peristiwa.target.closest('[data-keranjang-kurang]');

            if (!menambah && !mengurangi) {
                return;
            }

            const daftarBaris = bacaKeranjang();
            const indeks = Number((menambah || mengurangi).dataset.cartInc ?? (menambah || mengurangi).dataset.cartDec);
            const baris = daftarBaris[indeks];

            if (!baris) {
                return;
            }

            baris.jumlah += menambah ? 1 : -1;

            if (baris.jumlah < 1) {
                daftarBaris.splice(indeks, 1);
            }

            simpanKeranjang(daftarBaris);
        });

        tombolKeranjang?.addEventListener('click', bukaKeranjang);
        document.getElementById('tutupKeranjang')?.addEventListener('click', tutupPanelKeranjang);
        latarKeranjang?.addEventListener('click', tutupPanelKeranjang);

        document.addEventListener('keydown', (peristiwa) => {
            if (peristiwa.key === 'Escape' && panelKeranjang?.getAttribute('aria-hidden') === 'false') {
                tutupPanelKeranjang();
            }
        });

        document.getElementById('kosongkanKeranjang')?.addEventListener('click', () => {
            simpanPromo(null);
            simpanKeranjang([]);
        });

        /* Kode promo */
        const kolomKodePromo = document.getElementById('kodePromo');
        const pesanPromo = document.getElementById('pesanPromo');

        function tampilkanPesanPromo(teks, berhasilDipakai) {
            if (!pesanPromo) {
                return;
            }

            pesanPromo.textContent = teks;
            pesanPromo.className = `mt-2 text-sm ${berhasilDipakai ? 'text-neon-700' : 'text-red-600'}`;
        }

        document.getElementById('pakaiPromo')?.addEventListener('click', () => {
            const kode = (kolomKodePromo.value || '').trim().toUpperCase();
            const promo = KODE_PROMO[kode];

            if (!kode) {
                tampilkanPesanPromo('Masukkan kode promonya dulu.', false);
                return;
            }

            if (!promo) {
                tampilkanPesanPromo('Kode promo tidak ditemukan.', false);
                return;
            }

            const subtotal = bacaKeranjang().reduce((akumulasi, baris) => akumulasi + (baris.harga * baris.jumlah), 0);

            if (subtotal < promo.belanjaMinimal) {
                tampilkanPesanPromo(`Kode ${kode} butuh minimal belanja ${rupiah(promo.belanjaMinimal)}.`, false);
                return;
            }

            simpanPromo(kode);
            kolomKodePromo.value = '';
            tampilkanPesanPromo(`Kode ${kode} dipakai. Potongan ${rupiah(promo.potongan)}.`, true);
            gambarKeranjang();
        });

        kolomKodePromo?.addEventListener('keydown', (peristiwa) => {
            if (peristiwa.key === 'Enter') {
                peristiwa.preventDefault();
                document.getElementById('pakaiPromo').click();
            }
        });

        /*
         * ------------------------------------------------------------------
         * Tombol "Salin kode" di halaman promo
         *
         * Sekali tekan kodenya disalin ke papan klip sekaligus disimpan ke
         * keranjang. Kartu yang kodenya sedang tersimpan diberi tanda, dan
         * tandanya tetap terpasang setelah halaman dimuat ulang.
         * ------------------------------------------------------------------
         */
        const kartuPromo = [...document.querySelectorAll('[data-kartu-promo]')];

        if (kartuPromo.length > 0) {
            /* Cadangan bila Clipboard API ditolak (halaman tanpa HTTPS atau di iframe). */
            function salinLewatKolomTeks(teks) {
                const kolom = document.createElement('textarea');
                kolom.value = teks;
                kolom.setAttribute('readonly', '');
                kolom.style.position = 'fixed';
                kolom.style.opacity = '0';
                document.body.appendChild(kolom);
                kolom.select();

                let berhasil = false;

                try {
                    berhasil = document.execCommand('copy');
                } catch (galat) {
                    berhasil = false;
                }

                kolom.remove();

                return berhasil;
            }

            async function salinKode(teks) {
                try {
                    await navigator.clipboard.writeText(teks);
                    return true;
                } catch (galat) {
                    return salinLewatKolomTeks(teks);
                }
            }

            /* Tandai kartu mana yang kodenya sedang tersimpan di keranjang. */
            function tandaiKartuAktif() {
                const aktif = bacaPromo();

                kartuPromo.forEach((kartu) => {
                    const dipakai = kartu.dataset.kartuPromo === aktif;

                    kartu.querySelector('.kartu-promo')?.classList.toggle('is-dipakai', dipakai);
                    kartu.querySelector('[data-pita-dipakai]').hidden = !dipakai;
                });
            }

            let jedaLabel = null;

            kartuPromo.forEach((kartu) => {
                const tombol = kartu.querySelector('[data-pakai-promo]');
                const teksTombol = kartu.querySelector('[data-teks-tombol]');
                const ikonSalin = kartu.querySelector('[data-ikon-salin]');
                const ikonCentang = kartu.querySelector('[data-ikon-centang]');
                const pesan = kartu.querySelector('[data-pesan-kode]');

                tombol?.addEventListener('click', async () => {
                    const kode = tombol.dataset.pakaiPromo;
                    const promo = KODE_PROMO[kode];

                    if (!promo) {
                        pesan.hidden = false;
                        pesan.className = 'mt-2 text-sm text-red-600';
                        pesan.textContent = `Kode ${kode} sedang tidak aktif.`;
                        return;
                    }

                    const tersalin = await salinKode(kode);

                    simpanPromo(kode);
                    gambarKeranjang();
                    tandaiKartuAktif();

                    // Tombolnya berubah sebentar supaya jelas kodenya sudah disalin.
                    clearTimeout(jedaLabel);
                    ikonSalin.hidden = true;
                    ikonCentang.hidden = false;
                    teksTombol.textContent = tersalin ? 'Tersalin!' : 'Dipakai!';
                    tombol.classList.add('bg-emerald-600', 'text-white');
                    tombol.classList.remove('bg-white', 'text-neon-700');

                    const subtotal = bacaKeranjang().reduce((akumulasi, baris) => akumulasi + (baris.harga * baris.jumlah), 0);
                    const cukup = subtotal >= promo.belanjaMinimal;

                    pesan.hidden = false;
                    pesan.className = `mt-2 text-sm ${cukup ? 'text-emerald-700' : 'text-stone-500'}`;
                    pesan.textContent = [
                        tersalin ? `Kode ${kode} tersalin.` : `Kode ${kode} dipakai.`,
                        cukup
                            ? `Potongan ${rupiah(promo.potongan)} langsung terpakai di keranjang.`
                            : `Berlaku setelah belanja ${rupiah(promo.belanjaMinimal)}.`,
                    ].join(' ');

                    tampilkanNotifikasi(tersalin
                        ? `Kode ${kode} tersalin dan tersimpan di keranjang.`
                        : `Kode ${kode} tersimpan di keranjang.`);

                    jedaLabel = setTimeout(() => {
                        ikonSalin.hidden = false;
                        ikonCentang.hidden = true;
                        teksTombol.textContent = 'Salin kode';
                        tombol.classList.remove('bg-emerald-600', 'text-white');
                        tombol.classList.add('bg-white', 'text-neon-700');
                    }, 2400);
                });
            });

            tandaiKartuAktif();

            // Ikut berubah saat promo dipasang atau dilepas dari panel keranjang.
            document.addEventListener('grabo:promo-berubah', tandaiKartuAktif);
        }

        document.getElementById('hapusPromo')?.addEventListener('click', () => {
            simpanPromo(null);
            tampilkanPesanPromo('', true);
            pesanPromo?.classList.add('hidden');
            gambarKeranjang();
        });

        /* Pesan singkat yang muncul lalu hilang sendiri. */
        let jedaNotifikasi = null;

        function tampilkanNotifikasi(teks) {
            const elemenNotifikasi = document.getElementById('notifikasi');

            if (!elemenNotifikasi) {
                return;
            }

            elemenNotifikasi.textContent = teks;
            elemenNotifikasi.classList.remove('hidden');
            clearTimeout(jedaNotifikasi);
            jedaNotifikasi = setTimeout(() => elemenNotifikasi.classList.add('hidden'), 3200);
        }

        document.getElementById('lanjutKePembayaran')?.addEventListener('click', () => {
            if (bacaKeranjang().length === 0) {
                return;
            }

            // Panel ditutup lalu lanjut ke halaman pembayaran.
            tutupPanelKeranjang();
            window.location.href = @json(route('pembayaran'));
        });

        gambarKeranjang();

        /*
         * ------------------------------------------------------------------
         * Halaman deskripsi produk
         * Varian, catatan, dan jumlah dihitung di sini, lalu hasilnya masuk
         * ke keranjang yang sama dengan yang dipakai halaman lain.
         * ------------------------------------------------------------------
         */
        const GRUP_PILIHAN = {
            makanan: [
                { label: 'Tingkat pedas', pilihanVarian: [{ label: 'Tidak pedas' }, { label: 'Sedang' }, { label: 'Pedas' }] },
                { label: 'Porsi', pilihanVarian: [{ label: 'Normal' }, { label: 'Jumbo', harga: 3000 }] },
            ],
            camilan: [
                { label: 'Porsi', pilihanVarian: [{ label: 'Normal' }, { label: 'Tambah saus', harga: 2000 }] },
            ],
            minuman: [
                { label: 'Suhu', pilihanVarian: [{ label: 'Dingin' }, { label: 'Panas' }] },
                { label: 'Gula', pilihanVarian: [{ label: 'Normal' }, { label: 'Sedikit gula' }, { label: 'Tanpa gula' }] },
                { label: 'Es', pilihanVarian: [{ label: 'Normal' }, { label: 'Sedikit es' }] },
            ],
        };

        const akarRincian = document.getElementById('rincianProduk');

        if (akarRincian) {
            let sajian = null;

            try {
                sajian = JSON.parse(akarRincian.dataset.sajian);
            } catch (galat) {
                // Data produk rusak: halaman tetap terbaca, hanya tombolnya tidak berfungsi.
            }

            if (sajian) {
                const kotakPilihan = document.getElementById('pilihanRincian');
                const elemenCatatan = document.getElementById('catatanRincian');
                const elemenJumlah = document.getElementById('jumlahRincian');
                const tombolKurangJumlah = document.getElementById('kurangJumlah');
                const tombolTambahJumlah = document.getElementById('tambahJumlah');
                const elemenSubtotal = document.getElementById('subtotalRincian');
                const tombolTambahRincian = document.getElementById('tombolTambah');
                const tombolBeliRincian = document.getElementById('tombolBeli');

                /*
                 * ?ubah=N berarti halaman ini dibuka dari keranjang untuk
                 * memperbaiki satu baris pesanan, bukan menambah yang baru.
                 */
                const indeksUbah = Number(akarRincian.dataset.ubah);
                const sedangDiubah = indeksUbah >= 0 ? bacaKeranjang()[indeksUbah] ?? null : null;

                let jumlah = sedangDiubah ? sedangDiubah.jumlah : 1;
                let pilihanVarian = sedangDiubah ? { ...(sedangDiubah.pilihanVarian ?? {}) } : {};

                if (sedangDiubah) {
                    elemenCatatan.value = sedangDiubah.note ?? '';
                    tombolTambahRincian.textContent = 'Perbarui Pesanan';
                    // Memperbarui pesanan lama tidak sekaligus berarti membeli.
                    tombolBeliRincian.hidden = true;
                    tombolTambahRincian.classList.add('sm:col-span-2');
                }

                function selisihHargaPilihan() {
                    return Object.values(pilihanVarian).reduce((akumulasi, satuPilihan) => akumulasi + (satuPilihan.harga ?? 0), 0);
                }

                function ringkasanPilihan() {
                    return Object.entries(pilihanVarian)
                        .map(([group, satuPilihan]) => `${group}: ${satuPilihan.label}`)
                        .join(' · ');
                }

                function segarkanRincian() {
                    const hargaSatuan = sajian.harga + selisihHargaPilihan();
                    elemenSubtotal.textContent = rupiah(hargaSatuan * jumlah);
                    elemenJumlah.textContent = jumlah;
                    tombolKurangJumlah.disabled = jumlah <= 1;
                }

                function gambarPilihanRincian() {
                    kotakPilihan.innerHTML = '';
                    const grupPilihan = GRUP_PILIHAN[sajian.jenis] ?? GRUP_PILIHAN.makanan;

                    grupPilihan.forEach((group) => {
                        const pembungkus = document.createElement('div');
                        pembungkus.innerHTML = `<p class="text-[11px] uppercase tracking-[0.18em] text-stone-500">${group.label}</p>`;

                        const barisTampilan = document.createElement('div');
                        barisTampilan.className = 'mt-2 flex flex-pembungkus gap-2';
                        barisTampilan.setAttribute('role', 'radiogroup');
                        barisTampilan.setAttribute('aria-label', group.label);

                        // Pilihan pertama jadi bawaan, kecuali sudah diisi dari keranjang.
                        if (!pilihanVarian[group.label]) {
                            pilihanVarian[group.label] = group.pilihanVarian[0];
                        }

                        group.pilihanVarian.forEach((satuPilihan) => {
                            const tombolPilihan = document.createElement('button');
                            tombolPilihan.type = 'button';
                            tombolPilihan.setAttribute('role', 'radio');
                            tombolPilihan.textContent = satuPilihan.harga
                                ? `${satuPilihan.label} +${rupiah(satuPilihan.harga)}`
                                : satuPilihan.label;

                            const lukis = () => {
                                const menyala = pilihanVarian[group.label]?.label === satuPilihan.label;
                                tombolPilihan.className = `rounded-full border px-4 py-2 text-sm transition ${menyala
                                    ? 'border-neon-500 bg-neon-500 text-white'
                                    : 'border-stone-200 bg-white text-stone-600 hover:border-neon-300 hover:bg-neon-50'}`;
                                tombolPilihan.setAttribute('aria-checked', String(menyala));
                            };

                            tombolPilihan.addEventListener('click', () => {
                                pilihanVarian[group.label] = satuPilihan;
                                barisTampilan.querySelectorAll('button').forEach((other) => other.dispatchEvent(new Event('repaint')));
                                segarkanRincian();
                            });

                            tombolPilihan.addEventListener('repaint', lukis);
                            lukis();
                            barisTampilan.appendChild(tombolPilihan);
                        });

                        pembungkus.appendChild(barisTampilan);
                        kotakPilihan.appendChild(pembungkus);
                    });
                }

                function muatanRincian() {
                    return {
                        slug: sajian.slug,
                        nama: sajian.nama,
                        stan: sajian.stan,
                        hargaDasar: sajian.harga,
                        harga: sajian.harga + selisihHargaPilihan(),
                        jumlah,
                        pilihan: ringkasanPilihan(),
                        catatan: elemenCatatan.value.trim(),
                        jenis: sajian.jenis,
                        gambar: sajian.gambar,
                        foto: sajian.foto,
                        pilihanVarian: { ...pilihanVarian },
                    };
                }

                /* Menyimpan ke keranjang, entah sebagai baris baru atau perbaikan. */
                function simpanRincian() {
                    if (sedangDiubah) {
                        const daftarBaris = bacaKeranjang();
                        daftarBaris[indeksUbah] = muatanRincian();
                        simpanKeranjang(daftarBaris);
                        return;
                    }

                    tambahKeKeranjang(muatanRincian());
                }

                tombolTambahJumlah?.addEventListener('click', () => {
                    jumlah += 1;
                    segarkanRincian();
                });

                tombolKurangJumlah?.addEventListener('click', () => {
                    jumlah = Math.max(1, jumlah - 1);
                    segarkanRincian();
                });

                tombolTambahRincian?.addEventListener('click', () => {
                    simpanRincian();
                    tampilkanNotifikasi(sedangDiubah ? 'Pesanan diperbarui.' : `${sajian.nama} masuk ke keranjang.`);
                    bukaKeranjang();
                });

                tombolBeliRincian?.addEventListener('click', () => {
                    simpanRincian();
                    window.location.href = akarRincian.dataset.alamatPembayaran;
                });

                /* Galeri: thumbnail mengganti gambar utama beserta keterangannya. */
                const gambarUtama = document.getElementById('gambarUtamaRincian');
                const keterangan = document.getElementById('keteranganRincian');

                akarRincian.querySelectorAll('[data-galeri]').forEach((thumb) => {
                    thumb.addEventListener('click', () => {
                        if (!gambarUtama) {
                            return;
                        }

                        gambarUtama.src = thumb.dataset.sumber;
                        gambarUtama.className = thumb.dataset.foto === '1'
                            ? 'aspect-[4/3] w-full object-cover'
                            : 'aspect-[4/3] w-full object-contain p-10';

                        if (keterangan) {
                            keterangan.textContent = thumb.dataset.keterangan;
                        }

                        akarRincian.querySelectorAll('[data-galeri]').forEach((other) => {
                            other.setAttribute('aria-current', String(other === thumb));
                        });
                    });
                });

                gambarPilihanRincian();
                segarkanRincian();
            }
        }

        /*
         * ------------------------------------------------------------------
         * Halaman pembayaran
         * Ringkasan diambil dari keranjang di localStorage.
         * ------------------------------------------------------------------
         */
        const formPembayaran = document.getElementById('formPembayaran');

        if (formPembayaran) {
            const isiPembayaran = document.getElementById('isiPembayaran');
            const kirimPembayaran = document.getElementById('kirimPembayaran');

            function gambarPembayaran() {
                const daftarBaris = bacaKeranjang();
                const subtotal = daftarBaris.reduce((akumulasi, baris) => akumulasi + (baris.harga * baris.jumlah), 0);
                const kode = bacaPromo();
                const promo = kode ? KODE_PROMO[kode] : null;
                const potongan = promo && subtotal >= promo.belanjaMinimal ? Math.min(promo.potongan, subtotal) : 0;
                const total = subtotal - potongan;

                document.getElementById('pembayaranKosong').classList.toggle('hidden', daftarBaris.length > 0);
                formPembayaran.classList.toggle('hidden', daftarBaris.length === 0);
                kirimPembayaran.disabled = daftarBaris.length === 0;

                isiPembayaran.innerHTML = '';

                daftarBaris.forEach((baris) => {
                    const barisTampilan = document.createElement('div');
                    barisTampilan.className = 'flex items-start justify-between gap-3 border-b border-stone-100 pb-3 last:border-0 last:pb-0';
                    barisTampilan.innerHTML = `
                        <div class="flex-1">
                            <p class="font-semibold text-stone-900">${baris.jumlah}&times; ${baris.nama}</p>
                            <p class="text-xs uppercase tracking-[0.14em] text-stone-400">${baris.stan}</p>
                            ${baris.pilihan ? `<p class="mt-1 text-sm text-stone-500">${baris.pilihan}</p>` : ''}
                            ${baris.catatan ? `<p class="mt-0.5 text-sm italic text-stone-400">&ldquo;${baris.catatan}&rdquo;</p>` : ''}
                        </div>
                        <span class="shrink-0 text-stone-900">${rupiah(baris.harga * baris.jumlah)}</span>`;
                    isiPembayaran.appendChild(barisTampilan);
                });

                document.getElementById('subtotalPembayaran').textContent = rupiah(subtotal);
                document.getElementById('totalPembayaran').textContent = rupiah(total);
                document.getElementById('inputTotalPembayaran').value = total;

                // Server memakai daftar ini untuk menghitung ulang dan menyimpan pesanan.
                document.getElementById('inputKeranjangPembayaran').value = JSON.stringify(
                    daftarBaris.map(({ slug, name, stall, price, jumlah, options, note }) =>
                        ({ slug, name, stall, price, jumlah, options, note }))
                );
                document.getElementById('inputPromoPembayaran').value = kode ?? '';

                const barisDiskon = document.getElementById('barisDiskonPembayaran');
                barisDiskon.classList.toggle('hidden', potongan === 0);
                barisDiskon.classList.toggle('flex', potongan > 0);

                if (potongan > 0) {
                    document.getElementById('diskonPembayaran').textContent = '−' + rupiah(potongan);
                    document.getElementById('kodePromoPembayaran').textContent = `(${kode})`;
                }
            }

            /* Rincian tambahan hanya tampil untuk metode yang membutuhkannya. */
            function seleraskanRincianBayar() {
                const metodeTerpilih = formPembayaran.querySelector('input[name="metode"]:checked')?.value;

                document.getElementById('rincianTransfer').classList.toggle('hidden', metodeTerpilih !== 'transfer');
                document.getElementById('rincianDompet').classList.toggle('hidden', metodeTerpilih !== 'dompet');
                document.getElementById('rincianQris').classList.toggle('hidden', metodeTerpilih !== 'qris');
                document.getElementById('rincianSaldo').classList.toggle('hidden', metodeTerpilih !== 'saldo');
            }

            /* Tandai kartu yang sedang terpilih. */
            function tandaiKartuTerpilih() {
                formPembayaran.querySelectorAll('.kartu-pilihan').forEach((kartu) => {
                    const kolomIsian = kartu.querySelector('input[type="radio"]');
                    kartu.classList.toggle('is-terpilih', !!kolomIsian?.checked);
                });
            }

            formPembayaran.querySelectorAll('input[type="radio"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    tandaiKartuTerpilih();
                    seleraskanRincianBayar();
                });
            });

            formPembayaran.addEventListener('submit', (peristiwa) => {
                if (bacaKeranjang().length === 0) {
                    peristiwa.preventDefault();
                    return;
                }

                // Kunci tombol supaya pesanan tidak terkirim dua kali.
                kirimPembayaran.disabled = true;
                kirimPembayaran.innerHTML = `
                    <span class="flex items-center justify-center gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="berputar h-5 w-5" aria-hidden="true">
                            <path d="M21 12a9 9 0 1 1-6.22-8.56" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                        </svg>
                        Memproses pesanan…
                    </span>`;
            });

            gambarPembayaran();
            seleraskanRincianBayar();
            tandaiKartuTerpilih();
        }

        /* Carousel promo (halaman menu). */
        const jalurPromo = document.getElementById('jalurPromo');

        if (jalurPromo) {
            const JEDA_PROMO = 3200;   // jeda antar slide
            const DURASI_GESER_PROMO = 420;   // durasi perpindahan
            const titikBingkai = [...document.querySelectorAll('[data-titik-promo]')];
            const wilayahPromo = jalurPromo.closest('section');
            const kurangiGerak = window.matchMedia('(prefers-reduced-motion: reduce)');
            let jedaGeserPromo = null;
            let animasiPromo = null;

            const jumlahBingkai = () => jalurPromo.children.length;
            const bingkaiKini = () => Math.round(jalurPromo.scrollLeft / jalurPromo.clientWidth);

            /*
             * Animasi sendiri, bukan scrollTo({behavior:'smooth'}):
             * durasi bawaan browser terasa lambat untuk jarak selebar slide.
             */
            const pelanKeluarKubik = (t) => 1 - Math.pow(1 - t, 3);

            const luncurKe = (sasaranKiri) => {
                cancelAnimationFrame(animasiPromo);

                if (kurangiGerak.matches) {
                    jalurPromo.style.scrollSnapType = 'none';
                    jalurPromo.scrollLeft = sasaranKiri;
                    jalurPromo.style.scrollSnapType = '';
                    return;
                }

                const mulaiKiri = jalurPromo.scrollLeft;
                const jarak = sasaranKiri - mulaiKiri;

                if (Math.abs(jarak) < 1) {
                    return;
                }

                const mulaiPada = performance.now();

                /*
                 * scroll-snap mandatory akan menarik balik posisi di tengah animasi
                 * karena tiap frame berhenti di antara dua titik snap.
                 * Matikan sementara, lalu pasang lagi untuk geseran jari.
                 */
                jalurPromo.style.scrollSnapType = 'none';

                const langkah = (now) => {
                    const kemajuan = Math.min(1, (now - mulaiPada) / DURASI_GESER_PROMO);
                    jalurPromo.scrollLeft = mulaiKiri + (jarak * pelanKeluarKubik(kemajuan));

                    if (kemajuan < 1) {
                        animasiPromo = requestAnimationFrame(langkah);
                        return;
                    }

                    jalurPromo.style.scrollSnapType = '';
                };

                animasiPromo = requestAnimationFrame(langkah);
            };

            const keBingkai = (indeks) => {
                const berputarBalik = (indeks + jumlahBingkai()) % jumlahBingkai();
                luncurKe(berputarBalik * jalurPromo.clientWidth);
            };

            const hentikanGeserOtomatis = () => {
                clearInterval(jedaGeserPromo);
                jedaGeserPromo = null;
            };

            const mulaiGeserOtomatis = () => {
                hentikanGeserOtomatis();

                // Hormati preferensi pengguna yang mematikan animasi.
                if (kurangiGerak.matches || jumlahBingkai() < 2) {
                    return;
                }

                jedaGeserPromo = setInterval(() => keBingkai(bingkaiKini() + 1), JEDA_PROMO);
            };

            // Setiap interaksi manual menunda putaran otomatis.
            const ulangiGeserOtomatis = () => {
                hentikanGeserOtomatis();
                mulaiGeserOtomatis();
            };

            document.getElementById('promoSebelumnya')?.addEventListener('click', () => {
                keBingkai(bingkaiKini() - 1);
                ulangiGeserOtomatis();
            });

            document.getElementById('promoBerikutnya')?.addEventListener('click', () => {
                keBingkai(bingkaiKini() + 1);
                ulangiGeserOtomatis();
            });

            titikBingkai.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    keBingkai(i);
                    ulangiGeserOtomatis();
                });
            });

            // Geseran jari mengambil alih dari animasi yang sedang berjalan.
            jalurPromo.addEventListener('pointerdown', () => {
                cancelAnimationFrame(animasiPromo);
                jalurPromo.style.scrollSnapType = '';
            });

            jalurPromo.addEventListener('scroll', () => {
                const indeks = bingkaiKini();
                titikBingkai.forEach((dot, i) => dot.classList.toggle('is-kini', i === indeks));
            }, { passive: true });

            // Jeda saat pengguna sedang melihat atau menyentuh slide.
            wilayahPromo?.addEventListener('mouseenter', hentikanGeserOtomatis);
            wilayahPromo?.addEventListener('mouseleave', mulaiGeserOtomatis);
            wilayahPromo?.addEventListener('focusin', hentikanGeserOtomatis);
            wilayahPromo?.addEventListener('focusout', mulaiGeserOtomatis);
            jalurPromo.addEventListener('touchstart', hentikanGeserOtomatis, { passive: true });
            jalurPromo.addEventListener('touchend', ulangiGeserOtomatis, { passive: true });

            // Jangan berputar saat tab tidak terlihat.
            document.addEventListener('visibilitychange', () => {
                document.hidden ? hentikanGeserOtomatis() : mulaiGeserOtomatis();
            });

            kurangiGerak.addEventListener?.('change', ulangiGeserOtomatis);

            mulaiGeserOtomatis();
        }
    </script>
