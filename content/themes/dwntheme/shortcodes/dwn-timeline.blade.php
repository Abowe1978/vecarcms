{{-- DWN Timeline --}}
<section class="dwn-timeline-shortcode py-10">
    <div class="container">
        @if($style === 'vertical')
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                @foreach($events as $index => $event)
                <div class="d-flex mb-5">
                    <div class="flex-shrink-0 me-4 text-center" style="width: 80px;">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" style="width: 60px; height: 60px;">
                            {{ $event['year'] }}
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2">{{ $event['title'] }}</h5>
                                <p class="text-muted mb-0">{{ $event['description'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

