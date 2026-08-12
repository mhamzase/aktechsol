function toggleDropdown(btn) {
    const dropdown = btn.parentElement.querySelector(".dropdown-menu");
    dropdown.classList.toggle("hidden");
}

// Close dropdown when clicking outside
window.addEventListener("click", function (e) {
    if (!e.target.closest(".dropdown-container")) {
        document.querySelectorAll(".dropdown-menu").forEach(function (menu) {
            menu.classList.add("hidden");
        });
    }
});
