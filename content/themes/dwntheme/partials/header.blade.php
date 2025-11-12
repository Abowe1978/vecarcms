<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">

        <!-- Logo-->
        <a class="navbar-brand d-flex align-items-center lh-1 me-10 transition-opacity opacity-75-hover" href="{{ url('/') }}">
            @if(theme_setting('custom_logo'))
                <span class="d-flex align-items-center">
                    <img src="{{ theme_setting('custom_logo') }}" alt="{{ settings('site_name', 'VeCarCMS') }}" class="img-fluid" style="max-height: 40px;">
                    <span class="fw-bold text-body ms-2">{{ settings('site_name', 'VeCarCMS') }}</span>
                </span>
            @else
                <span class="d-flex align-items-center">
                    <img src="{{ theme_asset('assets/images/logo.png') }}" alt="{{ settings('site_name', 'VeCarCMS') }}" class="img-fluid" style="max-height: 40px;">
                    <span class="fw-bold text-body ms-2">{{ settings('site_name', 'VeCarCMS') }}</span>
                </span>
            @endif
        </a>
        <!-- / Logo-->

        <!-- Mobile Menu Btn-->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="ri-menu-line"></i>
        </button>
        <!-- /Mobile Menu Btn-->

        <div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarSupportedContent">
            {{-- Primary Menu from VeCarCMS Menu Builder --}}
            @if(has_menu('primary'))
                {!! menu('primary', ['class' => 'navbar-nav']) !!}
            @else
                {{-- Fallback menu if no menu is assigned --}}
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/blog') }}">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/contact') }}">Contact</a>
                    </li>
                </ul>
            @endif


        </div>

    </div>
</nav>
<!-- / Navbar -->

