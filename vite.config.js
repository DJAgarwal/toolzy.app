import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        {
            name: 'bootstrap-icons-font-display-swap',
            generateBundle(_, bundle) {
                for (const asset of Object.values(bundle)) {
                    if (asset.type === 'asset' && asset.fileName.endsWith('.css')) {
                        asset.source = asset.source.toString().replaceAll('font-display:block', 'font-display:swap');
                    }
                }
            },
        },
    ],
});
