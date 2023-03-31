/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['jost','Nunito', ...defaultTheme.fontFamily.sans],
            },
            // colors:{
            //     bluegray: colors.blueGray,
            // }
        },

    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
