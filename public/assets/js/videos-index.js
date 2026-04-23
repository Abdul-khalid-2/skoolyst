document.addEventListener("DOMContentLoaded", function () {
    const filterForm = document.getElementById("video-filters-form");
    if (!filterForm) {
        return;
    }

    const filterInputs = filterForm.querySelectorAll("select");
    filterInputs.forEach(function (input) {
        input.addEventListener("change", function () {
            filterForm.submit();
        });
    });

    const quickFilterBtns = document.querySelectorAll(".quick-filter-btn");
    const filterHiddenInput = document.getElementById("filter");

    quickFilterBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
            const filterValue = this.getAttribute("data-filter");

            quickFilterBtns.forEach(function (b) {
                b.classList.remove("active");
            });
            this.classList.add("active");

            if (filterHiddenInput) {
                filterHiddenInput.value = filterValue;
            }
            filterForm.submit();
        });
    });

    const videoCards = document.querySelectorAll(".video-card");
    videoCards.forEach(function (card) {
        card.addEventListener("mouseenter", function () {
            this.style.transform = "translateY(-10px)";
        });
        card.addEventListener("mouseleave", function () {
            this.style.transform = "translateY(0)";
        });
    });

    const playIcons = document.querySelectorAll(".play-icon");
    playIcons.forEach(function (icon) {
        icon.addEventListener("mouseenter", function () {
            this.style.transform = "scale(1.1)";
        });
        icon.addEventListener("mouseleave", function () {
            this.style.transform = "scale(1)";
        });
    });
});
