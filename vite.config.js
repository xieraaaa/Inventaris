import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/dashboard/user/pagination.js',
                'public/assets/node_modules/morrisjs/morris.css',
                'public/dist/css/style.min.css'
            ],
            refresh: true,
        }),
    ],
});
