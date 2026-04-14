import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/config/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
          '@': path.resolve(__dirname, 'resources/js'),
          '@/components': path.resolve(__dirname, 'resources/PagesVuejs/components'),
          '@components': path.resolve(__dirname, 'resources/PagesVuejs/components'),
          '@PagesVuejs': path.resolve(__dirname, 'resources/PagesVuejs'),
          '@resources': path.resolve(__dirname, 'resources'),
        },
      },
    server: {
        watch: true,
    },
});
