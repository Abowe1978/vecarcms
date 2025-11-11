{{-- Testimonials Shortcode --}}
<div class="testimonials-shortcode layout-{{ $layout }}">
    @if($layout === 'grid')
    <div class="row g-4">
        @foreach($testimonials as $testimonial)
        <div class="col-12 col-md-6 col-lg-{{ 12 / min(count($testimonials), 3) }}">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $testimonial['avatar'] }}" class="rounded-circle me-3" width="64" height="64" alt="{{ $testimonial['name'] }}">
                        <div>
                            <h5 class="fw-bold mb-0">{{ $testimonial['name'] }}</h5>
                            <small class="text-muted">{{ $testimonial['role'] }}</small>
                        </div>
                    </div>
                    <p class="text-muted fst-italic mb-3">"{{ $testimonial['content'] }}"</p>
                    <div class="text-warning">
                        @for($i = 0; $i < $testimonial['rating']; $i++)
                        <i class="ri-star-fill"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

