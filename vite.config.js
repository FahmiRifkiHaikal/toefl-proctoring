import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true // Memastikan refresh otomatis untuk file blade, php, js, css
        })
    ],
    // TAMBAHKAN BLOK SERVER DI BAWAH INI UNTUK FIX WINDOWS/LARAGON:
    server: {
        watch: {
            usePolling: true
        }
    }
})
