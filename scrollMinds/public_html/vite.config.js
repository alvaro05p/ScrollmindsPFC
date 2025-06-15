import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue'; // Importar el plugin Vue
import laravel from 'laravel-vite-plugin';
import path from 'path'; // Importar path para poder configurar alias

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            'vue': path.resolve('node_modules/vue/dist/vue.esm-bundler.js') // Configurar alias para Vue
        }
    }
});

