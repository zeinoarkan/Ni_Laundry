import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

// HAPUS import tailwindcss dari sini

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // HAPUS tailwindcss() dari sini
    ],
}); 