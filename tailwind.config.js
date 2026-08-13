import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './DICT_SDN_ILCD.html',
        './public/tmd/participants/index.html',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
                cinzel: ['Cinzel', 'serif'],
            },
            colors: {
                dict: {
                    blue: '#003366',
                    red: '#CE1126',
                    yellow: '#FCD116',
                    accent: '#0055A5',
                    light: '#F0F4F8',
                    dark: '#0A192F',
                    gold: '#D4AF37',
                },
            },
            fontSize: {
                xs: ['clamp(0.75rem, 0.625rem + 0.2vw, 0.875rem)', { lineHeight: '1.25rem' }],
                sm: ['clamp(0.875rem, 0.75rem + 0.25vw, 1.0625rem)', { lineHeight: '1.5rem' }],
                base: ['clamp(1rem, 0.85rem + 0.3vw, 1.25rem)', { lineHeight: '1.625rem' }],
                lg: ['clamp(1.0625rem, 0.875rem + 0.375vw, 1.375rem)', { lineHeight: '1.75rem' }],
                xl: ['clamp(1.125rem, 0.9375rem + 0.425vw, 1.5rem)', { lineHeight: '1.875rem' }],
                '2xl': ['clamp(1.25rem, 1rem + 0.55vw, 1.75rem)', { lineHeight: '2.25rem' }],
                '3xl': ['clamp(1.5rem, 1.125rem + 0.8vw, 2.25rem)', { lineHeight: '2.5rem' }],
            },
        },
    },
    plugins: [],
};
