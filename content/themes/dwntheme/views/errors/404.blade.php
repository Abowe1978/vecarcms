@extends('themes.dwntheme::layouts.main')

@section('title', '404 - Page Not Found - ' . settings('site_name', 'VeCarCMS'))

@section('content')

<section class="py-15 py-lg-20">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                
                {{-- 404 Icon --}}
                <div class="f-w-40 text-primary mb-6 mx-auto opacity-25">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                </div>

                {{-- Error Message --}}
                <h1 class="display-1 fw-bold text-primary mb-3">404</h1>
                <h2 class="display-6 fw-bold mb-4">Page Not Found</h2>
                <p class="lead text-muted mb-6">
                    Oops! The page you're looking for doesn't exist. It might have been moved or deleted.
                </p>

                {{-- Search Form --}}
                <div class="mb-8">
                    <form action="{{ route('search') }}" method="GET">
                        <div class="input-group input-group-lg shadow-sm mx-auto" style="max-width: 500px;">
                            <input type="text" name="q" class="form-control" placeholder="Search for content..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="ri-search-line"></i>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Action Buttons --}}
                <div class="d-flex flex-wrap gap-3 justify-content-center mb-10">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                        <i class="ri-home-line me-2"></i>Back to Home
                    </a>
                    <a href="{{ url('/blog') }}" class="btn btn-outline-primary btn-lg">
                        <i class="ri-article-line me-2"></i>View Blog
                    </a>
                    <a href="{{ url('/contact') }}" class="btn btn-outline-primary btn-lg">
                        <i class="ri-mail-line me-2"></i>Contact Us
                    </a>
                </div>

                {{-- Helpful Links --}}
                <div class="pt-8 border-top">
                    <h6 class="fw-bold mb-4">You might be interested in:</h6>
                    <div class="d-flex flex-wrap gap-3 justify-content-center">
                        @php
                            $popularPages = \App\Models\Page::where('is_published', true)
                                ->orderBy('views', 'desc')
                                ->limit(5)
                                ->get();
                        @endphp
                        
                        @if($popularPages->count() > 0)
                            @foreach($popularPages as $page)
                                <a href="{{ url('/' . $page->slug) }}" class="badge bg-light text-dark text-decoration-none fs-6 py-2 px-3">
                                    {{ $page->title }}
                                </a>
                            @endforeach
                        @else
                            <a href="{{ url('/blog') }}" class="badge bg-light text-dark text-decoration-none fs-6 py-2 px-3">Blog</a>
                            <a href="{{ url('/about') }}" class="badge bg-light text-dark text-decoration-none fs-6 py-2 px-3">About</a>
                            <a href="{{ url('/contact') }}" class="badge bg-light text-dark text-decoration-none fs-6 py-2 px-3">Contact</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

@endsection

