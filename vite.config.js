import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import react from "@vitejs/plugin-react";


export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.jsx'],
            refresh: true,
        }),
        react(),
        html(),
    ],

    build: {
        outDir: 'public/build', // Specify the public/build directory
        assetsDir: '',         // Optional: keeps paths cleaner
    },
});
