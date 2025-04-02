import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                    additionalData: `@import "bootstrap/dist/css/bootstrap.min.css";`
         
                },
            },
        }),
        
    ],
    resolve: {
        alias: {
          
            '@fullcalendar/vue3': '@fullcalendar/vue3',       }
      }
});
