@php
    $imageSource = $image ?? 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80';
    $imageSource = filter_var($imageSource, FILTER_VALIDATE_URL)
        ? $imageSource
        : theme_asset('assets/images/' . ltrim($imageSource, '/'));
@endphp

<section class="py-6">
    <div class="container">
        <div class="row gx-8 align-items-center {{ $reverse ? 'flex-row-reverse' : '' }}">
            <div class="col-12 col-lg-6">
                @if(!empty($eyebrow))
                    <p class="mb-3 small fw-bolder tracking-wider text-uppercase text-primary">{{ $eyebrow }}</p>
                @endif
                @if(!empty($title))
                    <h2 class="display-5 fw-bold mb-6">{{ $title }}</h2>
                @endif
                @if(!empty($content))
                    {!! $content !!}
                @endif
            </div>
            <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                <picture>
                    <img class="img-fluid rounded shadow-sm" src="{{ $imageSource }}" alt="{{ $title ?? 'About story image' }}">
                </picture>
            </div>
        </div>
    </div>
</section>

