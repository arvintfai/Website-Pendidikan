import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: [
                ...refreshPaths,
                "app/Filament/**",
                "app/Forms/Components/**",
                "app/Livewire/**",
                "app/Infolists/Components/**",
                "app/Providers/Filament/**",
                "app/Tables/Columns/**",
            ],
        }),
    ],
    server: {
        host: "0.0.0.0", // atau gunakan IP lokal, misal: '192.168.0.10'
        port: 5173, // atau port lain jika 5173 sudah digunakan
        hmr: {
            host: "192.168.201.182", // IP lokal perangkat Anda
        },
    },
});
