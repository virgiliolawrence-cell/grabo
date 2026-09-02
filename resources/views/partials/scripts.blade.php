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
                // group + focus-within: seluruh baris ikut menyala saat di-hover
                // atau saat tombol ubahnya mendapat fokus keyboard.
                row.className = 'group flex items-start gap-3 rounded-2xl border border-stone-100 p-3 transition '
                    + 'hover:border-neon-300 hover:bg-neon-50 hover:shadow-sm '
                    + 'focus-within:border-neon-300 focus-within:bg-neon-50';
                row.innerHTML = `
                    <button type="button" data-cart-edit="${index}" aria-label="Ubah pesanan ${item.name}"
                        class="flex-1 cursor-pointer rounded-xl px-1 py-0.5 text-left outline-none">
                        <span class="block font-semibold text-stone-900">${item.name}</span>
                        <span class="block text-xs uppercase tracking-[0.14em] text-stone-400">${item.stall}</span>
                        ${item.options ? `<span class="mt-1 block text-sm text-stone-500">${item.options}</span>` : ''}
                        ${item.note ? `<span class="mt-0.5 block text-sm italic text-stone-400">&ldquo;${item.note}&rdquo;</span>` : ''}
                        <span class="mt-1 block text-neon-600">${rupiah(item.price * item.qty)}</span>
                        <span class="mt-1 block text-xs text-stone-400 transition group-hover:text-neon-700">Ketuk untuk mengubah</span>
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
        function addToCart({ slug = null, name, stall, price, qty = 1, options = '', note = '', base = null, type = 'makanan', image = null, photo = true, choices = {} }) {
            const items = readCart();
            const existing = items.find((item) =>
                item.name === name && (item.options ?? '') === options && (item.note ?? '') === note);

            if (existing) {
                existing.qty += qty;
            } else {
                items.push({ slug, name, stall, price, qty, options, note, base: base ?? price, type, image, photo, choices });
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
                    // slug dibawa serta supaya baris ini bisa dibuka lagi di halaman detail.
                    slug: button.dataset.slug,
                    name: button.dataset.name,
                    stall: button.dataset.stall,
                    price: Number(button.dataset.price) || 0,
                    type: button.dataset.type || 'makanan',
                    image: button.dataset.image || null,
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
            // Menekan baris pesanan membuka kembali halaman deskripsi produknya.
            const edit = event.target.closest('[data-cart-edit]');

            if (edit) {
                const items = readCart();
                const index = Number(edit.dataset.cartEdit);
                const item = items[index];

                /*
                 * Baris keranjang lama (dari sebelum ada halaman detail) belum
                 * menyimpan slug; untuk itu cukup kembalikan ke daftar menu.
                 */
                if (item?.slug) {
                    const template = @json(route('menu.show', ['slug' => '__SLUG__']));
                    window.location.href = template.replace('__SLUG__', encodeURIComponent(item.slug)) + '?ubah=' + index;
                } else {
                    window.location.href = @json(route('menu'));
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

        /* Pesan singkat yang muncul lalu hilang sendiri. */
        let toastTimer = null;

        function showToast(text) {
            const toast = document.getElementById('toast');

            if (!toast) {
                return;
            }

            toast.textContent = text;
            toast.classList.remove('hidden');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.add('hidden'), 3200);
        }

        document.getElementById('cartCheckout')?.addEventListener('click', () => {
            if (readCart().length === 0) {
                return;
            }

            // Panel ditutup lalu lanjut ke halaman pembayaran.
            closeCart();
            window.location.href = @json(route('checkout'));
        });

        renderCart();

        /*
         * ------------------------------------------------------------------
         * Halaman deskripsi produk
         * Varian, catatan, dan jumlah dihitung di sini, lalu hasilnya masuk
         * ke keranjang yang sama dengan yang dipakai halaman lain.
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

        const detailRoot = document.getElementById('productDetail');

        if (detailRoot) {
            let product = null;

            try {
                product = JSON.parse(detailRoot.dataset.product);
            } catch (error) {
                // Data produk rusak: halaman tetap terbaca, hanya tombolnya tidak berfungsi.
            }

            if (product) {
                const optionsBox = document.getElementById('detailOptions');
                const noteEl = document.getElementById('detailNote');
                const qtyEl = document.getElementById('detailQty');
                const qtyDownBtn = document.getElementById('detailQtyDown');
                const qtyUpBtn = document.getElementById('detailQtyUp');
                const subtotalEl = document.getElementById('detailSubtotal');
                const addBtn = document.getElementById('detailAdd');
                const buyBtn = document.getElementById('detailBuy');

                /*
                 * ?ubah=N berarti halaman ini dibuka dari keranjang untuk
                 * memperbaiki satu baris pesanan, bukan menambah yang baru.
                 */
                const editIndex = Number(detailRoot.dataset.edit);
                const editing = editIndex >= 0 ? readCart()[editIndex] ?? null : null;

                let qty = editing ? editing.qty : 1;
                let choices = editing ? { ...(editing.choices ?? {}) } : {};

                if (editing) {
                    noteEl.value = editing.note ?? '';
                    addBtn.textContent = 'Perbarui Pesanan';
                    // Memperbarui pesanan lama tidak sekaligus berarti membeli.
                    buyBtn.hidden = true;
                    addBtn.classList.add('sm:col-span-2');
                }

                function optionsPriceDelta() {
                    return Object.values(choices).reduce((sum, choice) => sum + (choice.price ?? 0), 0);
                }

                function optionsSummary() {
                    return Object.entries(choices)
                        .map(([group, choice]) => `${group}: ${choice.label}`)
                        .join(' · ');
                }

                function refreshDetail() {
                    const unit = product.price + optionsPriceDelta();
                    subtotalEl.textContent = rupiah(unit * qty);
                    qtyEl.textContent = qty;
                    qtyDownBtn.disabled = qty <= 1;
                }

                function renderDetailOptions() {
                    optionsBox.innerHTML = '';
                    const groups = OPTION_GROUPS[product.type] ?? OPTION_GROUPS.makanan;

                    groups.forEach((group) => {
                        const wrap = document.createElement('div');
                        wrap.innerHTML = `<p class="text-[11px] uppercase tracking-[0.18em] text-stone-500">${group.label}</p>`;

                        const row = document.createElement('div');
                        row.className = 'mt-2 flex flex-wrap gap-2';
                        row.setAttribute('role', 'radiogroup');
                        row.setAttribute('aria-label', group.label);

                        // Pilihan pertama jadi bawaan, kecuali sudah diisi dari keranjang.
                        if (!choices[group.label]) {
                            choices[group.label] = group.choices[0];
                        }

                        group.choices.forEach((choice) => {
                            const chip = document.createElement('button');
                            chip.type = 'button';
                            chip.setAttribute('role', 'radio');
                            chip.textContent = choice.price
                                ? `${choice.label} +${rupiah(choice.price)}`
                                : choice.label;

                            const paint = () => {
                                const on = choices[group.label]?.label === choice.label;
                                chip.className = `rounded-full border px-4 py-2 text-sm transition ${on
                                    ? 'border-neon-500 bg-neon-500 text-white'
                                    : 'border-stone-200 bg-white text-stone-600 hover:border-neon-300 hover:bg-neon-50'}`;
                                chip.setAttribute('aria-checked', String(on));
                            };

                            chip.addEventListener('click', () => {
                                choices[group.label] = choice;
                                row.querySelectorAll('button').forEach((other) => other.dispatchEvent(new Event('repaint')));
                                refreshDetail();
                            });

                            chip.addEventListener('repaint', paint);
                            paint();
                            row.appendChild(chip);
                        });

                        wrap.appendChild(row);
                        optionsBox.appendChild(wrap);
                    });
                }

                function detailPayload() {
                    return {
                        slug: product.slug,
                        name: product.name,
                        stall: product.stall,
                        base: product.price,
                        price: product.price + optionsPriceDelta(),
                        qty,
                        options: optionsSummary(),
                        note: noteEl.value.trim(),
                        type: product.type,
                        image: product.image,
                        photo: product.photo,
                        choices: { ...choices },
                    };
                }

                /* Menyimpan ke keranjang, entah sebagai baris baru atau perbaikan. */
                function saveDetail() {
                    if (editing) {
                        const items = readCart();
                        items[editIndex] = detailPayload();
                        writeCart(items);
                        return;
                    }

                    addToCart(detailPayload());
                }

                qtyUpBtn?.addEventListener('click', () => {
                    qty += 1;
                    refreshDetail();
                });

                qtyDownBtn?.addEventListener('click', () => {
                    qty = Math.max(1, qty - 1);
                    refreshDetail();
                });

                addBtn?.addEventListener('click', () => {
                    saveDetail();
                    showToast(editing ? 'Pesanan diperbarui.' : `${product.name} masuk ke keranjang.`);
                    openCart();
                });

                buyBtn?.addEventListener('click', () => {
                    saveDetail();
                    window.location.href = detailRoot.dataset.checkoutUrl;
                });

                /* Galeri: thumbnail mengganti gambar utama beserta keterangannya. */
                const mainImage = document.getElementById('detailMainImage');
                const caption = document.getElementById('detailCaption');

                detailRoot.querySelectorAll('[data-gallery]').forEach((thumb) => {
                    thumb.addEventListener('click', () => {
                        if (!mainImage) {
                            return;
                        }

                        mainImage.src = thumb.dataset.src;
                        mainImage.className = thumb.dataset.photo === '1'
                            ? 'aspect-[4/3] w-full object-cover'
                            : 'aspect-[4/3] w-full object-contain p-10';

                        if (caption) {
                            caption.textContent = thumb.dataset.label;
                        }

                        detailRoot.querySelectorAll('[data-gallery]').forEach((other) => {
                            other.setAttribute('aria-current', String(other === thumb));
                        });
                    });
                });

                renderDetailOptions();
                refreshDetail();
            }
        }

        /*
         * ------------------------------------------------------------------
         * Halaman pembayaran
         * Ringkasan diambil dari keranjang di localStorage.
         * ------------------------------------------------------------------
         */
        const checkoutForm = document.getElementById('checkoutForm');

        if (checkoutForm) {
            const checkoutItems = document.getElementById('checkoutItems');
            const checkoutSubmit = document.getElementById('checkoutSubmit');

            function renderCheckout() {
                const items = readCart();
                const subtotal = items.reduce((sum, item) => sum + (item.price * item.qty), 0);
                const code = readPromo();
                const promo = code ? PROMO_CODES[code] : null;
                const discount = promo && subtotal >= promo.min ? Math.min(promo.discount, subtotal) : 0;
                const total = subtotal - discount;

                document.getElementById('checkoutEmpty').classList.toggle('hidden', items.length > 0);
                checkoutForm.classList.toggle('hidden', items.length === 0);
                checkoutSubmit.disabled = items.length === 0;

                checkoutItems.innerHTML = '';

                items.forEach((item) => {
                    const row = document.createElement('div');
                    row.className = 'flex items-start justify-between gap-3 border-b border-stone-100 pb-3 last:border-0 last:pb-0';
                    row.innerHTML = `
                        <div class="flex-1">
                            <p class="font-semibold text-stone-900">${item.qty}&times; ${item.name}</p>
                            <p class="text-xs uppercase tracking-[0.14em] text-stone-400">${item.stall}</p>
                            ${item.options ? `<p class="mt-1 text-sm text-stone-500">${item.options}</p>` : ''}
                            ${item.note ? `<p class="mt-0.5 text-sm italic text-stone-400">&ldquo;${item.note}&rdquo;</p>` : ''}
                        </div>
                        <span class="shrink-0 text-stone-900">${rupiah(item.price * item.qty)}</span>`;
                    checkoutItems.appendChild(row);
                });

                document.getElementById('checkoutSubtotal').textContent = rupiah(subtotal);
                document.getElementById('checkoutTotal').textContent = rupiah(total);
                document.getElementById('checkoutTotalInput').value = total;

                const discountRow = document.getElementById('checkoutDiscountRow');
                discountRow.classList.toggle('hidden', discount === 0);
                discountRow.classList.toggle('flex', discount > 0);

                if (discount > 0) {
                    document.getElementById('checkoutDiscount').textContent = '−' + rupiah(discount);
                    document.getElementById('checkoutPromoCode').textContent = `(${code})`;
                }
            }

            /* Rincian tambahan hanya tampil untuk metode yang membutuhkannya. */
            function syncPaymentDetails() {
                const chosen = checkoutForm.querySelector('input[name="metode"]:checked')?.value;

                document.getElementById('detailTransfer').classList.toggle('hidden', chosen !== 'transfer');
                document.getElementById('detailEwallet').classList.toggle('hidden', chosen !== 'ewallet');
                document.getElementById('detailQris').classList.toggle('hidden', chosen !== 'qris');
                document.getElementById('detailSaldo').classList.toggle('hidden', chosen !== 'saldo');
            }

            /* Tandai kartu yang sedang terpilih. */
            function syncSelectedCards() {
                checkoutForm.querySelectorAll('.option-card').forEach((card) => {
                    const input = card.querySelector('input[type="radio"]');
                    card.classList.toggle('is-selected', !!input?.checked);
                });
            }

            checkoutForm.querySelectorAll('input[type="radio"]').forEach((radio) => {
                radio.addEventListener('change', () => {
                    syncSelectedCards();
                    syncPaymentDetails();
                });
            });

            checkoutForm.addEventListener('submit', (event) => {
                if (readCart().length === 0) {
                    event.preventDefault();
                    return;
                }

                // Kunci tombol supaya pesanan tidak terkirim dua kali.
                checkoutSubmit.disabled = true;
                checkoutSubmit.innerHTML = `
                    <span class="flex items-center justify-center gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="spin h-5 w-5" aria-hidden="true">
                            <path d="M21 12a9 9 0 1 1-6.22-8.56" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" />
                        </svg>
                        Memproses pesanan…
                    </span>`;
            });

            renderCheckout();
            syncPaymentDetails();
            syncSelectedCards();
        }

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
