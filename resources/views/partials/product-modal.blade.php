{{-- Panel detail produk: dibuka saat kartu menu ditekan (lihat partials/scripts.blade.php) --}}
<div id="productBackdrop" class="fixed inset-0 z-[60] hidden bg-stone-950/50 backdrop-blur-sm" hidden></div>

<aside id="productPanel" role="dialog" aria-modal="true" aria-labelledby="productTitle" aria-hidden="true"
    class="fixed right-0 top-0 z-[70] flex h-full w-full max-w-md translate-x-full flex-col bg-white shadow-2xl transition-transform duration-300 ease-out">

    <header class="flex items-center justify-between gap-4 border-b border-stone-100 px-6 py-5">
        <h2 class="headline text-2xl text-stone-900">Detail Produk</h2>

        <button type="button" id="productClose" aria-label="Tutup detail produk"
            class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:bg-stone-100">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="h-5 w-5" aria-hidden="true">
                <path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
            </svg>
        </button>
    </header>

    <div class="flex-1 overflow-y-auto px-6 py-5">
        {{-- Ringkasan produk --}}
        <div class="flex items-center gap-4">
            <span id="productImageBox" class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-neon-50">
                <img id="productImage" src="" alt="" class="h-full w-full object-cover">
            </span>
            <div>
                <p id="productStall" class="text-[10px] uppercase tracking-[0.18em] text-stone-400"></p>
                <h3 id="productTitle" class="mt-1 headline text-2xl text-stone-900"></h3>
                <p id="productPrice" class="mt-1 text-neon-600"></p>
            </div>
        </div>

        {{-- Pilihan varian, dibuat ulang tiap produk dibuka --}}
        <div id="productOptions" class="mt-6 space-y-5"></div>

        {{-- Catatan tambahan --}}
        <div class="mt-6">
            <label for="productNote" class="block text-[11px] uppercase tracking-[0.18em] text-stone-500">
                Catatan tambahan
            </label>
            <textarea id="productNote" rows="2" maxlength="120"
                placeholder="Contoh: sambalnya dipisah"
                class="mt-2 w-full resize-none rounded-xl border border-stone-200 bg-stone-50 px-4 py-3 text-stone-900 outline-none transition placeholder:text-stone-400 focus:border-neon-500 focus:bg-white focus:ring-4 focus:ring-neon-500/20"></textarea>
        </div>

        {{-- Jumlah --}}
        <div class="mt-6 flex items-center justify-between">
            <span class="text-[11px] uppercase tracking-[0.18em] text-stone-500">Jumlah</span>

            <div class="flex items-center gap-3">
                <button type="button" id="productQtyDown" aria-label="Kurangi jumlah"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-xl text-stone-600 transition hover:bg-stone-100">&minus;</button>
                <span id="productQty" aria-live="polite" class="w-8 text-center text-lg font-semibold text-stone-900">1</span>
                <button type="button" id="productQtyUp" aria-label="Tambah jumlah"
                    class="flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-xl text-stone-600 transition hover:bg-stone-100">+</button>
            </div>
        </div>
    </div>

    <footer class="border-t border-stone-100 px-6 py-5">
        <div class="flex items-baseline justify-between">
            <span class="text-stone-500">Subtotal</span>
            <span id="productSubtotal" class="headline text-2xl text-neon-600">Rp 0</span>
        </div>

        <button type="button" id="productAdd"
            class="mt-4 w-full rounded-full bg-neon-500 px-6 py-4 font-semibold text-white shadow-[0_0_28px_rgba(255,106,0,0.45)] transition hover:bg-neon-600">
            Tambah ke Keranjang
        </button>
    </footer>
</aside>
