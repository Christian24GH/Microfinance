import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/landing.css',
                'resources/js/app.js',
                'resources/css/app.js'
            ],
            refresh: true,
        }),
    ],
});
