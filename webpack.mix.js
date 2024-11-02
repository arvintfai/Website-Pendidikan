// const mix = require("laravel-mix");

// mix.js("resources/js/app.js", "public/js").postCss(
//     "resources/css/app.css",
//     "public/css/**/*.css",
//     [require("tailwindcss")]
// );

import mix from "laravel-mix";

mix.js("resources/js/app.js", "public/js").sass(
    "resources/sass/app.scss",
    "public/css"
);
