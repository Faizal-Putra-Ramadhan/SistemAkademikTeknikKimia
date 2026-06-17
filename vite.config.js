import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
        allowedHosts: ['.ngrok-free.app'], // Izinkan semua subdomain ngrok
        host: '0.0.0.0', // Agar bisa diakses dari network luar
    },
    build: {
        // Nonaktifkan CSS minify agar output sama persis dengan npm run dev
        // (minifikasi bisa mengubah whitespace/format yang mempengaruhi rendering)
        cssMinify: false,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
