@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    .insights-hero { background: #0e4c92; color: white; padding: 80px 0; text-align: center; }
    .insights-hero h1 { font-size: 2.7rem; }
    .insights-hero p { margin: 0 auto; max-width: 760px; font-size: 1.15rem; }
    .insights-content { padding: 60px 0; }
    .insights-content h2 { margin-top: 2rem; color: #1f2937; }
    .insights-content p { color: #4b5563; line-height: 1.75; }
    .read-more-btn { display: inline-flex; align-items: center; gap: .5rem; margin-top: 1.5rem; padding: .65rem 1.2rem; border-radius: 999px; border: 2px solid #0e4c92; color: #0e4c92; background: #fff; font-weight: 700; text-decoration: none; }
    .read-more-btn:hover { background: #0e4c92; color: #fff; }
</style>
@endpush

@section('content')
<section class="insights-hero">
    <div class="container">
        <h1>Digital Transformation in Education with Skoolyst</h1>
        <p>Skoolyst is helping schools, parents, and students move towards a smarter, more connected, and transparent education system.</p>
    </div>
</section>

<section class="container insights-content">
    <p>Education is rapidly shifting towards digital platforms where access to information, communication, and learning tools is essential. Skoolyst plays a key role in this transformation by bringing all educational stakeholders onto one platform.</p>

    <h2>1. Smart School Discovery</h2>
    <p>Parents can search and explore schools based on location, facilities, and categories. Detailed school profiles make it easier to compare and choose the right institution.</p>

    <h2>2. Transparent Information & Reviews</h2>
    <p>With reviews and ratings, Skoolyst builds trust by allowing real users to share their experiences. This transparency improves decision-making and encourages schools to maintain quality.</p>

    <h2>3. Digital Learning Support</h2>
    <p>The MCQs system provides students with a simple and effective way to practice and test their knowledge, supporting their academic growth beyond the classroom.</p>

    <h2>4. Easy Communication & Updates</h2>
    <p>Schools can share announcements, updates, and achievements directly on the platform, ensuring parents and students stay informed without communication gaps.</p>

    <h2>5. Future-Ready Education Platform</h2>
    <p>Skoolyst is evolving into a complete digital ecosystem, where users will also be able to access and purchase educational products, making everything related to education available in one place.</p>

    <a class="read-more-btn" href="{{ route('about') }}">← Back to Latest Insights</a>
</section>
@endsection