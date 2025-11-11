@extends('themes.dwntheme::layouts.main')

@section('title', $page->title . ' - ' . settings('site_name', 'VeCarCMS'))
@section('meta_description', $page->meta_description ?? str($page->content)->limit(160))

@section('content')

@if($page->use_page_builder && $page->page_builder_content)
    {{-- Page Builder Content - Full Control (no wrappers, no containers) --}}
    {!! do_shortcode(render_page_builder_content($page->page_builder_content)) !!}
@else
    {{-- Regular Page Content with Standard Layout --}}
    
    {{-- Breadcrumbs --}}
    @if(!$page->isHomepage())
    <section class="py-4 bg-light border-bottom">
        <div class="container">
            {!! render_breadcrumbs($page) !!}
        </div>
    </section>
    @endif

    {{-- Page Content --}}
    <article class="py-10 py-lg-12">
        <div class="container">
            {{-- Page Header --}}
            @if(!$page->isHomepage() && ($page->show_title ?? true))
            <header class="mb-8 text-center">
                <h1 class="display-4 fw-bold mb-4">{{ $page->title }}</h1>
                
                @if($page->excerpt)
                <p class="lead text-muted">{{ $page->excerpt }}</p>
                @endif
            </header>
            @endif

            {{-- Page Body --}}
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="prose prose-lg max-w-full">
                        {!! do_shortcode($page->content) !!}
                    </div>
                </div>
            </div>
        </div>
    </article>
@endif

@endsection

