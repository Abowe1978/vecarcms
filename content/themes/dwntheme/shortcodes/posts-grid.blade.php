{{-- Posts Grid Shortcode --}}
<div class="posts-grid-shortcode">
    <div class="row g-4">
        @foreach($posts as $post)
        <div class="col-12 col-md-{{ 12 / min($columns, 4) }} col-lg-{{ 12 / $columns }}">
            <article class="card border-0 shadow-sm h-100">
                @if($post->featured_image)
                <a href="{{ url('/' . $post->slug) }}">
                    <img src="{{ $post->getImageUrl() }}" alt="{{ $post->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                </a>
                @endif
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">
                        <a href="{{ url('/' . $post->slug) }}" class="text-dark text-decoration-none">
                            {{ $post->title }}
                        </a>
                    </h5>
                    @if($show_excerpt && $post->excerpt)
                    <p class="card-text text-muted">{{ str($post->excerpt)->limit(100) }}</p>
                    @endif
                    <div class="mt-auto">
                        <small class="text-muted">{{ $post->published_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </article>
        </div>
        @endforeach
    </div>
</div>

