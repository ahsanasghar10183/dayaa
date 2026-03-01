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
                    600: '#0F69F3', // Dayaa primary blue
                    700: '#0A46C4',
                    800: '#0E3A9E',
                    900: '#170AB5', // Dayaa gradient end
                    950: '#12096E',
                },
                'dayaa-blue': '#0F69F3',
                'dayaa-purple': '#170AB5',
            },
            backgroundImage: {
                'gradient-primary': 'linear-gradient(135deg, #0F69F3 0%, #170AB5 100%)',
                'gradient-dayaa': 'linear-gradient(135deg, #0F69F3 0%, #170AB5 100%)',
                'gradient-dayaa-hover': 'linear-gradient(135deg, #0d5ad4 0%, #140998 100%)',
            },
        },
    },

    plugins: [forms],
};
