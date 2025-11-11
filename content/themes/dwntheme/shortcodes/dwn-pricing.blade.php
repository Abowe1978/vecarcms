{{-- DWN Pricing Table --}}
<section class="dwn-pricing-shortcode py-10">
    <div class="container">
        <div class="row g-4 justify-content-center">
            @foreach($plans as $index => $plan)
            <div class="col-12 col-md-6 col-lg-{{ 12 / $columns }}">
                <div class="card border-0 shadow-sm h-100 {{ isset($plan['featured']) && $plan['featured'] ? 'border-primary border-3' : '' }}">
                    @if(isset($plan['featured']) && $plan['featured'])
                    <div class="card-header bg-primary text-white text-center py-2">
                        <small class="fw-bold">MOST POPULAR</small>
                    </div>
                    @endif
                    <div class="card-body p-5 text-center">
                        <h5 class="text-uppercase text-muted mb-4">{{ $plan['name'] }}</h5>
                        <div class="mb-4">
                            <span class="display-4 fw-bold">${{ $plan['price'] }}</span>
                            <span class="text-muted">/{{ $plan['period'] }}</span>
                        </div>
                        <ul class="list-unstyled mb-5">
                            @foreach($plan['features'] as $feature)
                            <li class="mb-3">
                                <i class="ri-check-line text-success me-2"></i>{{ $feature }}
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ $plan['button_url'] }}" class="btn btn-{{ isset($plan['featured']) && $plan['featured'] ? 'primary' : 'outline-primary' }} w-100">
                            {{ $plan['button_text'] }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

