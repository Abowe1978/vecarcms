@extends('themes.dwntheme::layouts.main')

@section('title', $page->title . ' - ' . settings('site_name', 'VeCarCMS'))
@section('meta_description', $page->meta_description ?? 'Get in touch with us')

@section('content')

{{-- Breadcrumbs --}}
<section class="py-4 bg-light border-bottom">
    <div class="container">
        {!! render_breadcrumbs($page) !!}
    </div>
</section>

{{-- Page Header --}}
@if($page->show_title ?? true)
<section class="py-10 py-lg-12 bg-gradient-to-br from-primary-50 to-secondary-50">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <h1 class="display-4 fw-bold mb-4">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="lead text-muted">{{ $page->excerpt }}</p>
                @else
                    <p class="lead text-muted">We'd love to hear from you. Get in touch with us!</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

{{-- Contact Info Cards --}}
<section class="py-10">
    <div class="container">
        <div class="row g-4 mb-10">
            
            {{-- Address --}}
            @if(settings('contact_address'))
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-map-pin-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Address</h5>
                    <p class="text-muted mb-0 small">{!! nl2br(e(settings('contact_address'))) !!}</p>
                </div>
            </div>
            @endif

            {{-- Email --}}
            @if(settings('contact_email'))
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-mail-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Email</h5>
                    <a href="mailto:{{ settings('contact_email') }}" class="text-muted text-decoration-none small">
                        {{ settings('contact_email') }}
                    </a>
                </div>
            </div>
            @endif

            {{-- Phone --}}
            @if(settings('contact_phone'))
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-phone-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Phone</h5>
                    <a href="tel:{{ str_replace(' ', '', settings('contact_phone')) }}" class="text-muted text-decoration-none small">
                        {{ settings('contact_phone') }}
                    </a>
                </div>
            </div>
            @endif

            {{-- Working Hours --}}
            @if(settings('contact_hours'))
            <div class="col-12 col-md-6 col-lg-3">
                <div class="card border-0 shadow-sm h-100 text-center p-4 hover-lift transition-all">
                    <div class="f-w-20 text-primary mb-3 mx-auto">
                        <i class="ri-time-line"></i>
                    </div>
                    <h5 class="fw-bold mb-2">Working Hours</h5>
                    <p class="text-muted mb-0 small">{!! nl2br(e(settings('contact_hours'))) !!}</p>
                </div>
            </div>
            @endif

        </div>

        <div class="row g-8">
            
            {{-- Contact Form --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm p-5">
                    <h3 class="fw-bold mb-4">Send us a Message</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ri-check-line me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ri-error-warning-line me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('subject') is-invalid @enderror" 
                                       id="subject" 
                                       name="subject" 
                                       value="{{ old('subject') }}"
                                       required>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('message') is-invalid @enderror" 
                                          id="message" 
                                          name="message" 
                                          rows="6" 
                                          required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(settings('recaptcha_enabled'))
                            <div class="col-12">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                                @error('g-recaptcha-response')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif

                            <div class="col-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="ri-send-plane-line me-2"></i>Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Map & Additional Info --}}
            <div class="col-12 col-lg-6">
                
                {{-- Map --}}
                @if(settings('contact_map_embed'))
                <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                    <div class="ratio ratio-16x9">
                        {!! settings('contact_map_embed') !!}
                    </div>
                </div>
                @endif

                {{-- Additional Content from Page --}}
                @if($page->content)
                <div class="card border-0 shadow-sm p-5">
                    <h3 class="fw-bold mb-4">More Information</h3>
                    <div class="prose">
                        {!! do_shortcode($page->content) !!}
                    </div>
                </div>
                @endif

                {{-- Social Links --}}
                @if(settings('social_facebook') || settings('social_twitter') || settings('social_instagram') || settings('social_linkedin'))
                <div class="card border-0 shadow-sm p-5 mt-4">
                    <h4 class="fw-bold mb-4">Follow Us</h4>
                    <div class="d-flex flex-wrap gap-3">
                        @if(settings('social_facebook'))
                            <a href="{{ settings('social_facebook') }}" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-facebook-line me-2"></i>Facebook
                            </a>
                        @endif
                        @if(settings('social_twitter'))
                            <a href="{{ settings('social_twitter') }}" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-twitter-line me-2"></i>Twitter
                            </a>
                        @endif
                        @if(settings('social_instagram'))
                            <a href="{{ settings('social_instagram') }}" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-instagram-line me-2"></i>Instagram
                            </a>
                        @endif
                        @if(settings('social_linkedin'))
                            <a href="{{ settings('social_linkedin') }}" target="_blank" class="btn btn-outline-primary">
                                <i class="ri-linkedin-line me-2"></i>LinkedIn
                            </a>
                        @endif
                    </div>
                </div>
                @endif

            </div>

        </div>
    </div>
</section>

@endsection

@if(settings('recaptcha_enabled'))
@push('scripts')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
@endif


