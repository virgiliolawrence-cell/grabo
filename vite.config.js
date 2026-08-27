import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            fonts: [
                // Figtree = grotesk antarmuka (pengganti bebas lisensi untuk Styrene/tipografi Claude).
                bunny('Figtree', {
                    weights: [400, 500, 600, 700],
                }),
                // Anton = huruf poster kondensat untuk judul raksasa.
                bunny('Anton', {
                    weights: [400],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
