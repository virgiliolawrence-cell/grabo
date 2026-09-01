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
         * Tab aktif ditentukan oleh halaman yang sedang dibuka (dari server),
         * bukan oleh posisi scroll. Tautan seperti Kontak hanya
         * menggulirkan halaman tanpa memindahkan sorotan.
         */
        const siteHeader = document.querySelector('header');

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

        /* Navbar merapat saat halaman digulir. */
        function syncHeaderElevation() {
            siteHeader?.classList.toggle('is-scrolled', window.scrollY > 16);
        }

        window.addEventListener('scroll', syncHeaderElevation, { passive: true });
        syncHeaderElevation();

        window.addEventListener('resize', () => moveNavIndicator({ animate: false }));

        /*
         * Ukur ulang lewat ResizeObserver: pemanggilan saat parsing bisa terjadi
         * sebelum kapsul selesai di-layout, dan observer ini juga menangkap
         * perubahan lebar setelah webfont dimuat.
         */
        if (navCapsule && 'ResizeObserver' in window) {
            new ResizeObserver(() => moveNavIndicator({ animate: false })).observe(navCapsule);
        }

        document.fonts?.ready.then(() => moveNavIndicator({ animate: false }));
        window.addEventListener('load', () => moveNavIndicator({ animate: false }));

        moveNavIndicator({ animate: false });

        /*
         * ------------------------------------------------------------------
         * Keranjang
         * Disimpan di localStorage supaya isinya bertahan antar halaman.
         * ------------------------------------------------------------------
         */
        const CART_KEY = 'grabo.cart';
        const PROMO_KEY = 'grabo.promo';
        const rupiah = (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value);

        /* Kode promo yang dipajang di halaman Promo. */
        const PROMO_CODES = {
            HEMAT14: { discount: 2000, min: 14000, label: 'Paket hemat' },
            ROTI21: { discount: 9000, min: 18000, label: 'Beli 2 gratis 1' },
            SEGAR5: { discount: 1000, min: 5000, label: 'Promo minuman' },
        };

        function readPromo() {
            try {
                const code = localStorage.getItem(PROMO_KEY);
                return code && PROMO_CODES[code] ? code : null;
            } catch (error) {
                return null;
            }
        }

        function writePromo(code) {
            try {
                code ? localStorage.setItem(PROMO_KEY, code) : localStorage.removeItem(PROMO_KEY);
            } catch (error) {
                // Abaikan: promo hanya tidak bertahan setelah halaman ditutup.
            }
        }

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
            const subtotal = items.reduce((sum, item) => sum + (item.price * item.qty), 0);

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

            renderTotals(subtotal);
            cartItemsBox.innerHTML = '';

            items.forEach((item, index) => {
                const row = document.createElement('div');
                row.className = 'flex items-start gap-3 rounded-2xl border border-stone-100 p-3';
                row.innerHTML = `
                    <button type="button" data-cart-edit="${index}" aria-label="Ubah pesanan ${item.name}"
                        class="flex-1 rounded-xl px-1 py-0.5 text-left transition hover:bg-stone-50">
                        <span class="block font-semibold text-stone-900">${item.name}</span>
                        <span class="block text-xs uppercase tracking-[0.14em] text-stone-400">${item.stall}</span>
                        ${item.options ? `<span class="mt-1 block text-sm text-stone-500">${item.options}</span>` : ''}
                        ${item.note ? `<span class="mt-0.5 block text-sm italic text-stone-400">&ldquo;${item.note}&rdquo;</span>` : ''}
                        <span class="mt-1 block text-neon-600">${rupiah(item.price * item.qty)}</span>
                        <span class="mt-1 block text-xs text-stone-400">Ketuk untuk mengubah</span>
                    </button>
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

        /* Hitung diskon dan tampilkan rincian harga. */
        function renderTotals(subtotal) {
            const code = readPromo();
            const promo = code ? PROMO_CODES[code] : null;
            const eligible = promo && subtotal >= promo.min;
            const discount = eligible ? Math.min(promo.discount, subtotal) : 0;

            document.getElementById('cartSubtotal') && (document.getElementById('cartSubtotal').textContent = rupiah(subtotal));
            document.getElementById('cartDiscountRow')?.classList.toggle('hidden', discount === 0);
            document.getElementById('cartDiscount') && (document.getElementById('cartDiscount').textContent = '−' + rupiah(discount));

            if (cartTotalEl) {
                cartTotalEl.textContent = rupiah(subtotal - discount);
            }

            const appliedBox = document.getElementById('promoApplied');
            const promoForm = document.getElementById('promoForm');

            if (appliedBox && promoForm) {
                appliedBox.classList.toggle('hidden', !code);
                appliedBox.classList.toggle('flex', !!code);
                promoForm.classList.toggle('hidden', !!code);

                if (code) {
                    document.getElementById('promoAppliedCode').textContent = code;
                    document.getElementById('promoAppliedLabel').textContent = eligible
                        ? ` · ${promo.label}`
                        : ` · minimal belanja ${rupiah(promo.min)}`;
                }
            }
        }

        /*
         * Varian dan catatan ikut menentukan baris keranjang: dua pesanan dengan
         * pilihan berbeda tidak digabung jadi satu.
         */
        function addToCart({ name, stall, price, qty = 1, options = '', note = '', base = null, type = 'makanan', image = null, photo = true, choices = {} }) {
            const items = readCart();
            const existing = items.find((item) =>
                item.name === name && (item.options ?? '') === options && (item.note ?? '') === note);

            if (existing) {
                existing.qty += qty;
            } else {
                items.push({ name, stall, price, qty, options, note, base: base ?? price, type, image, photo, choices });
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
            // Menekan baris pesanan membuka kembali panel detail untuk diubah.
            const edit = event.target.closest('[data-cart-edit]');

            if (edit) {
                const items = readCart();
                const index = Number(edit.dataset.cartEdit);
                const item = items[index];

                if (item) {
                    openProduct({
                        name: item.name,
                        stall: item.stall,
                        price: item.base ?? item.price,
                        image: item.image,
                        photo: item.photo ?? true,
                        type: item.type ?? 'makanan',
                    }, cartToggle, { index, item });
                }

                return;
            }

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

        document.getElementById('cartClear')?.addEventListener('click', () => {
            writePromo(null);
            writeCart([]);
        });

        /* Kode promo */
        const promoInput = document.getElementById('promoCode');
        const promoMessage = document.getElementById('promoMessage');

        function showPromoMessage(text, ok) {
            if (!promoMessage) {
                return;
            }

            promoMessage.textContent = text;
            promoMessage.className = `mt-2 text-sm ${ok ? 'text-neon-700' : 'text-red-600'}`;
        }

        document.getElementById('promoApply')?.addEventListener('click', () => {
            const code = (promoInput.value || '').trim().toUpperCase();
            const promo = PROMO_CODES[code];

            if (!code) {
                showPromoMessage('Masukkan kode promonya dulu.', false);
                return;
            }

            if (!promo) {
                showPromoMessage('Kode promo tidak ditemukan.', false);
                return;
            }

            const subtotal = readCart().reduce((sum, item) => sum + (item.price * item.qty), 0);

            if (subtotal < promo.min) {
                showPromoMessage(`Kode ${code} butuh minimal belanja ${rupiah(promo.min)}.`, false);
                return;
            }

            writePromo(code);
            promoInput.value = '';
            showPromoMessage(`Kode ${code} dipakai. Potongan ${rupiah(promo.discount)}.`, true);
            renderCart();
        });

        promoInput?.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                document.getElementById('promoApply').click();
            }
        });

        document.getElementById('promoRemove')?.addEventListener('click', () => {
            writePromo(null);
            showPromoMessage('', true);
            promoMessage?.classList.add('hidden');
            renderCart();
        });

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

        /*
         * ------------------------------------------------------------------
         * Detail produk
         * Menekan kartu menu membuka panel berisi varian, catatan, dan jumlah.
         * ------------------------------------------------------------------
         */
        const OPTION_GROUPS = {
            makanan: [
                { label: 'Level pedas', choices: [{ label: 'Tidak pedas' }, { label: 'Sedang' }, { label: 'Pedas' }] },
                { label: 'Porsi', choices: [{ label: 'Normal' }, { label: 'Jumbo', price: 3000 }] },
            ],
            snack: [
                { label: 'Porsi', choices: [{ label: 'Normal' }, { label: 'Tambah saus', price: 2000 }] },
            ],
            minuman: [
                { label: 'Suhu', choices: [{ label: 'Dingin' }, { label: 'Panas' }] },
                { label: 'Gula', choices: [{ label: 'Normal' }, { label: 'Sedikit gula' }, { label: 'Tanpa gula' }] },
                { label: 'Es', choices: [{ label: 'Normal' }, { label: 'Sedikit es' }] },
            ],
        };

        const productPanel = document.getElementById('productPanel');
        const productBackdrop = document.getElementById('productBackdrop');
        const productOptionsBox = document.getElementById('productOptions');
        const productQtyEl = document.getElementById('productQty');
        const productSubtotalEl = document.getElementById('productSubtotal');
        const productNoteEl = document.getElementById('productNote');

        let activeProduct = null;
        let productQty = 1;
        let productChoices = {};
        let productOpener = null;
        let productEditIndex = null;   // diisi saat membuka dari baris keranjang

        function optionsPriceDelta() {
            return Object.values(productChoices).reduce((sum, choice) => sum + (choice.price ?? 0), 0);
        }

        function optionsSummary() {
            return Object.entries(productChoices)
                .map(([group, choice]) => `${group}: ${choice.label}`)
                .join(' · ');
        }

        function refreshProductSubtotal() {
            if (!activeProduct) {
                return;
            }

            const unit = activeProduct.price + optionsPriceDelta();
            productSubtotalEl.textContent = rupiah(unit * productQty);
            productQtyEl.textContent = productQty;
        }

        function renderProductOptions() {
            productOptionsBox.innerHTML = '';
            const groups = OPTION_GROUPS[activeProduct.type] ?? OPTION_GROUPS.makanan;

            groups.forEach((group) => {
                const wrap = document.createElement('div');
                wrap.innerHTML = `<p class="text-[11px] uppercase tracking-[0.18em] text-stone-500">${group.label}</p>`;

                const row = document.createElement('div');
                row.className = 'mt-2 flex flex-wrap gap-2';

                group.choices.forEach((choice, index) => {
                    const chip = document.createElement('button');
                    chip.type = 'button';
                    chip.textContent = choice.price
                        ? `${choice.label} +${rupiah(choice.price)}`
                        : choice.label;
                    chip.className = 'rounded-full border px-4 py-2 text-sm transition';
                    chip.setAttribute('aria-pressed', String(index === 0));

                    const paint = () => {
                        const on = productChoices[group.label]?.label === choice.label;
                        chip.className = `rounded-full border px-4 py-2 text-sm transition ${on
                            ? 'border-neon-500 bg-neon-500 text-white'
                            : 'border-stone-200 text-stone-600 hover:border-neon-300 hover:bg-neon-50'}`;
                        chip.setAttribute('aria-pressed', String(on));
                    };

                    chip.addEventListener('click', () => {
                        productChoices[group.label] = choice;
                        row.querySelectorAll('button').forEach((b) => b.dispatchEvent(new Event('repaint')));
                        refreshProductSubtotal();
                    });

                    chip.addEventListener('repaint', paint);

                    // Pilihan pertama jadi bawaan, kecuali sudah diisi dari keranjang.
                    if (index === 0 && !productChoices[group.label]) {
                        productChoices[group.label] = choice;
                    }

                    row.appendChild(chip);
                    requestAnimationFrame(paint);
                    paint();
                });

                wrap.appendChild(row);
                productOptionsBox.appendChild(wrap);
            });
        }

        function openProduct(product, opener, edit = null) {
            activeProduct = product;
            productOpener = opener ?? null;
            productEditIndex = edit ? edit.index : null;
            productQty = edit ? edit.item.qty : 1;
            productChoices = edit ? { ...(edit.item.choices ?? {}) } : {};

            document.getElementById('productTitle').textContent = product.name;
            document.getElementById('productStall').textContent = product.stall;
            document.getElementById('productPrice').textContent = rupiah(product.price);
            productNoteEl.value = edit ? (edit.item.note ?? '') : '';

            document.getElementById('productAdd').textContent = edit
                ? 'Perbarui Pesanan'
                : 'Tambah ke Keranjang';

            const image = document.getElementById('productImage');
            const imageBox = document.getElementById('productImageBox');

            if (product.image) {
                image.src = product.image;
                image.alt = product.name;
                image.className = product.photo ? 'h-full w-full object-cover' : 'h-full w-full object-contain p-2';
                imageBox.classList.remove('hidden');
            } else {
                imageBox.classList.add('hidden');
            }

            renderProductOptions();
            refreshProductSubtotal();

            closeCart();
            productBackdrop.hidden = false;
            productBackdrop.classList.remove('hidden');
            productPanel.classList.remove('translate-x-full');
            productPanel.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            document.getElementById('productClose')?.focus();
        }

        function closeProduct() {
            productBackdrop.classList.add('hidden');
            productBackdrop.hidden = true;
            productPanel.classList.add('translate-x-full');
            productPanel.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            productOpener?.focus();
            activeProduct = null;
        }

        document.querySelectorAll('[data-product]').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                try {
                    openProduct(JSON.parse(trigger.dataset.product), trigger);
                } catch (error) {
                    // Data produk rusak: biarkan kartu tidak melakukan apa-apa.
                }
            });
        });

        document.getElementById('productQtyUp')?.addEventListener('click', () => {
            productQty += 1;
            refreshProductSubtotal();
        });

        document.getElementById('productQtyDown')?.addEventListener('click', () => {
            productQty = Math.max(1, productQty - 1);
            refreshProductSubtotal();
        });

        document.getElementById('productAdd')?.addEventListener('click', () => {
            if (!activeProduct) {
                return;
            }

            const payload = {
                name: activeProduct.name,
                stall: activeProduct.stall,
                base: activeProduct.price,
                price: activeProduct.price + optionsPriceDelta(),
                qty: productQty,
                options: optionsSummary(),
                note: productNoteEl.value.trim(),
                type: activeProduct.type,
                image: activeProduct.image,
                photo: activeProduct.photo,
                choices: { ...productChoices },
            };

            if (productEditIndex === null) {
                addToCart(payload);
            } else {
                // Ganti baris yang sedang diubah, bukan menambah baris baru.
                const items = readCart();
                items[productEditIndex] = payload;
                writeCart(items);
            }

            closeProduct();
            openCart();
        });

        document.getElementById('productClose')?.addEventListener('click', closeProduct);
        productBackdrop?.addEventListener('click', closeProduct);

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && productPanel?.getAttribute('aria-hidden') === 'false') {
                closeProduct();
            }
        });

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
