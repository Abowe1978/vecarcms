@extends('themes.dwntheme::layouts.main')

@section('title', settings('site_name', 'VeCarCMS') . ' - ' . settings('site_tagline', 'A Powerful CMS'))

@section('content')

{{-- Render Homepage Content from Database using Shortcodes --}}
{!! do_shortcode($page->content ?? '') !!}

@endsection
