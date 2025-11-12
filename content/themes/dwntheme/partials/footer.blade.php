<!-- Footer -->
@once
    <style>
        .footer-nav li + li {
            margin-top: .4rem;
        }

        .footer-nav li a {
            color: rgba(148, 163, 184, 0.85);
            text-decoration: none;
            transition: color .2s ease, opacity .2s ease;
        }

        .footer-nav li a:hover {
            color: #ffffff;
        }

        .dwn-footer-menu {
            gap: 2rem;
        }

        .dwn-footer-menu li a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: .9rem;
            transition: color .2s ease, opacity .2s ease;
        }

        .dwn-footer-menu li a:hover {
            color: #ffffff;
            opacity: 1;
        }
    </style>
@endonce
<footer class="bg-dark pt-10 pb-5  ">
    <div class="container">

        <!-- Footer Widgets Row -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start pb-5">
            
            <!-- Footer Logo & Brand-->
            <div class="mb-5 mb-lg-0 me-lg-10">
                <a class="d-flex align-items-center text-decoration-none mb-4" href="{{ url('/') }}">
                    @if(theme_setting('custom_logo'))
                        <span class="d-flex align-items-center">
                            <img src="{{ theme_setting('custom_logo') }}" alt="{{ settings('site_name', 'VeCarCMS') }}" class="img-fluid" style="max-height: 35px; filter: brightness(0) invert(1);">
                            <span class="fw-bold text-white ms-2">{{ settings('site_name', 'VeCarCMS') }}</span>
                        </span>
                    @else
                        <span class="d-flex align-items-center">
                            <img src="{{ theme_asset('assets/images/logo.png') }}" alt="{{ settings('site_name', 'VeCarCMS') }}" class="img-fluid" style="max-height: 35px; filter: brightness(0) invert(1);">
                            <span class="fw-bold text-white ms-2">{{ settings('site_name', 'VeCarCMS') }}</span>
                        </span>
                    @endif
                </a>
                <p class="text-white opacity-75" style="max-width: 300px;">
                    {{ settings('site_description', 'A modern Laravel CMS for building beautiful digital experiences') }}
                </p>

                <!-- Footer socials-->
                <ul class="list-unstyled d-flex align-items-center mt-4">
                    @if($facebook = settings('social_facebook'))
                        <li class="me-4"><a href="{{ $facebook }}" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-facebook-circle-line ri-lg"></i></a></li>
                    @endif
                    @if($twitter = settings('social_twitter'))
                        <li class="me-4"><a href="{{ $twitter }}" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-twitter-line ri-lg"></i></a></li>
                    @endif
                    @if($instagram = settings('social_instagram'))
                        <li class="me-4"><a href="{{ $instagram }}" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-instagram-line ri-lg"></i></a></li>
                    @endif
                    @if($linkedin = settings('social_linkedin'))
                        <li class="me-4"><a href="{{ $linkedin }}" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-linkedin-line ri-lg"></i></a></li>
                    @endif
                    @if($youtube = settings('social_youtube'))
                        <li class="me-4"><a href="{{ $youtube }}" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-youtube-line ri-lg"></i></a></li>
                    @endif
                </ul>
                <!-- /Footer socials-->
            </div>
            <!-- / Footer Logo & Brand-->


            <div class="d-flex flex-wrap flex-grow-1 justify-content-between">

                <!-- Footer Widget Column 1-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    @if(has_widgets('footer-1'))
                        {!! widget_area('footer-1') !!}
                    @else
                        <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Company</h6>
                        <ul class="list-unstyled footer-nav">
                            <li><a href="{{ url('/about') }}">About Us</a></li>
                            <li><a href="{{ url('/blog') }}">Blog</a></li>
                            <li><a href="{{ url('/contact') }}">Contact</a></li>
                        </ul>
                    @endif
                </div>
                <!-- /Footer Widget Column 1-->

                <!-- Footer Widget Column 2-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    @if(has_widgets('footer-2'))
                        {!! widget_area('footer-2') !!}
                    @else
                        <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Resources</h6>
                        <ul class="list-unstyled footer-nav">
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Support</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    @endif
                </div>
                <!-- /Footer Widget Column 2-->

                <!-- Footer Widget Column 3-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    @if(has_widgets('footer-3'))
                        {!! widget_area('footer-3') !!}
                    @else
                        <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Legal</h6>
                        <ul class="list-unstyled footer-nav">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                        </ul>
                    @endif
                </div>
                <!-- /Footer Widget Column 3-->

                <!-- Footer Widget Column 4-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    @if(has_widgets('footer-4'))
                        {!! widget_area('footer-4') !!}
                    @endif
                </div>
                <!-- /Footer Widget Column 4-->

            </div>

        </div>
        <!-- / Footer Widgets Row -->

        <!-- Footer Copyright-->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-5 border-top border-white-10">
            <small class="text-muted">
                &copy; {{ date('Y') }} {{ settings('site_name', 'VeCarCMS') }}. All rights reserved. Powered by <a href="https://vecar cms.com" class="text-white text-decoration-none">VeCarCMS</a>.
            </small>

            {{-- Footer Menu --}}
            @if(has_menu('footer'))
                {!! menu('footer', ['class' => 'list-unstyled d-flex mt-3 mt-md-0 mb-0 dwn-footer-menu']) !!}
            @endif
        </div>
        <!-- / Footer Copyright-->

    </div>
</footer>
<!-- / Footer -->

