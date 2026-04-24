<nav class="school-navigation">
    <div class="container">
        <div class="nav-links-wrapper">
            <button class="nav-scroll-btn nav-scroll-left" type="button" aria-label="Scroll left">
                <i class="fas fa-chevron-left"></i>
            </button>
            <ul class="nav-links" id="schoolNavLinks">
                <li><a href="#overview" class="nav-link active" data-tab="overview">Overview</a></li>
                <li><a href="#gallery" class="nav-link" data-tab="gallery">Gallery</a></li>
                <li><a href="#curriculum" class="nav-link" data-tab="curriculum">Curriculum</a></li>
                <li><a href="#facilities" class="nav-link" data-tab="facilities">Facilities</a></li>
                <li><a href="#mission-vision" class="nav-link" data-tab="mission-vision">Mission &amp; Vision</a></li>
                <li><a href="#reviews" class="nav-link" data-tab="reviews">Reviews</a></li>
                <li>
                    <a href="#announcements" class="nav-link" data-tab="announcements">Announcement
                        @if($school->hasNewAnnouncements())
                            <i class="fas fa-bullhorn school-nav-announcement-icon" aria-hidden="true"></i>
                        @endif
                    </a>
                </li>
                <li><a href="#branches" class="nav-link" data-tab="branches">Branches</a></li>
                <li><a href="#contact" class="nav-link" data-tab="contact">Contact</a></li>
            </ul>
            <button class="nav-scroll-btn nav-scroll-right" type="button" aria-label="Scroll right">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</nav>
