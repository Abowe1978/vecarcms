@extends('themes.dwntheme::layouts.main')

@section('title', '403 - Access Denied - ' . settings('site_name', 'VeCarCMS'))

@section('content')

<section class="py-15 py-lg-20">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <div class="f-w-40 text-danger mb-6 mx-auto opacity-25">
                    <i class="ri-lock-line"></i>
                </div>
                <h1 class="display-1 fw-bold text-danger mb-3">403</h1>
                <h2 class="display-6 fw-bold mb-4">Access Denied</h2>
                <p class="lead text-muted mb-8">
                    Sorry, you don't have permission to access this page.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                        <i class="ri-home-line me-2"></i>Back to Home
                    </a>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="ri-mail-line me-2"></i>Contact Us
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

