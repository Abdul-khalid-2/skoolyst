@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== ABOUT HERO SECTION ==================== */
    .about-hero {
        background: linear-gradient(135deg, #4361ee 0%, #38b000 50%, #ff9e00 100%);
        color: white;
        padding: 100px 0 80px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.1' fill-rule='evenodd'/%3E%3C/svg%3E");
        animation: float 20s linear infinite;
    }

    @keyframes float {
        0% {
            transform: translateY(0px) translateX(0px);
        }

        100% {
            transform: translateY(-100px) translateX(-100px);
        }
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        margin-bottom: 1.5rem;
        text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.3);
    }

    .hero-subtitle {
        font-size: 1.3rem;
        opacity: 0.95;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ==================== MISSION VISION SECTION ==================== */
    .mission-vision-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
        color: #1a1a1a;
    }

    .mission-vision-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 3rem;
        margin-bottom: 4rem;
    }

    .mission-card,
    .vision-card {
        background: white;
        padding: 3rem 2.5rem;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        text-align: center;
        transition: transform 0.3s ease;
        border: 1px solid #e0e0e0;
    }

    .mission-card:hover,
    .vision-card:hover {
        transform: translateY(-10px);
    }

    .card-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #4361ee, #38b000);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        color: white;
        font-size: 2rem;
    }

    .card-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
    }

    .card-content {
        font-size: 1.1rem;
        line-height: 1.7;
        color: #555;
    }

    /* ==================== PLATFORM FEATURES ==================== */
    .features-section {
        padding: 80px 0;
        background: white;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: white;
        padding: 2.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        border-color: #4361ee;
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #4361ee, #38b000);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        color: white;
        font-size: 1.8rem;
    }

    .feature-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .feature-description {
        font-size: 1rem;
        line-height: 1.6;
        color: #666;
    }

    /* ==================== VALUE PROPOSITION ==================== */
    .value-section {
        padding: 80px 0;
        /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
        /* color: white; */
    }

    .value-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 2rem;
    }

    .value-card {
        text-align: center;
        padding: 2rem;
    }

    .value-number {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        opacity: 0.9;
    }

    .value-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .value-description {
        font-size: 1rem;
        opacity: 0.9;
        line-height: 1.6;
    }

    /* ==================== FUTURE ROADMAP ==================== */
    .roadmap-section {
        padding: 80px 0;
        background: #f8f9fa;
    }

    .roadmap-timeline {
        max-width: 800px;
        margin: 0 auto;
    }

    .roadmap-item {
        background: white;
        padding: 2.5rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        border-left: 5px solid #4361ee;
    }

    .roadmap-phase {
        font-size: 1.1rem;
        font-weight: 700;
        color: #4361ee;
        margin-bottom: 0.5rem;
    }

    .roadmap-title {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #1a1a1a;
    }

    .roadmap-features {
        list-style: none;
        padding: 0;
    }

    .roadmap-features li {
        padding: 0.5rem 0;
        color: #555;
        position: relative;
        padding-left: 1.5rem;
    }

    .roadmap-features li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #38b000;
        font-weight: bold;
    }

    /* ==================== CTA SECTION ==================== */
    .cta-section {
        padding: 80px 0;
        background: linear-gradient(135deg, #4361ee, #38b000);
        color: white;
        text-align: center;
    }

    .cta-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .cta-subtitle {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-btn {
        padding: 1rem 2rem;
        border-radius: 50px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 2px solid white;
    }

    .cta-btn.primary {
        background: white;
        color: #4361ee;
    }

    .cta-btn.secondary {
        background: transparent;
        color: white;
    }

    .cta-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    }

    /* ==================== RESPONSIVE DESIGN ==================== */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2.5rem;
        }

        .hero-subtitle {
            font-size: 1.1rem;
        }

        .mission-vision-grid {
            grid-template-columns: 1fr;
        }

        .mission-card,
        .vision-card {
            padding: 2rem 1.5rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .value-grid {
            grid-template-columns: 1fr;
        }

        .roadmap-item {
            padding: 2rem 1.5rem;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }

        .cta-btn {
            width: 100%;
            max-width: 300px;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-md-11">

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4 p-md-5">

                    <h1 class="fw-bold mb-4 text-primary">Terms & Conditions</h1>
                    <p class="text-muted">Last Updated: {{ now()->format('F d, Y') }}</p>

                    <hr>

                    <h3 class="mt-4">1. Acceptance of Terms</h3>
                    <p>
                        By accessing or using the SKOOLYST platform, you agree to follow these Terms & Conditions.
                        If you disagree with any part, please stop using the platform immediately.
                    </p>

                    <h3 class="mt-4">2. User Accounts & Responsibilities</h3>
                    <ul>
                        <li>Users are responsible for the accuracy of their account information.</li>
                        <li>Schools must upload real, updated, and verifiable information.</li>
                        <li>Users must maintain confidentiality of their login details.</li>
                    </ul>

                    <h3 class="mt-4">3. Content Ownership & Copyright</h3>
                    <p>
                        Schools and users uploading content confirm they have full rights or permission to upload it.
                        SKOOLYST is not responsible for copyright violations committed by users.
                        Any reported infringement will be removed promptly.
                    </p>

                    <h3 class="mt-4">4. Prohibited Activities</h3>
                    <ul>
                        <li>Uploading copyrighted content without permission</li>
                        <li>Fake or misleading information</li>
                        <li>Impersonation of individuals or institutions</li>
                        <li>Harmful or illegal content</li>
                    </ul>

                    <h3 class="mt-4">5. Reviews & Ratings</h3>
                    <p>
                        Reviews must be genuine and respectful. Fake or harmful reviews will be removed.
                    </p>

                    <h3 class="mt-4">6. Platform Updates</h3>
                    <p>
                        We may update or modify the platform at any time. Continued use means you accept the updated terms.
                    </p>

                    <h3 class="mt-4">7. Limitation of Liability</h3>
                    <p>
                        SKOOLYST displays content as provided by users and schools. We are not responsible for:
                    </p>
                    <ul>
                        <li>Incorrect information uploaded by schools</li>
                        <li>Fake reviews</li>
                        <li>Copyright infringement by users</li>
                        <li>Disputes between users and schools</li>
                    </ul>

                    <h3 class="mt-4">8. Removal of Content</h3>
                    <p>
                        We may remove content that violates policies, is copyrighted, misleading, or harmful.
                    </p>

                    <h3 class="mt-4">9. Termination</h3>
                    <p>
                        SKOOLYST may suspend or terminate accounts for policy violations.
                    </p>

                    <h3 class="mt-4">10. Contact</h3>
                    <p>
                        For complaints or inquiries: <br>
                        <strong>Email:</strong> abdulkhalidmasood66@gmail.com
                    </p>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection