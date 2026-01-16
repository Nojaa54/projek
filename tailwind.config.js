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
            colors: {
                ikea: {
                    blue: '#0058a3', // Brand Blue
                    yellow: '#ffdb00', // Brand Yellow
                    black: '#111111', // Text
                    gray: '#f5f5f5', // Background
                    darkgray: '#484848', // Secondary Text
                }
            }
        },
    },

    plugins: [forms],
};
