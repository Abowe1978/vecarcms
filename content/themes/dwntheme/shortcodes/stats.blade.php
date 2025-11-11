{{-- Stats/Counter Shortcode --}}
<div class="stats-shortcode">
    <div class="row g-4 text-center">
        @foreach($stats as $stat)
        <div class="col-{{ 12 / $columns }}">
            <div class="p-4">
                <div class="display-4 fw-bold text-primary mb-2">{{ $stat['value'] }}</div>
                <div class="text-muted">{{ $stat['label'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

