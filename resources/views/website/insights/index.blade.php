@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
<style>
    .insights-hero { background: #1e3a8a; color: white; padding: 80px 0; text-align: center; }
    .insights-hero h1 { font-size: 2.8rem; margin-bottom: 1rem; }
    .insights-hero p { font-size: 1.15rem; opacity: 0.9; max-width: 750px; margin: 0 auto; }
    .insights-grid { display: grid; grid-template-columns: repeat(auto-fit,minmax(280px,1fr)); gap: 1.5rem; margin-top: 40px; }
    .insights-card { background: white; border-radius: 14px; box-shadow: 0 10px 30px rgba(0,0,0,.08); padding: 2rem; border: 1px solid #e2e8f0; transition: transform .25s ease, box-shadow .25s ease; }
    .insights-card:hover { transform: translateY(-4px); box-shadow:0 15px 30px rgba(0,0,0,.12); }
    .insights-card h3 { margin-top: 0; margin-bottom: 0.8rem; color: #1f2937; }
    .insights-card p { color: #4b5563; line-height: 1.7; }
    .insights-card a { color: #1e3a8a; font-weight: 700; text-decoration: none; }
    .insights-card a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<section class="insights-hero">
    <div class="container">
        <h1>Latest Insights</h1>
        <p>Explore actionable school marketing, technology in education, and community building strategies crafted for Skoolyst platform growth.</p>
    </div>
</section>

<section class="container py-5">
    <div class="insights-grid">
        <div class="insights-card">
            <h3>Digital Transformation in Education</h3>
            <p>Discover how schools are using automation, content management, and online experiences to deliver modern learning and operational efficiency.</p>
            <a href="{{ route('insights.digital_transformation') }}">Read More →</a>
        </div>

        <div class="insights-card">
            <h3>Building Stronger School Communities</h3>
            <p>Learn proven strategies for parent engagement, student retention, and collaborative communication across your school stakeholders.</p>
            <a href="{{ route('insights.school_community') }}">Read More →</a>
        </div>

        <div class="insights-card">
            <h3>Marketing Your School Online</h3>
            <p>Get practical tactics for digital campaigns, audience targeting, and school branding that drive admissions and local visibility.</p>
            <a href="{{ route('insights.school_marketing') }}">Read More →</a>
        </div>
    </div>
</section>
@endsection