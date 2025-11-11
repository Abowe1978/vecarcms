@extends('themes.dwntheme::layouts.main')

@section('title', 'Search Results for "' . $query . '" - ' . settings('site_name', 'VeCarCMS'))

@section('content')

{{-- Breadcrumbs --}}
<section class="py-4 bg-light border-bottom">
    <div class="container">
        {!! render_breadcrumbs() !!}
    </div>
</section>

{{-- Search Header --}}
<section class="py-10 py-lg-12 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 text-center">
                <h1 class="display-5 fw-bold mb-4">Search Results</h1>
                <p class="lead text-muted mb-5">
                    @if($posts->total() > 0)
                        Found {{ $posts->total() }} {{ str('result')->plural($posts->total()) }} for <strong>"{{ $query }}"</strong>
                    @else
                        No results found for <strong>"{{ $query }}"</strong>
                    @endif
                </p>
                
                {{-- Search Form --}}
                <form action="{{ route('search') }}" method="GET" class="mb-4">
                    <div class="input-group input-group-lg shadow-sm">
                        <input type="text" name="q" class="form-control" placeholder="Search articles..." value="{{ $query }}" required>
                        <button class="btn btn-primary" type="submit">
                            <i class="ri-search-line me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- Search Results --}}
<section class="py-10">
    <div class="container">
        <div class="row g-8">
            
            {{-- Main Content --}}
            <div class="col-12 col-lg-8">
                @if($posts->count() > 0)
                    <div class="row g-5">
                        @foreach($posts as $post)
                        <div class="col-12">
                            <article class="card border-0 shadow-sm hover-lift transition-all">
                                <div class="row g-0">
                                    @if($post->featured_image)
                                    <div class="col-md-4">
                                        <a href="{{ url('/' . $post->slug) }}">
                                            <img src="{{ $post->getImageUrl() }}" alt="{{ $post->title }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                        </a>
                                    </div>
                                    @endif
                                    <div class="{{ $post->featured_image ? 'col-md-8' : 'col-12' }}">
                                        <div class="card-body p-5">
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
                                            <h4 class="card-title fw-bold mb-3">
                                                <a href="{{ url('/' . $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                                    {{ $post->title }}
                                                </a>
                                            </h4>
                                            @if($post->excerpt)
                                            <p class="card-text text-muted mb-4">{{ str($post->excerpt)->limit(200) }}</p>
                                            @endif
                                            <a href="{{ url('/' . $post->slug) }}" class="btn btn-link text-primary p-0 fw-medium">
                                                Read More <i class="ri-arrow-right-line ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $posts->appends(['q' => $query])->links() }}
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="f-w-20 text-muted mb-4 mx-auto">
                            <i class="ri-search-line"></i>
                        </div>
                        <h3 class="fw-bold mb-2">No results found</h3>
                        <p class="text-muted mb-4">We couldn't find any articles matching your search. Try different keywords or browse our categories.</p>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <a href="{{ url('/blog') }}" class="btn btn-primary">View All Posts</a>
                            <a href="{{ url('/') }}" class="btn btn-outline-primary">Back to Home</a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    @if(has_widgets('sidebar-blog'))
                        {!! widget_area('sidebar-blog') !!}
                    @else
                        <x-widgets.categories-list />
                        <x-widgets.tag-cloud class="mt-5" :limit="20" />
                        <x-widgets.recent-posts class="mt-5" :limit="5" />
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

