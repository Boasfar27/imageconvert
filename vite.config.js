import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true
        }),
    ],
    build: {
        // Generate manifest.json in outDir
        manifest: true,
        rollupOptions: {
            // Externalize deps that shouldn't be bundled
            external: [
                // Add any other externals if needed
            ],
        },
    },
    // Use a fixed base path in production
    base: process.env.APP_ENV === 'production' ? '/build/' : '',
});
