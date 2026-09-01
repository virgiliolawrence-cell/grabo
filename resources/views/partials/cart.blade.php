{{-- Panel keranjang: isinya disimpan di localStorage lewat partials/scripts.blade.php --}}
<div id="cartBackdrop" class="fixed inset-0 z-[60] hidden bg-stone-950/50 backdrop-blur-sm" hidden></div>

<aside id="cartPanel" role="dialog" aria-modal="true" aria-labelledby="cartTitle" aria-hidden="true"
    class="fixed right-0 top-0 z-[70] flex h-full w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">

    <header class="flex items-center justify-between gap-4 border-b border-stone-100 px-6 py-5">
        <div>
            <h2 id="cartTitle" class="headline text-2xl text-stone-900">Keranjang</h2>
            <p class="mt-1 text-sm text-stone-500"><span data-cart-count>0</span> item siap dipesan</p>
        </div>

        <button type="button" id="cartClose" aria-label="Tutup keranjang"
            class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
    </header>

    <div id="cartItems" class="flex-1 space-y-3 overflow-y-auto px-6 py-5"></div>

    {{-- Tampil saat keranjang kosong --}}
    <div id="cartEmpty" class="flex flex-1 flex-col items-center justify-center gap-4 px-6 text-center">
        <span class="flex h-16 w-16 items-center justify-center rounded-full bg-neon-50 text-neon-500">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-8 w-8" aria-hidden="true">
                <path d="M3 4h2l2.4 12.4a2 2 0 0 0 2 1.6h7.2a2 2 0 0 0 2-1.6L21 8H6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="9.5" cy="20" r="1.4" fill="currentColor" />
                <circle cx="17" cy="20" r="1.4" fill="currentColor" />
            </svg>
        </span>
        <p class="text-stone-500">Keranjangmu masih kosong.<br>Pilih menu favoritmu dulu.</p>
        <a href="{{ route('menu') }}" class="rounded-full bg-neon-500 px-6 py-3 font-semibold text-white transition hover:bg-neon-600">
            Lihat Menu
        </a>
    </div>

    <footer id="cartFooter" class="border-t border-stone-100 px-6 py-5">
        {{-- Kode promo dari halaman Promo --}}
        <div class="mb-4">
            <label for="promoCode" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">Kode promo</label>

            <div id="promoForm" class="mt-2 flex gap-2">
                <input type="text" id="promoCode" placeholder="Contoh: HEMAT14" autocomplete="off"
                    class="w-full rounded-xl border border-stone-200 bg-stone-50 px-4 py-2.5 uppercase tracking-[0.1em] text-stone-900 outline-none transition placeholder:normal-case placeholder:tracking-normal placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20">
                <button type="button" id="promoApply"
                    class="shrink-0 rounded-xl border-2 border-neon-500 px-5 font-semibold text-neon-700 transition hover:bg-neon-500 hover:text-white">
                    Pakai
                </button>
            </div>

            {{-- Tampil setelah kode berhasil dipakai --}}
            <div id="promoApplied" class="mt-2 hidden items-center justify-between gap-3 rounded-xl border border-dashed border-neon-300 bg-neon-50 px-4 py-2.5">
                <span class="text-sm text-neon-800">
                    <span id="promoAppliedCode" class="font-semibold tracking-[0.1em]"></span>
                    <span id="promoAppliedLabel" class="text-neon-700/80"></span>
                </span>
                <button type="button" id="promoRemove" class="text-sm text-stone-500 underline-offset-2 transition hover:text-stone-800 hover:underline">
                    Hapus
                </button>
            </div>

            <p id="promoMessage" class="mt-2 hidden text-sm"></p>
        </div>

        <div class="space-y-1.5 border-t border-stone-100 pt-4">
            <div class="flex items-baseline justify-between text-stone-500">
                <span>Subtotal</span>
                <span id="cartSubtotal">Rp 0</span>
            </div>

            <div id="cartDiscountRow" class="flex hidden items-baseline justify-between text-neon-700">
                <span>Diskon</span>
                <span id="cartDiscount">&minus;Rp 0</span>
            </div>

            <div class="flex items-baseline justify-between pt-1">
                <span class="text-stone-500">Total</span>
                <span id="cartTotal" class="headline text-2xl text-neon-600">Rp 0</span>
            </div>
        </div>

        <button type="button" id="cartCheckout"
            class="mt-4 w-full rounded-full bg-neon-500 px-6 py-4 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.45)] transition hover:bg-neon-600">
            Lanjutkan Pesanan
        </button>

        <button type="button" id="cartClear" class="mt-2 w-full rounded-full px-6 py-2.5 text-sm text-stone-500 transition hover:bg-stone-100 hover:text-stone-800">
            Kosongkan keranjang
        </button>
    </footer>
</aside>

{{-- Pesan singkat setelah pesanan dikirim --}}
<div id="toast" role="status" aria-live="polite"
    class="pointer-events-none fixed bottom-6 left-1/2 z-[80] hidden max-w-[90vw] -translate-x-1/2 rounded-full bg-stone-900 px-6 py-3 text-center text-white shadow-2xl"></div>
