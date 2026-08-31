module.exports = {
    content: ["./resources/views/**/*.blade.php", "./resources/js/**/*.js"],
    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", "ui-sans-serif", "system-ui"],
            },
        },
    },
    plugins: [require("@tailwindcss/typography")],
};
