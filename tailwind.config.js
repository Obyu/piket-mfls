import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
       './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js'
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#f8f6f0',
                    100: '#f0ead8',
                    200: '#f0d68a',
                    300: '#d4a843',
                    400: '#c9972e',
                    500: '#b8861a',
                    600: '#9c6e10',
                    700: '#7d5a0f',
                    800: '#1a2332',
                    900: '#0f1b2d',
                    950: '#0a1220',
                },
                navy: {
                    50: '#f0f2f5',
                    100: '#d8dde5',
                    200: '#b1baca',
                    300: '#8a97b0',
                    400: '#637495',
                    500: '#3c517b',
                    600: '#243447',
                    700: '#1a2332',
                    800: '#0f1b2d',
                    900: '#0a1220',
                    950: '#060d18',
                },
            },
        },
    },

    plugins: [forms],
};
