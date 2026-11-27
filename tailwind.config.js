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
                sans: ['"Inter"', ...defaultTheme.fontFamily.sans],
                serif: ['"Outfit"', '"Inter"', ...defaultTheme.fontFamily.sans],
                display: ['"Outfit"', '"Inter"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                meadow: {
                    50: '#F3F9F1',
                    100: '#E3F1DF',
                    200: '#C6E3BF',
                    300: '#9DCF93',
                    400: '#7FC072',
                    500: '#6BAF5D',
                    600: '#559648',
                    700: '#417637',
                    800: '#355E2E',
                    900: '#2A4A24',
                },
                earth: {
                    50: '#FBF6F1',
                    100: '#F5E6D3',
                    200: '#EBD1B3',
                    300: '#D9AE84',
                    400: '#C69163',
                    500: '#B57A4E',
                    600: '#9A643D',
                    700: '#7C4F30',
                    800: '#634028',
                    900: '#4E3321',
                },
                skyblock: {
                    50: '#F2F9FF',
                    100: '#E2F2FF',
                    200: '#C5E6FF',
                    300: '#A7D8FF',
                    400: '#79C1FC',
                    500: '#4EA5F2',
                    600: '#3184D6',
                    700: '#2567AB',
                },
                canvas: {
                    DEFAULT: '#F9FAFB',
                    warm: '#FAF7F2',
                    card: '#FFFFFF',
                    border: '#E8E2D9',
                },
                ink: {
                    DEFAULT: '#2B2B2B',
                    muted: '#626862',
                },
            },
        },
    },

    plugins: [forms],
};
