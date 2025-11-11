@extends('themes.dwntheme::layouts.main')

@section('title', $page->title . ' - ' . settings('site_name', 'VeCarCMS'))
@section('meta_description', $page->meta_description ?? str($page->content)->limit(160))

@section('body_class', 'landing-page')

@section('content')

{{-- Landing Page - No Breadcrumbs, No Distractions --}}
<article>
    {{-- Hero Section with Page Title --}}
    @if(!$page->isHomepage() && ($page->show_title ?? true))
    <section class="py-15 py-lg-20 bg-gradient-to-br from-primary-50 to-secondary-50 text-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <h1 class="display-3 fw-bold mb-4">{{ $page->title }}</h1>
                    
                    @if($page->excerpt)
                    <p class="lead text-muted mb-5">{{ $page->excerpt }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Page Content - Full Width, No Sidebar, Landing Optimized --}}
    <div class="py-0 py-lg-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="prose prose-lg max-w-full">
    @if($page->use_page_builder && $page->page_builder_content)
        {{-- Render Page Builder Content with Shortcodes --}}
        <div id="page-builder-content">
            {!! do_shortcode(render_page_builder_content($page->page_builder_content)) !!}
        </div>
    @else
        {{-- Render Regular Content with Shortcodes --}}
        {!! do_shortcode($page->content) !!}
    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

@endsection

