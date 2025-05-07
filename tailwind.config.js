import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
        './vendor/filament/**/*.blade.php',
    ],

    safelist: [
        'odd:bg-white',
        'even:bg-gray-50',
        'text-white',
        {pattern: /text-(red|yellow|green|gray|white|fuchsia|rose|amber)-(100|200|300|400|500|600|700|800|900)/},
        {pattern: /bg-(red|yellow|green|gray|white|fuchsia|rose|amber)-(100|200|300|400|500|600|700|800|900)/},
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
