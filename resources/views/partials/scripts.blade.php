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
                    promoTrack.scrollLeft = targetLeft;
                    return;
                }

                const startLeft = promoTrack.scrollLeft;
                const distance = targetLeft - startLeft;

                if (Math.abs(distance) < 1) {
                    return;
                }

                const startedAt = performance.now();

                const step = (now) => {
                    const progress = Math.min(1, (now - startedAt) / PROMO_SLIDE_MS);
                    promoTrack.scrollLeft = startLeft + (distance * easeOutCubic(progress));

                    if (progress < 1) {
                        promoTween = requestAnimationFrame(step);
                    }
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
            promoTrack.addEventListener('pointerdown', () => cancelAnimationFrame(promoTween));

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
