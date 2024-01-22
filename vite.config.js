import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import {glob} from "glob";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/css/app.css',
                ...glob.sync("resources/js/**/*.js"),
                ...glob.sync("resources/css/**/*.css"),
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost'
        }
      }
});
