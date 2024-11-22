import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/filament/admin/themes/sknor-theme.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                entryFileNames: ({ name }) => name, // Nama file output untuk JavaScript
                chunkFileNames: ({ name }) => name, // Nama file untuk potongan kode (chunks)
                assetFileNames: ({ name }) => name, // Nama file untuk CSS dan aset lainnya
            },
        },
    },
});
