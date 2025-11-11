{{-- Categories List Shortcode --}}
<div class="categories-list-shortcode">
    <div class="list-group">
        @foreach($categories as $category)
        <a href="{{ get_term_link('category', $category) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
            <span>{{ $category->name }}</span>
            @if($show_count)
            <span class="badge bg-primary rounded-pill">{{ $category->posts_count }}</span>
            @endif
        </a>
        @endforeach
    </div>
</div>

