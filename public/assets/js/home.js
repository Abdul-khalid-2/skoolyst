// ==================== SCHOOL DATA ==================== //
let filteredSchools = [];
let homeSearchController = null;
let homeSuggestController = null;
const HOME_SUGGEST_DEBOUNCE_MS = 300;

function getHomeSearchUrl() {
    const cfg = document.getElementById('homeSearchConfig');
    if (cfg && cfg.dataset.searchUrl) {
        return cfg.dataset.searchUrl;
    }
    const path = window.location.pathname.replace(/\/$/, '') || '';
    if (path) {
        return window.location.origin + path + '/search';
    }
    return window.location.origin + '/search';
}

function getHomeSuggestUrl() {
    const cfg = document.getElementById('homeSearchConfig');
    if (cfg && cfg.dataset.suggestUrl) {
        return cfg.dataset.suggestUrl;
    }
    return getHomeSearchUrl().replace(/\/search$/, '/search/suggest');
}

function getDirectoryFilterParams() {
    const locationFilter = document.getElementById('locationFilter') ? document.getElementById('locationFilter').value : '';
    const typeFilter = document.getElementById('typeFilter') ? document.getElementById('typeFilter').value : '';
    const curriculumFilter = document.getElementById('curriculumFilter') ? document.getElementById('curriculumFilter').value : '';
    return { location: locationFilter, type: typeFilter, curriculum: curriculumFilter };
}

// ==================== RENDER SCHOOLS (directory grid) ==================== //
function renderSchools(schoolsToRender) {
    const container = document.getElementById('schoolsContainer');
    const noResults = document.getElementById('noResults');

    if (!container) {
        return;
    }

    container.innerHTML = '';

    if (schoolsToRender.length === 0) {
        if (noResults) {
            noResults.style.display = 'block';
        }
        return;
    }

    if (noResults) {
        noResults.style.display = 'none';
    }

    schoolsToRender.forEach(function (school) {
        const stars = generateStars(school.rating);
        const bannerImage = school.banner_image
            ? '<img src="' + school.banner_image + '" alt="' + escapeAttr(school.name) + '">'
            : '<i class="fas fa-school"></i>';

        const desc = (school.description && String(school.description)) || '';
        const shortDesc = desc.length > 120 ? desc.substring(0, 120) + '...' : desc;

        const schoolCard =
            '<div class="col-lg-4 col-md-6 school-card-col">' +
            '<div class="school-card">' +
            '<div class="school-image">' +
            bannerImage +
            '</div>' +
            '<div class="school-content">' +
            '<div class="school-header">' +
            '<div>' +
            '<h3 class="school-name">' +
            escapeHtml(school.name) +
            '</h3>' +
            '<div class="school-location"><i class="fas fa-map-marker-alt"></i> <span>' +
            escapeHtml(school.location) +
            '</span></div>' +
            '</div>' +
            '<span class="school-type-badge">' +
            escapeHtml(school.type) +
            '</span>' +
            '</div>' +
            '<div class="school-rating">' +
            stars +
            '<span>' +
            school.rating +
            '</span> <small>(' +
            school.review_count +
            ' reviews)</small></div>' +
            '<p class="school-description">' +
            escapeHtml(shortDesc) +
            '</p>' +
            '<div class="school-features">' +
            '<span class="feature-tag"><i class="fas fa-book"></i> ' +
            escapeHtml(school.curriculum) +
            '</span>' +
            (school.features || [])
                .map(function (f) {
                    return '<span class="feature-tag">' + escapeHtml(f) + '</span>';
                })
                .join('') +
            '</div>' +
            '<a href="' +
            school.profile_url +
            '" class="view-profile-btn"><i class="fas fa-eye"></i> View Full Profile</a>' +
            '</div></div></div>';
        container.innerHTML += schoolCard;
    });

    setTimeout(function () {
        observeElements();
    }, 100);
}

function escapeHtml(s) {
    if (s == null) {
        return '';
    }
    const d = document.createElement('div');
    d.textContent = s;
    return d.innerHTML;
}

function escapeAttr(s) {
    return escapeHtml(s).replace(/"/g, '&quot;');
}

// ==================== GENERATE STAR RATING ==================== //
function generateStars(rating) {
    const r = Number(rating) || 0;
    const fullStars = Math.floor(r);
    const hasHalfStar = r % 1 >= 0.5;
    let stars = '';

    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }

    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }

    const emptyStars = 5 - Math.ceil(r);
    for (let j = 0; j < emptyStars; j++) {
        stars += '<i class="far fa-star"></i>';
    }

    return stars;
}

// ==================== FULL DIRECTORY SEARCH (grid) ==================== //
function applyFilters() {
    const mainSearch = document.getElementById('mainSearch');
    const searchTerm = (mainSearch && mainSearch.value) ? mainSearch.value.trim() : '';
    const f = getDirectoryFilterParams();

    if (homeSearchController) {
        homeSearchController.abort();
    }
    homeSearchController = new AbortController();

    const container = document.getElementById('schoolsContainer');
    if (container) {
        container.innerHTML =
            '<div class="col-12 text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading schools...</p></div>';
    }

    const baseUrl = getHomeSearchUrl();
    const params = new URLSearchParams();
    if (searchTerm) {
        params.set('search', searchTerm);
    }
    if (f.location) {
        params.set('location', f.location);
    }
    if (f.type) {
        params.set('type', f.type);
    }
    if (f.curriculum) {
        params.set('curriculum', f.curriculum);
    }

    const url = baseUrl + (baseUrl.indexOf('?') >= 0 ? '&' : '?') + params.toString();

    fetch(url, {
        signal: homeSearchController.signal,
        headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Request failed');
            }
            return response.json();
        })
        .then(function (data) {
            filteredSchools = data.schools || [];
            renderSchools(filteredSchools);
        })
        .catch(function (error) {
            if (error.name === 'AbortError') {
                return;
            }
            console.error('Error fetching schools:', error);
            if (container) {
                container.innerHTML =
                    '<div class="col-12 text-center text-danger"><p>Error loading schools. Please try again.</p></div>';
            }
        });
}

// ==================== CLEAR FILTERS ==================== //
function clearFilters() {
    const mainSearch = document.getElementById('mainSearch');
    if (mainSearch) {
        mainSearch.value = '';
    }
    const loc = document.getElementById('locationFilter');
    if (loc) {
        loc.value = '';
    }
    const typ = document.getElementById('typeFilter');
    if (typ) {
        typ.value = '';
    }
    const cur = document.getElementById('curriculumFilter');
    if (cur) {
        cur.value = '';
    }
    if (typeof closeHomeSuggestDropdown === 'function') {
        closeHomeSuggestDropdown();
    }
    applyFilters();
}

// ==================== LIVE TYPEAHEAD (Google-style list) ==================== //
var closeHomeSuggestDropdown = function () {};
var scheduleHomeSuggest = function () {};

function initHomeLiveSearch() {
    const suggestUrl = getHomeSuggestUrl();
    const input = document.getElementById('mainSearch');
    const clearBtn = document.getElementById('homeLiveSearchClear');
    const resultsEl = document.getElementById('homeLiveSearchResults');
    const wrap = document.getElementById('homeLiveSearchWrap');
    if (!input || !resultsEl || !wrap) {
        return;
    }

    var debounceT = null;
    var aborter = new AbortController();
    var items = [];
    var active = -1;

    function setClearVisible(visible) {
        if (clearBtn) {
            clearBtn.hidden = !visible;
        }
    }

    function closeDropdown() {
        resultsEl.innerHTML = '';
        resultsEl.hidden = true;
        input.setAttribute('aria-expanded', 'false');
        items = [];
        active = -1;
    }
    closeHomeSuggestDropdown = closeDropdown;

    function setActive(i) {
        items.forEach(function (el, j) {
            if (j === i) {
                el.classList.add('active');
            } else {
                el.classList.remove('active');
            }
            el.setAttribute('aria-selected', j === i ? 'true' : 'false');
        });
        active = i;
    }

    function buildRowHtml(row, i) {
        const u = row.profile_url;
        const title = row.title_highlight || escapeHtml(row.name);
        const snippet = row.highlight ? row.highlight : escapeHtml(row.excerpt);
        const city = row.city ? '<span class="home-live-search-badge">' + escapeHtml(row.city) + '</span>' : '';
        const st = row.type ? '<span class="home-live-search-badge">' + escapeHtml(row.type) + '</span>' : '';
        const cur =
            row.curriculum && row.curriculum !== '—'
                ? '<span class="home-live-search-badge">' + escapeHtml(row.curriculum) + '</span>'
                : '';
        return (
            '<a href="' +
            u +
            '" class="home-live-search-item" role="option" data-index="' +
            i +
            '" data-url="' +
            u +
            '">' +
            '<div class="home-live-search-item__title">' +
            title +
            '</div>' +
            '<div class="home-live-search-item__excerpt">' +
            snippet +
            '</div>' +
            '<div class="home-live-search-item__meta">' +
            city +
            st +
            cur +
            '</div></a>'
        );
    }

    function runSuggest(q) {
        if (homeSuggestController) {
            homeSuggestController.abort();
        }
        homeSuggestController = new AbortController();
        if (!q || q.length < 2) {
            closeDropdown();
            return;
        }
        resultsEl.hidden = false;
        input.setAttribute('aria-expanded', 'true');
        resultsEl.innerHTML =
            '<div class="px-3 py-4 text-center" style="color: var(--color-neutral-muted, #6c757d); font-size: 0.875rem;">' +
            '<i class="fas fa-spinner fa-spin" aria-hidden="true"></i> ' +
            '<div style="margin-top: 0.5rem">Searching…</div></div>';

        const params = new URLSearchParams();
        params.set('q', q);
        const f = getDirectoryFilterParams();
        if (f.location) {
            params.set('location', f.location);
        }
        if (f.type) {
            params.set('type', f.type);
        }
        if (f.curriculum) {
            params.set('curriculum', f.curriculum);
        }

        const url = suggestUrl + (suggestUrl.indexOf('?') >= 0 ? '&' : '?') + params.toString();
        fetch(url, {
            signal: homeSuggestController.signal,
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(function (r) {
                if (!r.ok) {
                    throw new Error('Suggest failed');
                }
                return r.json();
            })
            .then(function (data) {
                const list = data && data.results ? data.results : [];
                if (list.length === 0) {
                    resultsEl.innerHTML =
                        '<div class="px-3 py-3 text-center" style="color: var(--color-neutral-muted, #6c757d); font-size: 0.875rem;">' +
                        'No schools found. Try different keywords or use Search for the full directory.</div>';
                    items = [];
                    return;
                }
                const rowsHtml = list.map(function (row, idx) {
                    return buildRowHtml(row, idx);
                });
                rowsHtml.push(
                    '<button type="button" class="home-live-search-see-all" id="homeLiveSearchSeeAll">View all in directory</button>'
                );
                resultsEl.innerHTML = rowsHtml.join('');
                items = Array.from(resultsEl.querySelectorAll('a.home-live-search-item'));
                items.forEach(function (a, j) {
                    a.addEventListener('mouseenter', function () {
                        setActive(j);
                    });
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        window.location.href = a.getAttribute('data-url');
                    });
                });
                const seeAll = document.getElementById('homeLiveSearchSeeAll');
                if (seeAll) {
                    seeAll.addEventListener('click', function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        closeDropdown();
                        performSearch();
                    });
                }
                setActive(0);
            })
            .catch(function (e) {
                if (e.name === 'AbortError') {
                    return;
                }
                resultsEl.innerHTML =
                    '<div class="px-3 py-2" style="color: #b02a37; font-size: 0.875rem;">Search failed. Try again.</div>';
            });
    }

    function schedule() {
        clearTimeout(debounceT);
        const v = String(input.value || '').trim();
        setClearVisible(v.length > 0);
        if (v.length < 2) {
            closeDropdown();
            return;
        }
        debounceT = setTimeout(function () {
            runSuggest(v);
        }, HOME_SUGGEST_DEBOUNCE_MS);
    }
    scheduleHomeSuggest = schedule;

    input.addEventListener('input', schedule);
    input.addEventListener('focus', function () {
        if (String(input.value || '').trim().length >= 2) {
            schedule();
        }
    });
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            input.value = '';
            setClearVisible(false);
            closeDropdown();
        });
    }
    document.addEventListener('click', function (e) {
        if (!wrap.contains(e.target)) {
            closeDropdown();
        }
    });
    input.addEventListener('keydown', function (e) {
        if (resultsEl.hidden) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
            return;
        }
        if (items.length === 0) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
            }
            return;
        }
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            setActive(Math.min(items.length - 1, active + 1));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            setActive(Math.max(0, active - 1));
        } else if (e.key === 'Enter' && active >= 0 && items[active]) {
            e.preventDefault();
            window.location.href = items[active].getAttribute('data-url');
        } else if (e.key === 'Escape') {
            e.preventDefault();
            closeDropdown();
        }
    });

    ['locationFilter', 'typeFilter', 'curriculumFilter'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('change', function () {
                closeDropdown();
            });
        }
    });
}

// ==================== SCROLL + FULL SEARCH BUTTON ==================== //
function performSearch() {
    if (typeof closeHomeSuggestDropdown === 'function') {
        closeHomeSuggestDropdown();
    }
    applyFilters();
    const directory = document.getElementById('directory');
    if (directory) {
        directory.scrollIntoView({ behavior: 'smooth' });
    }
}

// ==================== SCROLL ANIMATIONS ==================== //
function observeElements() {
    const observerOptions = {
        threshold: 0.2,
        rootMargin: '0px',
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
    initHomeLiveSearch();
    const goBtn = document.getElementById('mainSearchGoBtn');
    if (goBtn) {
        goBtn.addEventListener('click', function () {
            performSearch();
        });
    }

    observeElements();

    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');

            if (targetId === '#') {
                return;
            }

            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            }
        });
    });
});