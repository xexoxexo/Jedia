/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                primary: "#00aa5b",
                "primary-light": "#ecfef4",
                black: "#212121",
                "gray-dark": "#273444",
                gray: "#8492a6",
                "gray-light": "#d3dce6",
                red: "#f94d63",
                "red-light": "#ffdbe2",
            },
        },
    },
    safelist: [
        "bg-primary",
        "bg-gray",
        "hover:bg-gray",
        "hover:text-gray",
        "hover:bg-red",
        "hover:text-red",
    ],
    plugins: [],
};
