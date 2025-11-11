{{-- DWN Reviews Section --}}
<section class="dwn-full-bleed dwn-reviews pb-10">
    <div class="container text-center">
        <p class="mb-3 small fw-bolder tracking-wider text-uppercase text-primary">{{ $subtitle ?? 'Our Feedback' }}</p>
        <h4 class="fs-1 fw-bold mb-6 mx-auto" style="max-width: 640px;">
            {{ $title ?? 'What our customers are saying about us' }}
        </h4>
        <div class="mt-3 d-flex justify-content-center flex-column flex-md-row">
            <a href="{{ $primary_button_url ?? '#' }}" class="btn btn-primary mb-2 mb-md-0" role="button">
                {{ $primary_button_text ?? 'Ready to get started?' }}
            </a>
            <a href="{{ $secondary_button_url ?? '#' }}" class="btn btn-link text-decoration-none text-muted ms-md-2 bg-light-hover" role="button">
                {{ $secondary_button_text ?? 'Start your free trial' }}
            </a>
        </div>
    </div>
    @php
        $reviews = $reviews ?? [
            ['name' => 'Annette Black', 'role' => 'Product Designer', 'quote' => 'VeCarCMS helped us launch faster than ever before.', 'rating' => 5],
            ['name' => 'Marvin McKinney', 'role' => 'Marketing Lead', 'quote' => 'The shortcode system is a game changer for our workflows.', 'rating' => 5],
            ['name' => 'Courtney Henry', 'role' => 'Agency Owner', 'quote' => 'We migrated our clients seamlessly and gained speed overnight.', 'rating' => 4],
            ['name' => 'Kathryn Murphy', 'role' => 'Freelancer', 'quote' => 'My clients love how easy it is to manage content now.', 'rating' => 5],
        ];
    @endphp
    <div class="mt-5 overflow-hidden">
        <div class="d-flex flex-nowrap gap-4 marquee">
            @foreach($reviews as $review)
                <div class="card shadow-sm border-0" style="min-width: 320px;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">
                                {{ strtoupper(substr($review['name'],0,1)) }}
                            </div>
                            <div class="text-start">
                                <h6 class="mb-0 fw-bold">{{ $review['name'] }}</h6>
                                <small class="text-muted">{{ $review['role'] }}</small>
                            </div>
                        </div>
                        <p class="text-muted fst-italic">“{{ $review['quote'] }}”</p>
                        <div class="text-warning">
                            @for($i = 0; $i < ($review['rating'] ?? 5); $i++)
                                <i class="ri-star-fill"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
@keyframes dwn-review-marquee {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}
.dwn-reviews .marquee {
    animation: dwn-review-marquee 30s linear infinite;
}
.dwn-reviews .marquee:hover {
    animation-play-state: paused;
}
</style>
