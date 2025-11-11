import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import image from '@rollup/plugin-image';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/admin-bar.css',
                'resources/css/auth.css',
                'resources/js/app.js',
                'resources/js/admin.js',
                'resources/js/pagebuilder.js'
            ],
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
        image(),
        viteStaticCopy({
            targets: [
                { src: 'resources/images/*', dest: 'assets/images' }
            ]
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
