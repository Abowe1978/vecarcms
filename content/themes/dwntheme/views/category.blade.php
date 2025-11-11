@extends('themes.dwntheme::layouts.main')

@section('title', $category->name . ' - ' . settings('site_name', 'VeCarCMS'))
@section('meta_description', $category->description ?? 'Browse all posts in ' . $category->name)

@section('content')

{{-- Breadcrumbs --}}
<section class="py-4 bg-light border-bottom">
    <div class="container">
        {!! render_breadcrumbs($category) !!}
    </div>
</section>

{{-- Page Header --}}
<section class="py-10 py-lg-12 bg-gradient-to-br from-primary-50 to-secondary-50">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <span class="badge bg-primary text-white mb-3">Category</span>
                <h1 class="display-4 fw-bold mb-4">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="lead text-muted">{{ $category->description }}</p>
                @endif
                <p class="text-muted">
                    <i class="ri-article-line me-2"></i>{{ $posts->total() }} {{ str('post')->plural($posts->total()) }}
                </p>
            </div>
        </div>
    </div>
</section>

{{-- Posts with Sidebar --}}
<section class="py-10">
    <div class="container">
        <div class="row g-8">
            
            {{-- Main Content --}}
            <div class="col-12 col-lg-8">
                @if($posts->count() > 0)
                    <div class="row g-5">
                        @foreach($posts as $post)
                        <div class="col-12 col-md-6">
                            <article class="card border-0 shadow-sm h-100 hover-lift transition-all">
                                @if($post->featured_image)
                                <a href="{{ url('/' . $post->slug) }}">
                                    <img src="{{ $post->getImageUrl() }}" alt="{{ $post->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                </a>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex align-items-center text-sm text-muted mb-3">
                                        <time datetime="{{ $post->published_at }}">
                                            {{ $post->published_at->format('M d, Y') }}
                                        </time>
                                        <span class="mx-2">•</span>
                                        <span>{{ $post->author->name }}</span>
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

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $posts->links() }}
                    </div>
                @else
                    <div class="text-center py-10">
                        <div class="f-w-20 text-muted mb-4 mx-auto">
                            <i class="ri-article-line"></i>
                        </div>
                        <h3 class="fw-bold mb-2">No posts in this category yet</h3>
                        <p class="text-muted">Check back later for new content.</p>
                        <a href="{{ url('/blog') }}" class="btn btn-primary mt-4">View All Posts</a>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-12 col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    @if(has_widgets('sidebar-blog'))
                        {!! widget_area('sidebar-blog') !!}
                    @else
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

