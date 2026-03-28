@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    .insights-hero { background: #0b5b68; color: white; padding: 80px 0; text-align: center; }
    .insights-hero h1 { font-size: 2.7rem; }
    .insights-hero p { margin: 0 auto; max-width: 760px; font-size: 1.15rem; }
    .insights-content { padding: 60px 0; }
    .insights-content h2 { margin-top: 2rem; color: #1f2937; }
    .insights-content p { color: #4b5563; line-height: 1.75; }
    .read-more-btn { display: inline-flex; align-items: center; gap: .5rem; margin-top: 1.5rem; padding: .65rem 1.2rem; border-radius: 999px; border: 2px solid #0b5b68; color: #0b5b68; background: #fff; font-weight: 700; text-decoration: none; }
    .read-more-btn:hover { background: #0b5b68; color: #fff; }
</style>
@endpush

@section('content')
<section class="insights-hero">
    <div class="container">
        <h1>Building a Connected School Community</h1>
        <p>Skoolyst helps schools, parents, and students come together on one platform to share information, build trust, and create a strong educational community.</p>
    </div>
</section>

<section class="container insights-content">
    <p>A strong school community is not just about education — it’s about connection, communication, and trust. Skoolyst is designed to bridge the gap between schools, parents, and students by creating a transparent and interactive ecosystem.</p>

    <h2>1. Strong Parent-School Connection</h2>
    <p>Parents can easily explore school profiles, view facilities, and stay updated with announcements. This creates a clear and direct connection between schools and families.</p>

    <h2>2. Transparent Reviews & Feedback</h2>
    <p>Skoolyst allows parents and students to share reviews and ratings based on real experiences. This transparency helps other users make better decisions and encourages schools to improve continuously.</p>

    <h2>3. Student Engagement & Learning</h2>
    <p>With the built-in MCQs system, students can actively participate in learning, practice questions, and improve their academic performance.</p>

    <h2>4. Awareness & Information Sharing</h2>
    <p>Schools can share important updates, achievements, and announcements, helping parents stay informed and involved in their child’s education journey.</p>

    <h2>5. Growing Education Ecosystem</h2>
    <p>Skoolyst is evolving into a complete platform where educational resources and school-related products will also be available, making it a one-stop solution for the school community.</p>

    <a class="read-more-btn" href="{{ route('about') }}">← Back to Latest Insights</a>
</section>
@endsection