import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './Modules/**/*.blade.php',
        './Modules/**/*.js',
        './Modules/**/*.jsx',
        './resources/**/*.js',
        './resources/**/*.jsx',
        './resources/**/*.css',
        './vendor/filament/**/*.blade.php',
    ],

    safelist: [
        'odd:bg-white',
        'even:bg-gray-50',
        'text-white',
        'lg:w-1/2',
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
