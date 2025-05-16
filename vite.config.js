import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import fs from 'fs';
import fg from "fast-glob";

const moduleJsFiles = fg.sync('Modules/**/resources/assets/js/pages/*.jsx');

export default defineConfig({
    server: {
        https: {
            key: fs.readFileSync('/Users/denispopov/Sites/laravel/certs/laravel.local-key.pem'),
            cert: fs.readFileSync('/Users/denispopov/Sites/laravel/certs/laravel.local.pem'),
        },
        host: 'laravel.local',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                ...moduleJsFiles,
            ],
            refresh: true,
        }),
        react({
            include: [
                '**/*.jsx',
                '**/*.js',
                'Modules/**/resources/assets/js/**/*.{js,jsx}',
            ],
        })
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
            '@modules': '/Modules',
        },
    },
});
