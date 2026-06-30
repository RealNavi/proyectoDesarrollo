(function () {
    document.addEventListener("DOMContentLoaded", function () {

        const button = document.querySelector(".menu-toggle-btn");
        const menu = document.getElementById("site-navigation");
        const overlay = document.querySelector(".menu-overlay");

        if (!button || !menu) return;

        function closeMenu() {
            button.classList.remove("is-active");
            menu.classList.remove("is-active");
            overlay?.classList.remove("is-active");
            button.setAttribute("aria-expanded", "false");
            document.body.style.overflow = "";
        }

        function openMenu() {
            button.classList.add("is-active");
            menu.classList.add("is-active");
            overlay?.classList.add("is-active");
            button.setAttribute("aria-expanded", "true");
            document.body.style.overflow = "hidden";
        }

        function toggleMenu() {
            const isOpen = button.classList.contains("is-active");
            if (isOpen) closeMenu();
            else openMenu();
        }
        button.addEventListener("click", toggleMenu);
        overlay?.addEventListener("click", closeMenu);
        menu.querySelectorAll("a").forEach(link => {
            link.addEventListener("click", closeMenu);
        });
        document.addEventListener("keydown", function (e) {
            if (e.key === "Escape") closeMenu();
        });

    });
})();