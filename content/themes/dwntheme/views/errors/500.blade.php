@extends('themes.dwntheme::layouts.main')

@section('title', '500 - Server Error - ' . settings('site_name', 'VeCarCMS'))

@section('content')

<section class="py-15 py-lg-20">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <div class="f-w-40 text-warning mb-6 mx-auto opacity-25">
                    <i class="ri-error-warning-line"></i>
                </div>
                <h1 class="display-1 fw-bold text-warning mb-3">500</h1>
                <h2 class="display-6 fw-bold mb-4">Server Error</h2>
                <p class="lead text-muted mb-8">
                    Something went wrong on our end. We're working to fix it.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                        <i class="ri-home-line me-2"></i>Back to Home
                    </a>
                    <a href="javascript:window.location.reload();" class="btn btn-outline-primary btn-lg">
                        <i class="ri-refresh-line me-2"></i>Try Again
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

