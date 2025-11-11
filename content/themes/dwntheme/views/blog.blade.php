@extends('themes.dwntheme::layouts.main')

@section('title', (isset($blogPage) ? $blogPage->title : 'Blog') . ' - ' . settings('site_name', 'VeCarCMS'))

@section('content')

{{-- Breadcrumbs --}}
<section class="py-4 bg-light border-bottom">
    <div class="container">
        {!! render_breadcrumbs() !!}
    </div>
</section>

{{-- Page Header --}}
<section class="py-10 py-lg-12 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    {{ isset($blogPage) ? $blogPage->title : 'Blog' }}
                </h1>
                @if(isset($blogPage) && $blogPage->content)
                    <div class="lead text-muted">
                        {!! $blogPage->content !!}
                    </div>
                @else
                    <p class="lead text-muted">Insights, stories, and updates from our team</p>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Blog Posts with Sidebar --}}
<section class="py-10">
    <div class="container">
        <div class="row g-8">
            
            {{-- Main Content (Blog Posts) --}}
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
                                                <span class="mx-2">•</span>
                                                <span>{{ $post->author->name }}</span>
                                            </div>
                                            <h3 class="card-title fw-bold mb-3">
                                                <a href="{{ url('/' . $post->slug) }}" class="text-dark text-decoration-none hover-primary">
                                                    {{ $post->title }}
                                                </a>
                                            </h3>
                                            @if($post->excerpt)
                                            <p class="card-text text-muted mb-4">{{ str($post->excerpt)->limit(200) }}</p>
                                            @endif
                                            
                                            {{-- Tags --}}
                                            @if($post->tags->count() > 0)
                                            <div class="d-flex flex-wrap gap-2 mb-4">
                                                @foreach($post->tags->take(3) as $tag)
                                                    <a href="{{ get_term_link('tag', $tag) }}" class="badge bg-light text-muted text-decoration-none">
                                                        {{ $tag->name }}
                                                    </a>
                                                @endforeach
                                            </div>
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
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="f-w-20 text-muted mb-4 mx-auto">
                            <i class="ri-article-line"></i>
                        </div>
                        <h3 class="fw-bold mb-2">No posts found</h3>
                        <p class="text-muted">Check back later for new content.</p>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    @if(has_widgets('sidebar-blog'))
                        {!! widget_area('sidebar-blog') !!}
                    @else
                        {{-- Default Widgets --}}
                        <x-widgets.search-box />
                        <x-widgets.categories-list class="mt-5" />
                        <x-widgets.tag-cloud class="mt-5" :limit="20" />
                        <x-widgets.recent-posts class="mt-5" :limit="5" />
                    @endif
                </div>
            </div>

        </div>
    </div>
</section>

@endsection

