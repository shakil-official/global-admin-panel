/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.jsx',
    ],
    theme: {
        extend: {
            container: {
                center: true,
                padding: '15px',
            },
            colors: {
                primary: {
                    DEFAULT: '#011d4d',
                    light: '#0a2f78',
                    dark: '#001233',
                },
                secondary: {
                    DEFAULT: '#1e3a8a',
                    light: '#3b5ccc',
                    dark: '#162766',
                },
                accent: '#07966b',
                dark: '#1B1B1B',
                light: '#f8fafc',
                textPrimary: '#0a2f78',
                textSecondary: '#475569',
                textMuted: '#94a3b8',
                weblite: '#EA704F',
            },
        },
    },
    plugins: [],
};
