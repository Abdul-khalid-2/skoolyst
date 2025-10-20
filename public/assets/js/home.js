
// ==================== SCHOOL DATA ==================== //
const schools = [{
    name: "St. Mary's International School",
    type: "International",
    location: "Mumbai",
    curriculum: "IB",
    rating: 4.8,
    description: "A premier international school offering world-class education with state-of-the-art facilities and experienced faculty.",
    features: ["Smart Classrooms", "Sports Complex", "International Faculty", "Arts Program"]
},
{
    name: "Delhi Public School",
    type: "Private",
    location: "Delhi",
    curriculum: "CBSE",
    rating: 4.7,
    description: "One of India's most prestigious schools with a legacy of academic excellence and holistic development.",
    features: ["Science Labs", "Olympic Pool", "Robotics Club", "Music Academy"]
},
{
    name: "Green Valley High School",
    type: "Public",
    location: "Bangalore",
    curriculum: "ICSE",
    rating: 4.5,
    description: "A nurturing environment focused on academic excellence and character building with modern infrastructure.",
    features: ["Digital Library", "Outdoor Activities", "Counseling Center", "Eco Campus"]
},
{
    name: "Oxford Academy",
    type: "Private",
    location: "Pune",
    curriculum: "IGCSE",
    rating: 4.6,
    description: "Blending traditional values with modern education methods to create well-rounded global citizens.",
    features: ["Language Lab", "Theatre", "STEM Program", "Community Service"]
},
{
    name: "Sunshine Public School",
    type: "Public",
    location: "Chennai",
    curriculum: "State Board",
    rating: 4.4,
    description: "Affordable quality education with focus on inclusive learning and student-centered teaching methods.",
    features: ["Computer Lab", "Playground", "Art Studio", "Parent Partnership"]
},
{
    name: "Cambridge International",
    type: "International",
    location: "Hyderabad",
    curriculum: "IGCSE",
    rating: 4.9,
    description: "Premium international curriculum with focus on critical thinking and global perspective.",
    features: ["Innovation Lab", "Student Exchange", "College Counseling", "Leadership Programs"]
},
{
    name: "Little Angels School",
    type: "Private",
    location: "Mumbai",
    curriculum: "CBSE",
    rating: 4.3,
    description: "Child-centric approach with emphasis on creative learning and individual attention to each student.",
    features: ["Activity Rooms", "Dance Studio", "Small Class Size", "Montessori Method"]
},
{
    name: "Modern Education Academy",
    type: "Charter",
    location: "Bangalore",
    curriculum: "CBSE",
    rating: 4.5,
    description: "Innovative teaching methods combined with traditional curriculum for comprehensive development.",
    features: ["Tech Integration", "Skill Development", "Career Guidance", "Research Labs"]
},
{
    name: "Heritage International School",
    type: "International",
    location: "Delhi",
    curriculum: "IB",
    rating: 4.7,
    description: "Fostering global citizens with strong cultural roots through internationally recognized programs.",
    features: ["MUN Club", "Sports Academy", "Cultural Exchange", "Scholarship Programs"]
}
];

let filteredSchools = [...schools];

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
        const schoolCard = `
                    <div class="col-lg-4 col-md-6">
                        <div class="school-card">
                            <div class="school-image">
                                <i class="fas fa-school"></i>
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
                                </div>
                                <p class="school-description">${school.description}</p>
                                <div class="school-features">
                                    <span class="feature-tag"><i class="fas fa-book"></i> ${school.curriculum}</span>
                                    ${school.features.map(f => `<span class="feature-tag">${f}</span>`).join('')}
                                </div>
                                <a href="#" class="view-profile-btn">
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

    filteredSchools = schools.filter(school => {
        const matchesLocation = !locationFilter || school.location === locationFilter;
        const matchesType = !typeFilter || school.type === typeFilter;
        const matchesCurriculum = !curriculumFilter || school.curriculum === curriculumFilter;
        const matchesSearch = !searchTerm ||
            school.name.toLowerCase().includes(searchTerm) ||
            school.location.toLowerCase().includes(searchTerm) ||
            school.curriculum.toLowerCase().includes(searchTerm) ||
            school.description.toLowerCase().includes(searchTerm);

        return matchesLocation && matchesType && matchesCurriculum && matchesSearch;
    });

    renderSchools(filteredSchools);
}

// ==================== CLEAR FILTERS ==================== //
function clearFilters() {
    document.getElementById('locationFilter').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('curriculumFilter').value = '';
    document.getElementById('mainSearch').value = '';
    filteredSchools = [...schools];
    renderSchools(filteredSchools);
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

// Add input event for real-time search
document.getElementById('mainSearch').addEventListener('input', applyFilters);

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

// ==================== SMOOTH SCROLL ==================== //
document.addEventListener('DOMContentLoaded', function () {
    // Render initial schools
    renderSchools(schools);

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

    // Initialize observer
    observeElements();
});

