{{-- Hero Section Shortcode --}}
<section class="hero-shortcode py-5 py-lg-10 bg-{{ $background }} text-white position-relative overflow-hidden">
    <div class="container position-relative z-index-10">
        <div class="row align-items-center">
            <div class="col-12 {{ $image ? 'col-lg-6' : 'col-lg-8 mx-auto text-center' }} mb-5 mb-lg-0">
                <h1 class="display-3 fw-bold mb-4">{{ $title }}</h1>
                
                @if($subtitle)
                <p class="lead mb-5">{{ $subtitle }}</p>
                @endif
                
                @if(!empty($content))
                <div class="mb-5">{!! $content !!}</div>
                @endif
                
                @if($button_text && $button_url)
                <a href="{{ $button_url }}" class="btn btn-light btn-lg">
                    {{ $button_text }}
                    <i class="ri-arrow-right-line ms-2"></i>
                </a>
                @endif
            </div>
            
            @if($image)
            <div class="col-12 col-lg-6">
                <img src="{{ $image }}" alt="{{ $title }}" class="img-fluid rounded-3 shadow-lg">
            </div>
            @endif
        </div>
    </div>
</section>

