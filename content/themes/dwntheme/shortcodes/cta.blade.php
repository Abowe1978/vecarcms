{{-- CTA (Call to Action) Section --}}
<section class="dwn-full-bleed py-10 py-lg-15 bg-{{ $background ?? 'primary' }} text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up">
                    {{ $title ?? 'Ready to Get Started?' }}
                </h2>
                
                @if(isset($description) && $description)
                <p class="lead mb-5 opacity-90" data-aos="fade-up" data-aos-delay="100">
                    {{ $description }}
                </p>
                @endif
                
                @if(!empty($content))
                <div class="mb-5" data-aos="fade-up" data-aos-delay="150">
                    {!! $content !!}
                </div>
                @endif
                
                @if((isset($button1_text) && $button1_text) || (isset($button2_text) && $button2_text))
                <div class="d-flex flex-wrap gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="200">
                    @if(isset($button1_text) && $button1_text)
                    <a href="{{ $button1_url ?? '#' }}" class="btn btn-light btn-lg">
                        {{ $button1_text }}
                        <i class="ri-arrow-right-line ms-2"></i>
                    </a>
                    @endif
                    
                    @if(isset($button2_text) && $button2_text)
                    <a href="{{ $button2_url ?? '#' }}" class="btn btn-outline-light btn-lg">
                        {{ $button2_text }}
                    </a>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
