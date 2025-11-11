@extends('themes.dwntheme::layouts.main')

@section('title', $page->title . ' - ' . settings('site_name', 'VeCarCMS'))
@section('meta_description', $page->meta_description ?? str($page->content)->limit(160))

@section('content')

{{-- Breadcrumbs (if not homepage) --}}
@if(!$page->isHomepage())
<section class="py-5 bg-light border-bottom">
    <div class="container">
        {!! render_breadcrumbs($page) !!}
    </div>
</section>
@endif

{{-- Page Content --}}
<article class="py-10 py-lg-12">
    <div class="container">
        <div class="row">
            
            {{-- Main Content --}}
            <div class="{{ theme_setting('sidebar_position') === 'none' ? 'col-12' : 'col-12 col-lg-8' }}">
                {{-- Page Header --}}
                @if(!$page->isHomepage() && ($page->show_title ?? true))
                <header class="mb-8">
                    <h1 class="display-4 fw-bold mb-4">{{ $page->title }}</h1>
                    
                    @if($page->excerpt)
                    <p class="lead text-muted">{{ $page->excerpt }}</p>
                    @endif
                </header>
                @endif

                {{-- Page Body --}}
                @if($page->use_page_builder && $page->page_builder_content)
                    {{-- Render Page Builder Content (no wrapper to avoid CSS conflicts) --}}
                    {!! do_shortcode(render_page_builder_content($page->page_builder_content)) !!}
                @else
                    {{-- Render Regular Content with Shortcodes --}}
                    <div class="prose prose-lg max-w-full">
                        {!! do_shortcode($page->content) !!}
                    </div>
                @endif
            </div>

            {{-- Sidebar (if enabled) --}}
            @if(theme_setting('sidebar_position') !== 'none')
            <aside class="col-12 col-lg-4 mt-8 mt-lg-0">
                <div class="position-sticky" style="top: 2rem;">
                    @if(has_widgets('sidebar-page'))
                        {!! widget_area('sidebar-page') !!}
                    @else
                        {{-- Default Widgets --}}
                        <x-widgets.search-box />
                        <x-widgets.recent-posts class="mt-5" :limit="5" />
                        <x-widgets.categories-list class="mt-5" />
                    @endif
                </div>
            </aside>
            @endif

        </div>
    </div>
</article>

@endsection

