(function () {
    function equalizeRecetasCards() {
        const grid = document.querySelector(".pipa-grid--recetas");
        if (!grid) return;

        const cards = grid.querySelectorAll(".pipa-card");
        cards.forEach(function (card) {
            card.style.height = "auto";
        });

        if (window.innerWidth <= 700) return;

        let maxHeight = 0;
        cards.forEach(function (card) {
            maxHeight = Math.max(maxHeight, card.offsetHeight);
        });

        cards.forEach(function (card) {
            card.style.height = maxHeight + "px";
        });
    }

    document.addEventListener("DOMContentLoaded", equalizeRecetasCards);

    let resizeTimer;
    window.addEventListener("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(equalizeRecetasCards, 150);
    });
})();
