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
                padding: "15px",
            },
            colors: {
                accent: "#07966b",
                weblite: "#EA704F",
                dark: "#1B1B1B",
            },
        },
    },
    plugins: [],
}

