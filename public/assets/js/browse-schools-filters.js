let filterTimeout;

function getFilterBaseUrl() {
    const filterContainer = document.querySelector(".filter-container");
    return filterContainer ? filterContainer.dataset.baseUrl : window.location.pathname;
}

function applyFilters() {
    const search = document.getElementById("searchInput")?.value || "";
    const location = document.getElementById("locationFilter")?.value || "";
    const type = document.getElementById("typeFilter")?.value || "";
    const curriculum = document.getElementById("curriculumFilter")?.value || "";

    const params = new URLSearchParams();
    if (search) params.append("search", search);
    if (location) params.append("location", location);
    if (type) params.append("type", type);
    if (curriculum) params.append("curriculum", curriculum);

    const targetUrl = params.toString()
        ? getFilterBaseUrl() + "?" + params.toString()
        : getFilterBaseUrl();

    window.location.href = targetUrl;
}

function clearFilters() {
    window.location.href = getFilterBaseUrl();
}

function observeElements() {
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px"
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add("visible");
            }
        });
    }, observerOptions);

    document.querySelectorAll(".school-card").forEach(function (card) {
        observer.observe(card);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("input", function () {
            clearTimeout(filterTimeout);
            filterTimeout = setTimeout(applyFilters, 800);
        });
    }

    const locationFilter = document.getElementById("locationFilter");
    const typeFilter = document.getElementById("typeFilter");
    const curriculumFilter = document.getElementById("curriculumFilter");
    const clearFiltersBtn = document.getElementById("clearFiltersBtn");

    if (locationFilter) locationFilter.addEventListener("change", applyFilters);
    if (typeFilter) typeFilter.addEventListener("change", applyFilters);
    if (curriculumFilter) curriculumFilter.addEventListener("change", applyFilters);
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener("click", function (event) {
            event.preventDefault();
            clearFilters();
        });
    }

    observeElements();
});
