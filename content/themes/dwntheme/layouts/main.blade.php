<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<!-- Head -->
<head>
    <!-- Page Meta Tags-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', settings('site_description', ''))">
    <meta name="author" content="@yield('meta_author', settings('site_name', ''))">
    <meta name="keywords" content="@yield('meta_keywords', '')">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="@yield('title', settings('site_name', 'VeCarCMS'))">
    <meta property="og:description" content="@yield('meta_description', settings('site_description', ''))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    @stack('og_image')

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', settings('site_name', 'VeCarCMS'))">
    <meta name="twitter:description" content="@yield('meta_description', settings('site_description', ''))">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ theme_asset('assets/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ theme_asset('assets/images/logo.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ theme_asset('assets/images/logo.png') }}">
    <link rel="mask-icon" href="{{ theme_asset('assets/images/logo.png') }}" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ theme_asset('assets/css/libs.bundle.css') }}" />

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ theme_asset('assets/css/theme.bundle.css') }}" />

    <style>
        body {
            overflow-x: hidden;
        }

        .dwn-full-bleed {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
        }

        main > section,
        main > .dwn-full-bleed {
            padding-top: 4rem;
            padding-bottom: 4rem;
        }
    </style>

    {{-- Admin Bar CSS (only when logged in) --}}
    @auth
        @vite(['resources/css/admin-bar.css'])
    @endauth

    {{-- Custom Theme Styles --}}
    @if($customCss = theme_setting('custom_css'))
        <style>{!! $customCss !!}</style>
    @endif

    <!-- Fix for custom scrollbar if JS is disabled-->
    <noscript>
        <style>
          /**
          * Reinstate scrolling for non-JS clients
          */
          .simplebar-content-wrapper {
            overflow: auto;
          }
        </style>
    </noscript>

    <!-- Page Title -->
    <title>@yield('title', settings('site_name', 'VeCarCMS'))</title>

    {{-- Stack for additional head content (Schema.org, etc.) --}}
    @stack('head')
    
</head>
<body class="@yield('body_class', '') @auth admin-logged-in @endauth">

    {{-- Admin Bar (only visible when logged in) --}}
    @include('themes.dwntheme::partials.admin-bar')

    {{-- Header/Navbar --}}
    @include('themes.dwntheme::partials.header')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('themes.dwntheme::partials.footer')

    <!-- Vendor JS -->
    <script src="{{ theme_asset('assets/js/vendor.bundle.js') }}"></script>

    <!-- Theme JS -->
    <script src="{{ theme_asset('assets/js/theme.bundle.js') }}"></script>

    {{-- Stack for additional scripts --}}
    @stack('scripts')

</body>
</html>

