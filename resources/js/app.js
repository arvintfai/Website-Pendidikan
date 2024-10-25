const prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)");
const body = document.html;

if (prefersDarkScheme.matches) {
    // Jika perangkat menggunakan tema gelap
    document.documentElement.classList.add("dark");
} else {
    // Jika perangkat menggunakan tema terang
    document.documentElement.classList.remove("dark");
}

prefersDarkScheme.addEventListener("change", (e) => {
    if (e.matches) {
        document.documentElement.classList.add("dark");
    } else {
        document.documentElement.classList.remove("dark");
    }
});

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
