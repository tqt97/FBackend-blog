/** @type {import('tailwindcss').Config} */
// const preset = require('./vendor/filament/filament/tailwind.config.preset')

export default {
    // presets: [preset],
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        container: {
            padding: '2rem',
            screen: {
                '2xl': '1200px'
            }
        },
        extend: {
            colors: {
                'primary': {
                    DEFAULT: '#FDAE4B',
                    50: '#fff9f5',
                    100: '#FFF7EC',
                    200: '#FEE4C4',
                    300: '#FED29C',
                    400: '#FDC073',
                    500: '#FDAE4B',
                    600: '#FC9514',
                    700: '#D57802',
                    800: '#9E5902',
                    900: '#663901',
                    950: '#4B2A01'
                },
                'rum': {
                    DEFAULT: '#6C6489',
                    50: '#FFFFFF',
                    100: '#FFFFFF',
                    200: '#F0EFF3',
                    300: '#D9D7E2',
                    400: '#C3C0D1',
                    500: '#ADA8BF',
                    600: '#9790AE',
                    700: '#81799D',
                    800: '#6C6489',
                    900: '#524C69',
                    950: '#464058'
                },
            }
        }
    },
    plugins: [],
}
