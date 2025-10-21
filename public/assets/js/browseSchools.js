// ==================== SCHOOL DATA ==================== //
let filteredSchools = [];

// ==================== RENDER SCHOOLS ==================== //
function renderSchools(schoolsToRender) {
    const container = document.getElementById('schoolsContainer');
    const noResults = document.getElementById('noResults');

    container.innerHTML = '';

    if (schoolsToRender.length === 0) {
        noResults.style.display = 'block';
        return;
    }

    noResults.style.display = 'none';

    schoolsToRender.forEach(school => {
        const stars = generateStars(school.rating);
        const bannerImage = school.banner_image ?
            `<img src="${school.banner_image}" alt="${school.name}" style="width: 100%; height: 200px; object-fit: cover;">` :
            `<i class="fas fa-school"></i>`;

        const schoolCard = `
            <div class="col-lg-4 col-md-6 school-card-col">
                <div class="school-card">
                    <div class="school-image">
                        ${bannerImage}
                    </div>
                    <div class="school-content">
                        <div class="school-header">
                            <div>
                                <h3 class="school-name">${school.name}</h3>
                                <div class="school-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>${school.location}</span>
                                </div>
                            </div>
                            <span class="school-type-badge">${school.type}</span>
                        </div>
                        <div class="school-rating">
                            ${stars}
                            <span style="color: #666; margin-left: 0.5rem;">${school.rating}</span>
                            <small style="color: #888; margin-left: 0.5rem;">(${school.review_count} reviews)</small>
                        </div>
                        <p class="school-description">${school.description}</p>
                        <div class="school-features">
                            <span class="feature-tag"><i class="fas fa-book"></i> ${school.curriculum}</span>
                            ${school.features.map(f => `<span class="feature-tag">${f}</span>`).join('')}
                        </div>
                        <a href="${school.profile_url}" class="view-profile-btn">
                            <i class="fas fa-eye"></i> View Full Profile
                        </a>
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += schoolCard;
    });

    // Trigger animation
    setTimeout(() => {
        observeElements();
    }, 100);
}

// ==================== GENERATE STAR RATING ==================== //
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    let stars = '';

    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }

    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }

    const emptyStars = 5 - Math.ceil(rating);
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }

    return stars;
}

// ==================== APPLY FILTERS ==================== //
function applyFilters() {
    const locationFilter = document.getElementById('locationFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const curriculumFilter = document.getElementById('curriculumFilter').value;
    const searchTerm = document.getElementById('mainSearch').value.toLowerCase();

    // Show loading state
    const container = document.getElementById('schoolsContainer');
    container.innerHTML = '<div class="col-12 text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading schools...</p></div>';

    // Make AJAX request to search endpoint
    fetch('/search?' + new URLSearchParams({
        search: searchTerm,
        location: locationFilter,
        type: typeFilter,
        curriculum: curriculumFilter
    }))
        .then(response => response.json())
        .then(data => {
            filteredSchools = data.schools;
            renderSchools(filteredSchools);
        })
        .catch(error => {
            console.error('Error fetching schools:', error);
            container.innerHTML = '<div class="col-12 text-center text-danger"><p>Error loading schools. Please try again.</p></div>';
        });
}

// ==================== CLEAR FILTERS ==================== //
function clearFilters() {
    document.getElementById('locationFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('curriculumFilter').value = '';
    document.getElementById('mainSearch').value = '';
    applyFilters(); // This will fetch all schools
}

// ==================== PERFORM SEARCH ==================== //
function performSearch() {
    applyFilters();
    document.getElementById('directory').scrollIntoView({
        behavior: 'smooth'
    });
}

// Add Enter key support for search
document.getElementById('mainSearch').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        performSearch();
    }
});

// Add input event for real-time search with debounce
let searchTimeout;
document.getElementById('mainSearch').addEventListener('input', function () {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(applyFilters, 500); // Wait 500ms after user stops typing
});

// ==================== SCROLL ANIMATIONS ==================== //
function observeElements() {
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px'
    };

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.school-card, .step-card, .testimonial-card').forEach(function (el) {
        observer.observe(el);
    });
}

// ==================== INITIALIZE ==================== //
document.addEventListener('DOMContentLoaded', function () {
    // Initialize observer for initial schools
    observeElements();

    // Smooth scroll for all anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');

            if (targetId === '#') return;

            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});