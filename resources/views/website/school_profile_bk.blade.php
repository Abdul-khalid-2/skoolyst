<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenwood International School - School Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@700;800&display=swap" rel="stylesheet">
    <style>
        /* === GLOBAL RESET & TYPOGRAPHY === */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #2563eb;
            --secondary: #16a34a;
            --accent: #f59e0b;
            --light: #f8fafc;
            --dark: #0f172a;
            --gray: #64748b;
            --light-gray: #e2e8f0;
            --white: #ffffff;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --radius-sm: 6px;
            --radius-md: 12px;
            --radius-lg: 16px;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--dark);
            background-color: #fafafa;
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1,
        h2,
        h3,
        h4,
        h5 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* === NAVIGATION === */
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow-sm);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav .nav-link {
            color: var(--dark);
            font-weight: 600;
            margin: 0 0.8rem;
            transition: color 0.2s ease;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary);
        }

        .auth-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn-login {
            padding: 0.6rem 1.5rem;
            border: 2px solid var(--primary);
            color: var(--primary);
            background: transparent;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: var(--primary);
            color: var(--white);
        }

        .btn-register {
            padding: 0.6rem 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: var(--white);
            border: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }

        /* === HERO SECTION === */
        .school-header {
            padding: 4rem 0;
            color: var(--white);
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 50%, var(--accent) 100%);
        }

        .school-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.08' fill-rule='evenodd'/%3E%3C/svg%3E");
            animation: float 20s linear infinite;
        }

        @keyframes float {
            0% {
                transform: translateY(0) translateX(0);
            }

            100% {
                transform: translateY(-100px) translateX(-100px);
            }
        }

        .school-logo-placeholder {
            width: 130px;
            height: 130px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 2.2rem;
            border: 3px solid rgba(255, 255, 255, 0.9);
            box-shadow: var(--shadow-lg);
        }

        .school-name {
            font-size: 2.8rem;
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--white);
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
        }

        .school-meta {
            display: flex;
            gap: 1.8rem;
            margin-bottom: 1.2rem;
            flex-wrap: wrap;
        }

        .school-location,
        .school-type {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: rgba(255, 255, 255, 0.95);
            font-weight: 500;
        }

        .school-rating {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .rating-stars i {
            color: #fbbf24;
        }

        .rating-value {
            font-weight: 700;
            font-size: 1.1rem;
        }

        .review-count {
            opacity: 0.9;
        }

        /* === SCHOOL NAVIGATION === */
        .school-navigation {
            background: var(--white);
            border-bottom: 1px solid var(--light-gray);
            position: sticky;
            top: 0;
            z-index: 99;
            box-shadow: var(--shadow-sm);
        }

        .nav-links {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-x: auto;
            scrollbar-width: none;
        }

        .nav-links::-webkit-scrollbar {
            display: none;
        }

        .nav-link {
            padding: 1.1rem 1.4rem;
            color: var(--gray);
            font-weight: 600;
            white-space: nowrap;
            border-bottom: 3px solid transparent;
            transition: all 0.25s ease;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary);
            border-bottom-color: var(--primary);
        }

        /* === MAIN CONTENT LAYOUT === */
        .school-main-content {
            padding: 2.5rem 0;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
        }

        .content-section {
            margin-bottom: 3rem;
            scroll-margin-top: 120px;
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .section-title {
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            color: var(--dark);
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 4px;
            background: var(--primary);
            border-radius: 2px;
        }

        .section-content p {
            color: var(--gray);
            margin-bottom: 1.2rem;
            font-size: 1rem;
        }

        /* === QUICK FACTS === */
        .quick-facts h3 {
            margin: 1.5rem 0 1rem;
            font-size: 1.3rem;
            color: var(--dark);
        }

        .facts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .fact-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease;
        }

        .fact-item:hover {
            transform: translateY(-2px);
        }

        .fact-item i {
            font-size: 1.4rem;
            color: var(--primary);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 50%;
        }

        /* === GALLERY === */
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 1.2rem;
        }

        .gallery-item {
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-md);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
        }

        .gallery-item img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
        }

        .image-caption {
            padding: 0.75rem;
            background: var(--white);
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--dark);
        }

        /* === CURRICULUM === */
        .curriculum-list {
            display: flex;
            flex-direction: column;
            gap: 1.2rem;
        }

        .curriculum-item {
            padding: 1.4rem;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border-left: 4px solid var(--primary);
            transition: box-shadow 0.2s ease;
        }

        .curriculum-item:hover {
            box-shadow: var(--shadow-md);
        }

        .curriculum-item h4 {
            margin-bottom: 0.6rem;
            color: var(--dark);
        }

        .curriculum-item p {
            margin: 0;
            color: var(--gray);
        }

        /* === FACILITIES === */
        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1.2rem;
        }

        .facility-item {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.2rem;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: all 0.25s ease;
        }

        .facility-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        .facility-item i {
            font-size: 1.3rem;
            color: var(--primary);
            margin-top: 0.2rem;
        }

        .facility-description {
            font-size: 0.9rem;
            color: var(--gray);
            margin-top: 0.25rem;
        }

        /* === REVIEWS === */
        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .review-item {
            padding: 1.5rem;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .reviewer-name {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.25rem;
        }

        .review-rating i {
            color: #fbbf24;
            font-size: 0.95rem;
        }

        .review-date {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .review-content {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* === EVENTS === */
        .events-list {
            display: flex;
            flex-direction: column;
            gap: 1.3rem;
        }

        .event-item {
            display: flex;
            gap: 1.2rem;
            padding: 1.3rem;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            transition: box-shadow 0.2s ease;
        }

        .event-item:hover {
            box-shadow: var(--shadow-md);
        }

        .event-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--primary);
            color: var(--white);
            padding: 0.8rem 0.6rem;
            border-radius: var(--radius-md);
            min-width: 65px;
            font-weight: 700;
        }

        .event-day {
            font-size: 1.4rem;
            line-height: 1;
        }

        .event-month {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .event-title {
            font-weight: 700;
            margin-bottom: 0.4rem;
            color: var(--dark);
        }

        .event-description {
            color: var(--gray);
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
        }

        .event-location {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.9rem;
            color: var(--gray);
        }

        /* === BRANCHES === */
        .branches-list {
            display: flex;
            flex-direction: column;
            gap: 1.4rem;
        }

        .branch-item {
            padding: 1.5rem;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .branch-name {
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: var(--dark);
        }

        .branch-details {
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
        }

        .branch-address,
        .branch-phone,
        .branch-head {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            color: var(--gray);
            font-size: 0.95rem;
        }

        .branch-address i,
        .branch-phone i,
        .branch-head i {
            color: var(--primary);
            margin-top: 0.2rem;
        }

        /* === SIDEBAR === */
        .sidebar-section {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: var(--white);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }

        .sidebar-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.2rem;
            color: var(--dark);
            position: relative;
            padding-bottom: 0.5rem;
        }

        .sidebar-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 3px;
            background: var(--secondary);
            border-radius: 2px;
        }

        /* === CONTACT INFO === */
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.8rem;
        }

        .contact-item i {
            color: var(--primary);
            font-size: 1rem;
            margin-top: 0.2rem;
        }

        .contact-item a {
            color: var(--gray);
            transition: color 0.2s ease;
        }

        .contact-item a:hover {
            color: var(--primary);
        }

        /* === FEE STRUCTURE === */
        .fee-info {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .fee-item {
            display: flex;
            justify-content: space-between;
            padding: 0.9rem;
            background: rgba(37, 99, 235, 0.03);
            border-radius: var(--radius-sm);
        }

        .fee-label {
            font-weight: 600;
            color: var(--dark);
        }

        .fee-amount {
            font-weight: 700;
            color: var(--secondary);
        }

        /* === ACTION BUTTONS === */
        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.9rem;
        }

        .action-btn {
            padding: 0.85rem 1rem;
            border: none;
            border-radius: var(--radius-sm);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            font-size: 0.95rem;
        }

        .action-btn.primary {
            background: var(--primary);
            color: var(--white);
        }

        .action-btn.primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .action-btn.secondary {
            background: var(--gray);
            color: var(--white);
        }

        .action-btn.secondary:hover {
            background: #475569;
            transform: translateY(-2px);
        }

        .action-btn.tertiary {
            background: transparent;
            color: var(--primary);
            border: 1px solid var(--primary);
        }

        .action-btn.tertiary:hover {
            background: rgba(37, 99, 235, 0.05);
            transform: translateY(-2px);
        }

        /* === SOCIAL LINKS === */
        .social-links {
            display: flex;
            gap: 0.8rem;
            justify-content: center;
            margin-top: 0.8rem;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            background: var(--light-gray);
            border-radius: 50%;
            color: var(--gray);
            transition: all 0.25s ease;
        }

        .social-link:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
        }

        /* === RESPONSIVE DESIGN === */
        @media (max-width: 991px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .auth-buttons {
                flex-direction: column;
                width: 100%;
                margin-top: 1rem;
            }

            .btn-login,
            .btn-register {
                width: 100%;
                text-align: center;
            }
        }

        @media (max-width: 768px) {
            .school-header {
                padding: 2.5rem 0;
            }

            .school-basic-info {
                flex-direction: column;
                text-align: center;
                gap: 1.5rem;
            }

            .school-name {
                font-size: 2.2rem;
            }

            .school-meta {
                justify-content: center;
            }

            .school-logo-placeholder {
                width: 110px;
                height: 110px;
            }

            .facts-grid,
            .gallery-grid,
            .facilities-grid {
                grid-template-columns: 1fr;
            }

            .review-header {
                flex-direction: column;
                gap: 0.6rem;
            }

            .event-item {
                flex-direction: column;
                gap: 1rem;
            }

            .event-date {
                align-self: flex-start;
                min-width: auto;
                padding: 0.6rem 0.8rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .school-header::before,
            .gallery-item,
            .facility-item,
            .review-item,
            .event-item,
            .branch-item,
            .action-btn {
                animation: none;
                transition: none;
                transform: none;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="#">EduFinder</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Schools</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Compare</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                </ul>
                <div class="auth-buttons">
                    <a href="#" class="btn-login">Login</a>
                    <a href="#" class="btn-register">Register</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- School Header Section -->
    <section class="school-header">
        <div class="container">
            <div class="school-basic-info">
                <div class="school-logo">
                    <div class="school-logo-placeholder">
                        <i class="fas fa-school"></i>
                    </div>
                </div>
                <div class="school-info">
                    <h1 class="school-name">Greenwood International School</h1>
                    <div class="school-meta">
                        <span class="school-location">
                            <i class="fas fa-map-marker-alt"></i>
                            Mumbai, Maharashtra
                        </span>
                        <span class="school-type">
                            <i class="fas fa-tag"></i>
                            International School
                        </span>
                    </div>
                    <div class="school-rating">
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <span class="rating-value">4.5</span>
                        <span class="review-count">(128 reviews)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- School Navigation -->
    <nav class="school-navigation">
        <div class="container">
            <ul class="nav-links">
                <li><a href="#overview" class="nav-link active" data-tab="overview">Overview</a></li>
                <li><a href="#gallery" class="nav-link" data-tab="gallery">Gallery</a></li>
                <li><a href="#curriculum" class="nav-link" data-tab="curriculum">Curriculum</a></li>
                <li><a href="#facilities" class="nav-link" data-tab="facilities">Facilities</a></li>
                <li><a href="#reviews" class="nav-link" data-tab="reviews">Reviews</a></li>
                <li><a href="#events" class="nav-link" data-tab="events">Events</a></li>
                <li><a href="#branches" class="nav-link" data-tab="branches">Branches</a></li>
                <li><a href="#contact" class="nav-link" data-tab="contact">Contact</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="school-main-content">
        <div class="container">
            <div class="content-grid">
                <!-- Left Column - Main Content -->
                <div class="main-column">
                    <!-- Overview Section -->
                    <section id="overview" class="content-section active">
                        <h2 class="section-title">About Our School</h2>
                        <div class="section-content">
                            <p class="school-description">Greenwood International School is a premier educational institution committed to providing holistic education that nurtures young minds and prepares them for global challenges. Established in 1995, our school has consistently maintained high academic standards while fostering creativity, critical thinking, and character development.</p>
                            <div class="quick-facts">
                                <h3>Quick Facts</h3>
                                <div class="facts-grid">
                                    <div class="fact-item">
                                        <i class="fas fa-calendar"></i>
                                        <span>Established: 1995</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-users"></i>
                                        <span>Student Strength: 2,500</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <span>Faculty: 150 teachers</span>
                                    </div>
                                    <div class="fact-item">
                                        <i class="fas fa-building"></i>
                                        <span>Campus Size: 10 acres</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Gallery Section -->
                    <section id="gallery" class="content-section">
                        <h2 class="section-title">School Gallery</h2>
                        <div class="section-content">
                            <div class="gallery-grid">
                                <div class="gallery-item">
                                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="School Campus">
                                    <div class="image-caption">Main Campus</div>
                                </div>
                                <div class="gallery-item">
                                    <img src="https://images.unsplash.com/photo-1588072432836-e10032774350?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Science Lab">
                                    <div class="image-caption">Science Laboratory</div>
                                </div>
                                <div class="gallery-item">
                                    <img src="https://images.unsplash.com/photo-1541336032412-2048a678540d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Library">
                                    <div class="image-caption">Library</div>
                                </div>
                                <div class="gallery-item">
                                    <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Sports Ground">
                                    <div class="image-caption">Sports Ground</div>
                                </div>
                                <div class="gallery-item">
                                    <img src="https://images.unsplash.com/photo-1519452635265-7b1fbfd1e4e0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Art Room">
                                    <div class="image-caption">Art Room</div>
                                </div>
                                <div class="gallery-item">
                                    <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f8f5be?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Auditorium">
                                    <div class="image-caption">Auditorium</div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Curriculum Section -->
                    <section id="curriculum" class="content-section">
                        <h2 class="section-title">Curriculum & Programs</h2>
                        <div class="section-content">
                            <div class="curriculum-list">
                                <div class="curriculum-item">
                                    <h4>CBSE Curriculum</h4>
                                    <p>We follow the Central Board of Secondary Education (CBSE) curriculum with a focus on holistic development and academic excellence.</p>
                                </div>
                                <div class="curriculum-item">
                                    <h4>International Baccalaureate (IB)</h4>
                                    <p>Our IB program offers a globally recognized curriculum that emphasizes critical thinking, intercultural understanding, and respect.</p>
                                </div>
                                <div class="curriculum-item">
                                    <h4>STEM Program</h4>
                                    <p>Specialized Science, Technology, Engineering, and Mathematics program with advanced labs and project-based learning.</p>
                                </div>
                                <div class="curriculum-item">
                                    <h4>Arts & Humanities</h4>
                                    <p>Comprehensive arts program including visual arts, music, theater, and dance with professional instructors and facilities.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Facilities Section -->
                    <section id="facilities" class="content-section">
                        <h2 class="section-title">Facilities & Features</h2>
                        <div class="section-content">
                            <div class="facilities-grid">
                                <div class="facility-item">
                                    <i class="fas fa-wifi"></i>
                                    <div>
                                        <span>Wi-Fi Campus</span>
                                        <p class="facility-description">High-speed internet across the campus</p>
                                    </div>
                                </div>
                                <div class="facility-item">
                                    <i class="fas fa-flask"></i>
                                    <div>
                                        <span>Science Labs</span>
                                        <p class="facility-description">Modern laboratories for Physics, Chemistry, Biology</p>
                                    </div>
                                </div>
                                <div class="facility-item">
                                    <i class="fas fa-book"></i>
                                    <div>
                                        <span>Digital Library</span>
                                        <p class="facility-description">20,000+ books and digital resources</p>
                                    </div>
                                </div>
                                <div class="facility-item">
                                    <i class="fas fa-laptop"></i>
                                    <div>
                                        <span>Computer Labs</span>
                                        <p class="facility-description">State-of-the-art computer labs with latest technology</p>
                                    </div>
                                </div>
                                <div class="facility-item">
                                    <i class="fas fa-swimming-pool"></i>
                                    <div>
                                        <span>Swimming Pool</span>
                                        <p class="facility-description">Olympic-size swimming pool with certified trainers</p>
                                    </div>
                                </div>
                                <div class="facility-item">
                                    <i class="fas fa-basketball-ball"></i>
                                    <div>
                                        <span>Sports Complex</span>
                                        <p class="facility-description">Indoor and outdoor sports facilities</p>
                                    </div>
                                </div>
                                <div class="facility-item">
                                    <i class="fas fa-utensils"></i>
                                    <div>
                                        <span>Cafeteria</span>
                                        <p class="facility-description">Hygienic and nutritious meal options</p>
                                    </div>
                                </div>
                                <div class="facility-item">
                                    <i class="fas fa-bus"></i>
                                    <div>
                                        <span>Transportation</span>
                                        <p class="facility-description">Safe and reliable bus service</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Reviews Section -->
                    <section id="reviews" class="content-section">
                        <h2 class="section-title">Parent & Student Reviews</h2>
                        <div class="section-content">
                            <div class="reviews-list">
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <span class="reviewer-name">Rajesh Sharma</span>
                                            <div class="review-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                            </div>
                                        </div>
                                        <span class="review-date">June 15, 2023</span>
                                    </div>
                                    <p class="review-content">Greenwood has been an excellent choice for our daughter. The teachers are dedicated, the facilities are top-notch, and the overall environment is very nurturing. We're particularly impressed with their focus on holistic development.</p>
                                </div>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <span class="reviewer-name">Priya Patel</span>
                                            <div class="review-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="far fa-star"></i>
                                            </div>
                                        </div>
                                        <span class="review-date">May 22, 2023</span>
                                    </div>
                                    <p class="review-content">The school provides excellent academic programs and extracurricular activities. My son has flourished here both academically and personally. The communication from the school could be improved, but overall we're very satisfied.</p>
                                </div>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <span class="reviewer-name">Anita Desai</span>
                                            <div class="review-rating">
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star"></i>
                                                <i class="fas fa-star-half-alt"></i>
                                            </div>
                                        </div>
                                        <span class="review-date">April 10, 2023</span>
                                    </div>
                                    <p class="review-content">As an alumni and now a parent, I can confidently say that Greenwood maintains its high standards. The IB program is exceptional and prepares students well for international universities. The campus infrastructure has improved significantly over the years.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Events Section -->
                    <section id="events" class="content-section">
                        <h2 class="section-title">Upcoming Events</h2>
                        <div class="section-content">
                            <div class="events-list">
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="event-day">15</span>
                                        <span class="event-month">Sep</span>
                                    </div>
                                    <div class="event-details">
                                        <h4 class="event-title">Annual Sports Day</h4>
                                        <p class="event-description">Join us for our annual sports competition with various track and field events.</p>
                                        <div class="event-meta">
                                            <span class="event-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                School Sports Ground
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="event-day">25</span>
                                        <span class="event-month">Sep</span>
                                    </div>
                                    <div class="event-details">
                                        <h4 class="event-title">Science Exhibition</h4>
                                        <p class="event-description">Students showcase innovative science projects and experiments.</p>
                                        <div class="event-meta">
                                            <span class="event-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                Science Block
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="event-day">05</span>
                                        <span class="event-month">Oct</span>
                                    </div>
                                    <div class="event-details">
                                        <h4 class="event-title">Parent-Teacher Meeting</h4>
                                        <p class="event-description">Quarterly meeting to discuss student progress and development.</p>
                                        <div class="event-meta">
                                            <span class="event-location">
                                                <i class="fas fa-map-marker-alt"></i>
                                                Main Auditorium
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Branches Section -->
                    <section id="branches" class="content-section">
                        <h2 class="section-title">Our Branches</h2>
                        <div class="section-content">
                            <div class="branches-list">
                                <div class="branch-item">
                                    <h4 class="branch-name">Greenwood International School - Main Campus</h4>
                                    <div class="branch-details">
                                        <p class="branch-address"><i class="fas fa-map-marker-alt"></i> 123 Education Lane, Bandra West, Mumbai, Maharashtra 400050</p>
                                        <p class="branch-phone"><i class="fas fa-phone"></i> +91 22 2645 7890</p>
                                        <p class="branch-head"><i class="fas fa-user-tie"></i> Dr. Sanjay Mehta - Principal</p>
                                    </div>
                                </div>
                                <div class="branch-item">
                                    <h4 class="branch-name">Greenwood International School - East Campus</h4>
                                    <div class="branch-details">
                                        <p class="branch-address"><i class="fas fa-map-marker-alt"></i> 456 Knowledge Road, Powai, Mumbai, Maharashtra 400076</p>
                                        <p class="branch-phone"><i class="fas fa-phone"></i> +91 22 2854 3210</p>
                                        <p class="branch-head"><i class="fas fa-user-tie"></i> Ms. Anjali Kapoor - Head of Campus</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="sidebar-column">
                    <!-- Contact Information -->
                    <section id="contact-sidebar" class="sidebar-section">
                        <h3 class="sidebar-title">Contact Information</h3>
                        <div class="contact-info">
                            <div class="contact-item">
                                <i class="fas fa-phone"></i>
                                <span>+91 22 2645 7890</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-envelope"></i>
                                <span>admissions@greenwood.edu.in</span>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-globe"></i>
                                <a href="https://www.greenwood.edu.in" target="_blank">Visit Website</a>
                            </div>
                            <div class="contact-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Education Lane, Bandra West, Mumbai, Maharashtra 400050</span>
                            </div>
                        </div>
                    </section>

                    <!-- Fee Structure -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Fee Structure</h3>
                        <div class="fee-info">
                            <div class="fee-item">
                                <span class="fee-label">Regular Fees:</span>
                                <span class="fee-amount">₹85,000/year</span>
                            </div>
                            <div class="fee-item">
                                <span class="fee-label">Discounted Fees:</span>
                                <span class="fee-amount">₹75,000/year</span>
                            </div>
                            <div class="fee-item">
                                <span class="fee-label">Admission Fees:</span>
                                <span class="fee-amount">₹25,000</span>
                            </div>
                        </div>
                    </section>

                    <!-- Quick Actions -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Quick Actions</h3>
                        <div class="action-buttons">
                            <button class="action-btn primary">
                                <i class="fas fa-download"></i>
                                Download Brochure
                            </button>
                            <button class="action-btn secondary">
                                <i class="fas fa-calendar-check"></i>
                                Schedule Visit
                            </button>
                            <button class="action-btn tertiary">
                                <i class="fas fa-edit"></i>
                                Write Review
                            </button>
                        </div>
                    </section>

                    <!-- Social Media -->
                    <section class="sidebar-section">
                        <h3 class="sidebar-title">Follow Us</h3>
                        <div class="social-links">
                            <a href="#" class="social-link">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="social-link">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-link[data-tab]');
            const contentSections = document.querySelectorAll('.content-section');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Update active nav link
                    navLinks.forEach(nav => nav.classList.remove('active'));
                    this.classList.add('active');

                    // Show only the selected content section
                    const targetTab = this.getAttribute('data-tab');
                    contentSections.forEach(section => {
                        if (section.id === targetTab) {
                            section.classList.add('active');
                        } else {
                            section.classList.remove('active');
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>