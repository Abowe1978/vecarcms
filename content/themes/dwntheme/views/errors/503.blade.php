@extends('themes.dwntheme::layouts.main')

@section('title', '503 - Maintenance Mode - ' . settings('site_name', 'VeCarCMS'))

@section('content')

<section class="py-15 py-lg-20">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <div class="f-w-40 text-info mb-6 mx-auto opacity-25">
                    <i class="ri-tools-line"></i>
                </div>
                <h1 class="display-1 fw-bold text-info mb-3">503</h1>
                <h2 class="display-6 fw-bold mb-4">We'll Be Right Back</h2>
                <p class="lead text-muted mb-8">
                    We're currently performing scheduled maintenance. We'll be back online shortly.
                </p>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="javascript:window.location.reload();" class="btn btn-primary btn-lg">
                        <i class="ri-refresh-line me-2"></i>Refresh Page
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

