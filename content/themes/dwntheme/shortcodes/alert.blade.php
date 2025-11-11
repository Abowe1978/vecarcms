{{-- Alert/Notice Shortcode --}}
<div class="alert alert-{{ $type }} {{ $dismissible ? 'alert-dismissible fade show' : '' }} d-flex align-items-center" role="alert">
    @if($icon)
    <i class="{{ $icon }} me-3 fs-4"></i>
    @endif
    <div>
        {!! $content !!}
    </div>
    @if($dismissible)
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>

