{{-- Latest Blog Posts Section --}}
@if($posts && $posts->count() > 0)
<section class="dwn-full-bleed py-10 py-lg-15 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-10">
            <div>
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Latest Articles</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Insights, stories, and updates from our team</p>
            </div>
            <a href="{{ url('/blog') }}" class="btn btn-link text-primary fw-medium" data-aos="fade-up">
                View All Posts <i class="ri-arrow-right-line ms-2"></i>
            </a>
        </div>

        <div class="row g-5">
            @foreach($posts->take(3) as $index => $post)
            <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <article class="card border-0 shadow-sm h-100 hover-lift transition-all">
                    @if($post->featured_image)
                    <a href="{{ url('/' . $post->slug) }}">
                        <img src="{{ $post->getImageUrl() }}" alt="{{ $post->title }}" class="card-img-top" style="height: 240px; object-fit: cover;">
                    </a>
                    @endif
                    <div class="card-body">
                        <div class="d-flex align-items-center text-sm text-muted mb-3">
                            <time datetime="{{ $post->published_at }}">
                                {{ $post->published_at->format('M d, Y') }}
                            </time>
                            @if($post->categories->count() > 0)
                                <span class="mx-2">•</span>
                                <a href="{{ get_term_link('category', $post->categories->first()) }}" class="text-primary text-decoration-none">
                                    {{ $post->categories->first()->name }}
                                </a>
                            @endif
                        </div>
                        <h5 class="card-title fw-bold mb-3">
                            <a href="{{ url('/' . $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                {{ $post->title }}
                            </a>
                        </h5>
                        @if($post->excerpt)
                        <p class="card-text text-muted">{{ str($post->excerpt)->limit(120) }}</p>
                        @endif
                        <a href="{{ url('/' . $post->slug) }}" class="btn btn-link text-primary p-0 fw-medium">
                            Read More <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
