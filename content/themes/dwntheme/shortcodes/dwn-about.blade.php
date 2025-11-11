{{-- DWN About Section --}}
<section class="dwn-full-bleed dwn-about pt-10 bg-light">
    <div class="container">
        <div class="col-12 mx-auto col-sm-10 col-md-8 col-lg-6 text-center">
            <h4 class="fs-1 fw-bold mb-4">{{ $title ?? 'Who we are' }}</h4>
            @php
                $stats = $stats ?? [
                    ['value' => '15K+', 'label' => 'Websites powered'],
                    ['value' => '120+', 'label' => 'Custom shortcodes'],
                    ['value' => '98%', 'label' => 'Client satisfaction'],
                ];
            @endphp
            <div class="row g-4 text-center mb-4">
                @foreach($stats as $stat)
                    <div class="col-{{ 12 / min(count($stats), 3) }}">
                        <div class="p-4">
                            <div class="display-5 fw-bold text-primary mb-2">{{ $stat['value'] }}</div>
                            <div class="text-muted">{{ $stat['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
            <p>{!! $content ?? 'VeCarCMS helps marketing teams launch modern websites with an intuitive workflow built entirely on Laravel.' !!}</p>
            <a href="{{ $button_url ?? '#' }}" class="btn btn-success mt-4">{{ $button_text ?? 'Find out more' }}</a>
        </div>
    </div>
    <div class="position-relative mt-10">
        <picture class="d-table mx-auto mt-5 col-12 col-sm-10 position-relative z-index-20">
            <img class="img-fluid d-table mx-auto" src="{{ $image ?? theme_asset('assets/images/team-photo.png') }}" alt="Team photo">
        </picture>
        <div class="position-absolute top-0 end-0 start-0 bottom-0 z-index-0 d-none d-lg-block">
            <div class="d-block f-w-6 position-absolute top-n13 end-50">
                <span class="d-block">
                    <svg class="w-100 h-auto" viewBox="0 0 119 119" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="59.5" cy="59.5" r="59.5" fill="#EEF2FF" />
                    </svg>
                </span>
            </div>
            <div class="d-block f-w-6 position-absolute bottom-n10 start-0">
                <span class="d-block">
                    <svg class="w-100 h-auto" viewBox="0 0 119 119" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="59.5" cy="59.5" r="59.5" fill="#FFF4E6" />
                    </svg>
                </span>
            </div>
        </div>
    </div>
</section>
