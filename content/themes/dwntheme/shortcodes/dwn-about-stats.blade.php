<section class="py-4">
    <div class="container">
        <div class="col-12 col-md-8 col-lg-6 mx-auto text-center border-bottom pb-5">
            <div class="my-4 d-flex d-md-none flex-column gap-3">
                @foreach($stats as $stat)
                    <div>
                        <span class="display-5 fw-bold text-primary d-block">{{ $stat['value'] ?? '' }}</span>
                        <span class="d-block fs-9 fw-bolder tracking-wide text-uppercase text-muted">{{ $stat['label'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
            <div class="my-5 d-none d-md-flex align-items-start justify-content-between">
                @foreach($stats as $stat)
                    <div>
                        <span class="display-3 fw-bold text-primary d-block">{{ $stat['value'] ?? '' }}</span>
                        <span class="d-block fs-9 fw-bolder tracking-wide text-uppercase text-muted">{{ $stat['label'] ?? '' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

