@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    .insights-hero { background: #3c096c; color: white; padding: 80px 0; text-align: center; }
    .insights-hero h1 { font-size: 2.7rem; }
    .insights-hero p { margin: 0 auto; max-width: 760px; font-size: 1.15rem; }
    .insights-content { padding: 60px 0; }
    .insights-content h2 { margin-top: 2rem; color: #1f2937; }
    .insights-content p { color: #4b5563; line-height: 1.75; }
    .read-more-btn { display: inline-flex; align-items: center; gap: .5rem; margin-top: 1.5rem; padding: .65rem 1.2rem; border-radius: 999px; border: 2px solid #3c096c; color: #3c096c; background: #fff; font-weight: 700; text-decoration: none; }
    .read-more-btn:hover { background: #3c096c; color: #fff; }
</style>
@endpush

@section('content')
<section class="insights-hero">
    <div class="container">
        <h1>Connecting Schools, Parents & Students</h1>
        <p>Skoolyst is a smart platform designed to help parents find the best schools, explore detailed information, and connect with educational institutions  and transparently.</p>
    </div>
</section>

<section class="container insights-content">
    <p>Skoolyst aims to simplify the education search process by bringing schools, parents, and students onto a single platform. From discovering nearby schools to reading reviews and accessing learning resources, everything is available in one place.</p>

    <h2>1. Find Schools </h2>
    <p>Parents can search schools based on location, categories, and preferences. Each school profile provides complete details including facilities, courses, and contact information to help make informed decisions.</p>

    <h2>2. Reviews & Ratings System</h2>
    <p>Users can share their experiences by giving reviews and ratings. This helps other parents understand the quality of education and environment before selecting a school.</p>

    <h2>3. Student Learning Support (MCQs System)</h2>
    <p>Skoolyst provides an MCQs-based learning system where students can practice questions, improve their knowledge, and prepare for exams effectively.</p>

    <h2>4. Awareness & Information Sharing</h2>
    <p>The platform promotes awareness by allowing schools to share updates, announcements, and important educational information with parents and students.</p>

    <h2>5. Upcoming Education E-Commerce</h2>
    <p>Soon, Skoolyst will introduce an e-commerce system where users can buy school and educational items , making it a complete education ecosystem.</p>

    <a class="read-more-btn" href="{{ route('about') }}">← Back to Latest Insights</a>
</section>
@endsection