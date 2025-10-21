// Sidebar functionality
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");
    const header = document.querySelector(".header");
    const mainContent = document.querySelector(".main-content");

    if (window.innerWidth < 768) {
        // Mobile behavior
        sidebar.classList.toggle("active");
        overlay.classList.toggle("active");
    } else {
        // Desktop collapse behavior
        sidebar.classList.toggle("collapsed");
        header.classList.toggle("collapsed");
        mainContent.classList.toggle("collapsed");
    }
}

function closeSidebar() {
    const sidebar = document.getElementById("sidebar");
    const overlay = document.getElementById("sidebarOverlay");

    sidebar.classList.remove("active");
    overlay.classList.remove("active");
}
// Handle window resize
window.addEventListener('resize', function () {
    if (window.innerWidth >= 768) {
        closeSidebar();
    }
});

// Initialize page
document.addEventListener('DOMContentLoaded', function () {
    // Show dashboard by default


    // Initialize tooltips if needed
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Search functionality
function handleSearch(searchTerm) {
    console.log('Searching for:', searchTerm);
    // In a real application, this would filter the products table
}

// Add some interactive features
document.addEventListener('DOMContentLoaded', function () {
    // Add hover effects to stats cards
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach(card => {
        card.addEventListener('mouseenter', function () {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function () {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Add real-time search
    const searchInput = document.querySelector('input[placeholder="Search products..."]');
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('#products tbody tr');

            tableRows.forEach(row => {
                const productName = row.querySelector('h6').textContent.toLowerCase();
                const productSku = row.querySelector('small').textContent.toLowerCase();

                if (productName.includes(searchTerm) || productSku.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
});