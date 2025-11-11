{{-- DWN Hero Section with Image on Right --}}
<section class="dwn-full-bleed py-3 py-lg-15 bg-gradient-to-br from-primary-50 to-secondary-50 position-relative overflow-hidden">
    <div class="container position-relative z-index-10">
        <div class="row align-items-center">
            {{-- Text Content (Left) --}}
            <div class="col-12 col-lg-6 mb-5 mb-lg-0">
                <h1 class="display-3 fw-bold mb-4" data-aos="fade-up">
                    {{ $title ?? 'Welcome' }}
                </h1>
                @if(isset($description) && $description)
                <p class="lead text-muted mb-5" data-aos="fade-up" data-aos-delay="100">
                    {{ $description }}
                </p>
                @endif
                
                @if(!empty($content))
                <div class="mb-5" data-aos="fade-up" data-aos-delay="150">
                    {!! $content !!}
                </div>
                @endif
                
                @php
                    $primaryText = $button1_text ?? $button_text ?? '';
                    $primaryUrl = $button1_url ?? $button_url ?? '#';
                @endphp

                @if($primaryText || (!empty($button2_text)))
                <div class="d-flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="200">
                    @if($primaryText)
                    <a href="{{ $primaryUrl }}" class="btn btn-primary btn-lg">
                        {{ $primaryText }}
                        <i class="ri-arrow-right-line ms-2"></i>
                    </a>
                    @endif
                    
                    @if(!empty($button2_text))
                    <a href="{{ $button2_url ?? '#' }}" class="btn btn-outline-primary btn-lg">
                        {{ $button2_text }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
            
            {{-- Image (Right) --}}
            <div class="col-12 col-lg-6" data-aos="fade-left">
                @if(isset($image) && $image)
                    @php
                        $imageUrl = filter_var($image, FILTER_VALIDATE_URL) || str_starts_with($image, '/')
                            ? $image
                            : theme_asset('assets/images/' . $image);
                    @endphp
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $title ?? 'Hero' }}" 
                         class="img-fluid rounded-3 shadow-lg">
                @endif
            </div>
        </div>
    </div>
    
    {{-- SVG Background Shape --}}
    <div class="position-absolute bottom-0 start-0 end-0 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 120">
            <path fill="currentColor" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,120L1360,120C1280,120,1120,120,960,120C800,120,640,120,480,120C320,120,160,120,80,120L0,120Z"></path>
        </svg>
    </div>
</section>
