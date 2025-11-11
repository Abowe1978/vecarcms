{{-- Button Shortcode --}}
<a href="{{ $url }}" class="btn btn-{{ $style }} btn-{{ $size }}">
    @if($icon)
    <i class="{{ $icon }} me-2"></i>
    @endif
    {{ $text }}
</a>

