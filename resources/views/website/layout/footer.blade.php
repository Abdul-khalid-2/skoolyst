    <footer>
        <div class="container">
            @stack('testemonial')
            <nav class="footer-nav">
                <a href="{{ route('website.home') }}">Home</a>
                <a href="{{ route('browseSchools.index') }}">Browse Schools</a>
                <a href="{{ route('website.how_it_works') }}">How It Works</a>
                <a href="{{ route('website.home') }}">Testimonials</a>
                <a href="{{ url('/about') }}">About</a>
                <a href="{{ route('website.terms') }}">Terms</a>
                <a href="{{ route('website.privacy') }}">Privacy</a>
                <a href="{{ route('website.contact') }}">Contact</a>
            </nav>

            <div class="social-icons">
                <a href="https://www.facebook.com/skoolyst/" target="_blank" rel="noopener noreferrer" aria-label="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="https://x.com/skoolyst" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <a href="https://www.linkedin.com/in/skoolyst/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn">
                    <i class="fab fa-linkedin-in"></i>
                </a>
                <a href="https://www.instagram.com/skoolyst/" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </div>

            <p class="copyright">
                &copy; 2025 SKOOLYST. All rights reserved.
            </p>
        </div>
    </footer>