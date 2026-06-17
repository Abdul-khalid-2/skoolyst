let filterTimeout;

function getFilterBaseUrl() {
    const filterContainer = document.querySelector(".filter-container");
    return filterContainer ? filterContainer.dataset.baseUrl : window.location.pathname;
}

function applyFilters() {
    const search = document.getElementById("searchInput")?.value || "";
    const location = document.getElementById("locationFilter")?.value || "";
    const type = document.getElementById("typeFilter")?.value || "";
    const ownership = document.getElementById("ownershipFilter")?.value || "";
    const curriculum = document.getElementById("curriculumFilter")?.value || "";

    const params = new URLSearchParams();
    if (search) params.append("search", search);
    if (location) params.append("location", location);
    if (type) params.append("type", type);
    if (ownership) params.append("ownership", ownership);
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

    const clearFiltersBtn = document.getElementById("clearFiltersBtn");
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener("click", function (event) {
            event.preventDefault();
            clearFilters();
        });
    }

    if (window.SkoolystWebsiteSelect2) {
        window.SkoolystWebsiteSelect2.bindChanges(
            ["locationFilter", "typeFilter", "ownershipFilter", "curriculumFilter"],
            applyFilters
        );
    } else if (window.jQuery) {
        window.jQuery(
            "#locationFilter, #typeFilter, #ownershipFilter, #curriculumFilter"
        ).on("change", applyFilters);
    } else {
        ["locationFilter", "typeFilter", "ownershipFilter", "curriculumFilter"].forEach(function (id) {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener("change", applyFilters);
            }
        });
    }

    observeElements();
});
