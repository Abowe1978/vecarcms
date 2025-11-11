{{-- DWN Icon Box --}}
<div class="dwn-icon-box style-{{ $style }}">
    @if($style === 'bordered')
    <div class="card border-2 h-100">
        <div class="card-body p-5 text-center">
            <i class="{{ $icon }} text-primary mb-4" style="font-size: 3rem;"></i>
            @if($title)
            <h5 class="fw-bold mb-3">{{ $title }}</h5>
            @endif
            @if($content)
            <div class="text-muted">{!! $content !!}</div>
            @endif
            @if($link)
            <a href="{{ $link }}" class="btn btn-link text-primary mt-3">Learn More <i class="ri-arrow-right-line ms-1"></i></a>
            @endif
        </div>
    </div>
    @else
    <div class="text-center p-4">
        <i class="{{ $icon }} text-primary mb-4" style="font-size: 3rem;"></i>
        @if($title)
        <h5 class="fw-bold mb-3">{{ $title }}</h5>
        @endif
        @if($content)
        <div class="text-muted">{!! $content !!}</div>
        @endif
        @if($link)
        <a href="{{ $link }}" class="btn btn-link text-primary mt-3">Learn More <i class="ri-arrow-right-line ms-1"></i></a>
        @endif
    </div>
    @endif
</div>

