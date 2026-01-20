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
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#EEF5FF',
                    100: '#D9E8FF',
                    200: '#BBD7FF',
                    300: '#8CBDFF',
                    400: '#5699FF',
                    500: '#2F74FF',
                    600: '#1163F0',
                    700: '#0A46C4',
                    800: '#0E3A9E',
                    900: '#12347C',
                    950: '#1707B2',
                },
            },
            backgroundImage: {
                'gradient-primary': 'linear-gradient(135deg, #1163F0 0%, #1707B2 100%)',
            },
        },
    },

    plugins: [forms],
};
