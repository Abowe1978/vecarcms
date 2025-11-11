{{-- DWN Team Members --}}
<section class="dwn-team-shortcode py-10">
    <div class="container">
        <div class="row g-5">
            @foreach($team as $member)
            <div class="col-12 col-md-6 col-lg-{{ 12 / $columns }}">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-5">
                        <img src="{{ $member['avatar'] }}" class="rounded-circle mb-4" width="128" height="128" alt="{{ $member['name'] }}">
                        <h5 class="fw-bold mb-1">{{ $member['name'] }}</h5>
                        <p class="text-muted mb-3">{{ $member['role'] }}</p>
                        @if(isset($member['bio']))
                        <p class="small text-muted mb-4">{{ $member['bio'] }}</p>
                        @endif
                        @if(isset($member['social']))
                        <div class="d-flex justify-content-center gap-2">
                            @foreach($member['social'] as $platform => $url)
                            <a href="{{ $url }}" class="btn btn-sm btn-outline-primary">
                                <i class="ri-{{ $platform }}-line"></i>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

