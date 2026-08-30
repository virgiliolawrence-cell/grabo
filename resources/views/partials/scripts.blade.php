    <script>
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');

        menuToggle?.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.toggle('hidden');
            menuToggle.setAttribute('aria-expanded', String(!isHidden));
        });

        mobileMenu?.querySelectorAll('a').forEach((link) => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuToggle?.setAttribute('aria-expanded', 'false');
            });
        });

        /*
         * Scrollspy: tandai tab yang sedang dilihat.
         * Hanya aktif di beranda; halaman lain menandai tab aktifnya dari server.
         */
        const SPY_ENABLED = document.querySelector('header')?.dataset.spy === 'on';
        const NAV_SECTIONS = ['home', 'menu', 'how', 'kontak'];
        const navLinks = document.querySelectorAll('[data-nav-target]');
        const siteHeader = document.querySelector('header');
        let spyLockedUntil = 0;

        /* Penanda pil yang meluncur ke tab aktif. */
        const navCapsule = document.querySelector('nav.nav-capsule');
        const navIndicator = navCapsule?.querySelector('.nav-indicator');

        function moveNavIndicator({ animate = true } = {}) {
            if (!navCapsule || !navIndicator) {
                return;
            }

            const activeLink = navCapsule.querySelector('.nav-link.is-active');

            // Kapsul disembunyikan di layar kecil; lewati saat tidak terlihat.
            if (!activeLink || navCapsule.offsetParent === null || navCapsule.clientWidth === 0) {
                navIndicator.classList.remove('is-ready');
                return;
            }

            const capsuleBox = navCapsule.getBoundingClientRect();
            const linkBox = activeLink.getBoundingClientRect();
            const styles = getComputedStyle(navCapsule);
            const borderLeft = parseFloat(styles.borderLeftWidth) || 0;
            const borderTop = parseFloat(styles.borderTopWidth) || 0;

            if (!animate) {
                navIndicator.style.transition = 'none';
            }

            navIndicator.style.setProperty('--nav-x', `${linkBox.left - capsuleBox.left - borderLeft}px`);
            navIndicator.style.setProperty('--nav-y', `${linkBox.top - capsuleBox.top - borderTop}px`);
            navIndicator.style.setProperty('--nav-w', `${linkBox.width}px`);
            navIndicator.style.setProperty('--nav-h', `${linkBox.height}px`);
            navIndicator.classList.add('is-ready');

            if (!animate) {
                // Paksa reflow supaya posisi awal tidak ikut dianimasikan.
                void navIndicator.offsetWidth;
                navIndicator.style.transition = '';
            }
        }

        function setActiveNav(id) {
            navLinks.forEach((link) => {
                const isActive = link.dataset.navTarget === id;
                link.classList.toggle('is-active', isActive);

                if (isActive) {
                    link.setAttribute('aria-current', 'true');
                } else {
                    link.removeAttribute('aria-current');
                }
            });

            moveNavIndicator();
        }

        function currentSectionId() {
            const offset = (siteHeader?.offsetHeight ?? 0) + 40;
            let current = NAV_SECTIONS[0];

            NAV_SECTIONS.forEach((id) => {
                const section = document.getElementById(id);

                if (section && section.getBoundingClientRect().top <= offset) {
                    current = id;
                }
            });

            // Bagian terakhir (footer) sering terlalu pendek untuk melewati ambang di atas.
            if (window.scrollY + window.innerHeight >= document.documentElement.scrollHeight - 8) {
                current = NAV_SECTIONS[NAV_SECTIONS.length - 1];
            }

            return current;
        }

        let lastSpyRun = 0;

        function refreshActiveNav() {
            if (!SPY_ENABLED) {
                return;
            }

            const now = Date.now();

            // Throttle lewat stempel waktu, bukan requestAnimationFrame:
            // di tab yang tidak aktif rAF bisa tertunda dan menyangkutkan status.
            if (now < spyLockedUntil || now - lastSpyRun < 100) {
                return;
            }

            lastSpyRun = now;
            setActiveNav(currentSectionId());
        }

        navLinks.forEach((link) => {
            link.addEventListener('click', () => {
                if (!SPY_ENABLED) {
                    return; // Tautan menuju halaman lain: biarkan browser berpindah.
                }

                setActiveNav(link.dataset.navTarget);
                // Kunci sebentar supaya sorotan tidak berkedip selama smooth scroll.
                spyLockedUntil = Date.now() + 800;
            });
        });

        /* Navbar merapat saat halaman digulir. */
        function syncHeaderElevation() {
            siteHeader?.classList.toggle('is-scrolled', window.scrollY > 16);
        }

        window.addEventListener('scroll', () => {
            refreshActiveNav();
            syncHeaderElevation();
        }, { passive: true });
        syncHeaderElevation();

        window.addEventListener('resize', () => {
            refreshActiveNav();
            moveNavIndicator({ animate: false });
        });

        /*
         * Ukur ulang lewat ResizeObserver: pemanggilan saat parsing bisa terjadi
         * sebelum kapsul selesai di-layout, dan observer ini juga menangkap
         * perubahan lebar setelah webfont dimuat.
         */
        if (navCapsule && 'ResizeObserver' in window) {
            new ResizeObserver(() => moveNavIndicator({ animate: false })).observe(navCapsule);
        }

        document.fonts?.ready.then(() => moveNavIndicator({ animate: false }));
        window.addEventListener('load', () => {
            if (SPY_ENABLED) {
                setActiveNav(currentSectionId());
            }

            moveNavIndicator({ animate: false });
        });
        window.addEventListener('hashchange', () => {
            spyLockedUntil = Date.now() + 800;
            const target = window.location.hash.replace('#', '');

            if (NAV_SECTIONS.includes(target)) {
                setActiveNav(target);
            }
        });
        if (SPY_ENABLED) {
            setActiveNav(currentSectionId());
        }

        moveNavIndicator({ animate: false });

        /*
         * ------------------------------------------------------------------
         * Keranjang
         * Disimpan di localStorage supaya isinya bertahan antar halaman.
         * ------------------------------------------------------------------
         */
        const CART_KEY = 'grabo.cart';
        const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

        function readCart() {
            // localStorage bisa dilarang (mode penyamaran), jadi selalu dibungkus try.
            try {
                const raw = JSON.parse(localStorage.getItem(CART_KEY));
                return Array.isArray(raw) ? raw : [];
            } catch (error) {
                return [];
            }
        }

        function writeCart(items) {
            try {
                localStorage.setItem(CART_KEY, JSON.stringify(items));
            } catch (error) {
                // Tetap lanjut: keranjang hanya tidak bertahan setelah halaman ditutup.
            }

            renderCart(items);
        }

        const cartPanel = document.getElementById('cartPanel');
        const cartBackdrop = document.getElementById('cartBackdrop');
        const cartItemsBox = document.getElementById('cartItems');
        const cartEmptyBox = document.getElementById('cartEmpty');
        const cartFooter = document.getElementById('cartFooter');
        const cartTotalEl = document.getElementById('cartTotal');
        const cartNote = document.getElementById('cartNote');
        const cartToggle = document.getElementById('cartToggle');

        function renderCart(items = readCart()) {
            const totalQty = items.reduce((sum, item) => sum + item.qty, 0);
            const totalPrice = items.reduce((sum, item) => sum + (item.price * item.qty), 0);

            document.querySelectorAll('[data-cart-count]').forEach((el) => {
                el.textContent = totalQty;
            });

            if (!cartItemsBox) {
                return;
            }

            const isEmpty = items.length === 0;
            cartItemsBox.classList.toggle('hidden', isEmpty);
            cartEmptyBox?.classList.toggle('hidden', !isEmpty);
            cartFooter?.classList.toggle('hidden', isEmpty);

            if (cartTotalEl) {
                cartTotalEl.textContent = rupiah(totalPrice);
            }

            cartItemsBox.innerHTML = '';

            items.forEach((item, index) => {
                const row = document.createElement('div');
                row.className = 'flex items-start gap-3 rounded-2xl border border-stone-100 p-3';
                row.innerHTML = `
                    <div class="flex-1">
                        <p class="font-semibold text-stone-900">${item.name}</p>
                        <p class="text-xs uppercase tracking-[0.14em] text-stone-400">${item.stall}</p>
                        <p class="mt-1 text-neon-600">${rupiah(item.price * item.qty)}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" data-cart-dec="${index}" aria-label="Kurangi ${item.name}"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">&minus;</button>
                        <span class="w-6 text-center font-semibold">${item.qty}</span>
                        <button type="button" data-cart-inc="${index}" aria-label="Tambah ${item.name}"
                            class="flex h-8 w-8 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">+</button>
                    </div>`;
                cartItemsBox.appendChild(row);
            });
        }

        function addToCart({ name, stall, price }) {
            const items = readCart();
            const existing = items.find((item) => item.name === name);

            if (existing) {
                existing.qty += 1;
            } else {
                items.push({ name, stall, price, qty: 1 });
            }

            writeCart(items);
        }

        function openCart() {
            if (!cartPanel) {
                return;
            }

            cartBackdrop.hidden = false;
            cartBackdrop.classList.remove('hidden');
            cartPanel.classList.remove('translate-x-full');
            cartPanel.setAttribute('aria-hidden', 'false');
            cartToggle?.setAttribute('aria-expanded', 'true');
            document.body.style.overflow = 'hidden';
            document.getElementById('cartClose')?.focus();
        }

        function closeCart() {
            if (!cartPanel) {
                return;
            }

            cartBackdrop.classList.add('hidden');
            cartBackdrop.hidden = true;
            cartPanel.classList.add('translate-x-full');
            cartPanel.setAttribute('aria-hidden', 'true');
            cartToggle?.setAttribute('aria-expanded', 'false');
            document.body.style.overflow = '';
            cartToggle?.focus();
        }

        document.querySelectorAll('[data-add-to-cart]').forEach((button) => {
            button.addEventListener('click', () => {
                addToCart({
                    name: button.dataset.name,
                    stall: button.dataset.stall,
                    price: Number(button.dataset.price) || 0,
                });

                // Umpan balik singkat pada tombolnya sendiri.
                button.classList.add('bg-neon-500', 'text-white');
                setTimeout(() => button.classList.remove('bg-neon-500', 'text-white'), 450);

                if (cartToggle) {
                    cartToggle.classList.add('scale-110');
                    setTimeout(() => cartToggle.classList.remove('scale-110'), 250);
                }
            });
        });

        cartItemsBox?.addEventListener('click', (event) => {
            const inc = event.target.closest('[data-cart-inc]');
            const dec = event.target.closest('[data-cart-dec]');

            if (!inc && !dec) {
                return;
            }

            const items = readCart();
            const index = Number((inc || dec).dataset.cartInc ?? (inc || dec).dataset.cartDec);
            const item = items[index];

            if (!item) {
                return;
            }

            item.qty += inc ? 1 : -1;

            if (item.qty < 1) {
                items.splice(index, 1);
            }

            writeCart(items);
        });

        cartToggle?.addEventListener('click', openCart);
        document.getElementById('cartClose')?.addEventListener('click', closeCart);
        cartBackdrop?.addEventListener('click', closeCart);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && cartPanel?.getAttribute('aria-hidden') === 'false') {
                closeCart();
            }
        });

        document.getElementById('cartClear')?.addEventListener('click', () => writeCart([]));

        document.getElementById('cartCheckout')?.addEventListener('click', () => {
            if (readCart().length === 0 || !cartNote) {
                return;
            }

            // Belum ada halaman pembayaran, jadi beri konfirmasi dulu.
            cartNote.textContent = 'Pesanan dicatat. Halaman pembayaran menyusul.';
            cartNote.classList.remove('hidden');
            setTimeout(() => cartNote.classList.add('hidden'), 3000);
        });

        renderCart();

        /* Carousel promo (halaman menu). */
        const promoTrack = document.getElementById('promoTrack');

        if (promoTrack) {
            const PROMO_INTERVAL = 3200;   // jeda antar slide
            const PROMO_SLIDE_MS = 420;   // durasi perpindahan
            const dots = [...document.querySelectorAll('[data-promo-dot]')];
            const promoRegion = promoTrack.closest('section');
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
            let promoTimer = null;
            let promoTween = null;

            const slideCount = () => promoTrack.children.length;
            const currentSlide = () => Math.round(promoTrack.scrollLeft / promoTrack.clientWidth);

            /*
             * Animasi sendiri, bukan scrollTo({behavior:'smooth'}):
             * durasi bawaan browser terasa lambat untuk jarak selebar slide.
             */
            const easeOutCubic = (t) => 1 - Math.pow(1 - t, 3);

            const glideTo = (targetLeft) => {
                cancelAnimationFrame(promoTween);

                if (reduceMotion.matches) {
                    promoTrack.style.scrollSnapType = 'none';
                    promoTrack.scrollLeft = targetLeft;
                    promoTrack.style.scrollSnapType = '';
                    return;
                }

                const startLeft = promoTrack.scrollLeft;
                const distance = targetLeft - startLeft;

                if (Math.abs(distance) < 1) {
                    return;
                }

                const startedAt = performance.now();

                /*
                 * scroll-snap mandatory akan menarik balik posisi di tengah animasi
                 * karena tiap frame berhenti di antara dua titik snap.
                 * Matikan sementara, lalu pasang lagi untuk geseran jari.
                 */
                promoTrack.style.scrollSnapType = 'none';

                const step = (now) => {
                    const progress = Math.min(1, (now - startedAt) / PROMO_SLIDE_MS);
                    promoTrack.scrollLeft = startLeft + (distance * easeOutCubic(progress));

                    if (progress < 1) {
                        promoTween = requestAnimationFrame(step);
                        return;
                    }

                    promoTrack.style.scrollSnapType = '';
                };

                promoTween = requestAnimationFrame(step);
            };

            const goToSlide = (index) => {
                const wrapped = (index + slideCount()) % slideCount();
                glideTo(wrapped * promoTrack.clientWidth);
            };

            const stopAutoplay = () => {
                clearInterval(promoTimer);
                promoTimer = null;
            };

            const startAutoplay = () => {
                stopAutoplay();

                // Hormati preferensi pengguna yang mematikan animasi.
                if (reduceMotion.matches || slideCount() < 2) {
                    return;
                }

                promoTimer = setInterval(() => goToSlide(currentSlide() + 1), PROMO_INTERVAL);
            };

            // Setiap interaksi manual menunda putaran otomatis.
            const restartAutoplay = () => {
                stopAutoplay();
                startAutoplay();
            };

            document.getElementById('promoPrev')?.addEventListener('click', () => {
                goToSlide(currentSlide() - 1);
                restartAutoplay();
            });

            document.getElementById('promoNext')?.addEventListener('click', () => {
                goToSlide(currentSlide() + 1);
                restartAutoplay();
            });

            dots.forEach((dot, i) => {
                dot.addEventListener('click', () => {
                    goToSlide(i);
                    restartAutoplay();
                });
            });

            // Geseran jari mengambil alih dari animasi yang sedang berjalan.
            promoTrack.addEventListener('pointerdown', () => {
                cancelAnimationFrame(promoTween);
                promoTrack.style.scrollSnapType = '';
            });

            promoTrack.addEventListener('scroll', () => {
                const index = currentSlide();
                dots.forEach((dot, i) => dot.classList.toggle('is-current', i === index));
            }, { passive: true });

            // Jeda saat pengguna sedang melihat atau menyentuh slide.
            promoRegion?.addEventListener('mouseenter', stopAutoplay);
            promoRegion?.addEventListener('mouseleave', startAutoplay);
            promoRegion?.addEventListener('focusin', stopAutoplay);
            promoRegion?.addEventListener('focusout', startAutoplay);
            promoTrack.addEventListener('touchstart', stopAutoplay, { passive: true });
            promoTrack.addEventListener('touchend', restartAutoplay, { passive: true });

            // Jangan berputar saat tab tidak terlihat.
            document.addEventListener('visibilitychange', () => {
                document.hidden ? stopAutoplay() : startAutoplay();
            });

            reduceMotion.addEventListener?.('change', restartAutoplay);

            startAutoplay();
        }
    </script>
