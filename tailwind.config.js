import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // --- INI BAGIAN PENTING YANG KURANG ---
            colors: {
                'lpk-navy': '#0f172a',       // Biru Gelap Utama
                'lpk-navy-light': '#1e293b', // Biru Kartu (Agak Terang)
                'lpk-gold': '#c4a462',       // Emas Mewah
                'lpk-gold-hover': '#b39351', // Emas saat diklik
            }
            // --------------------------------------
        },
    },

    plugins: [forms],
};