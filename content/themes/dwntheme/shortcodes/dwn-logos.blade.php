{{-- DWN Logo Marquee --}}
<section class="dwn-full-bleed dwn-logo-marquee bg-primary py-8 text-white">
    <div class="container">
        <p class="mb-0 text-center small fw-bolder tracking-wider text-uppercase opacity-75">
            {{ $title ?? 'Trusted by thousands of companies worldwide' }}
        </p>
        <div class="mt-5 d-flex flex-wrap justify-content-center align-items-center gap-4 gap-md-5">
            @php
                $logos = $logos ?? [
                    'logo-1.svg','logo-2.svg','logo-3.svg','logo-4.svg','logo-5.svg','logo-6.svg','logo-7.svg','logo-8.svg'
                ];
            @endphp
            @foreach($logos as $logo)
                <div class="dwn-logo-item" data-aos="fade-in">
                    <img src="{{ filter_var($logo, FILTER_VALIDATE_URL) ? $logo : theme_asset('assets/images/logos/' . $logo) }}"
                         alt="{{ basename($logo) }}"
                         class="img-fluid opacity-90" style="max-height: 48px;">
                </div>
            @endforeach
        </div>
    </div>
</section>
