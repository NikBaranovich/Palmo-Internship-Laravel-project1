import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/js/components/venues.js',
            ],
            refresh: true,
        }),
    ],
    // assetsInclude: [
    //     'resources/js/components/*.*'
    // ],
    server: {
        hmr: {
            host: 'localhost'
        }
      }
});
