{{-- Features Grid Shortcode --}}
<section class="features-shortcode py-5">
    <div class="container">
        @if($title)
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold mb-3">{{ $title }}</h2>
            @if($subtitle)
            <p class="lead text-muted">{{ $subtitle }}</p>
            @endif
        </div>
        @endif
        
        <div class="row g-5">
            {{-- Feature 1: Page Builder --}}
            <div class="col-12 col-md-6 col-lg-{{ 12 / $columns }}">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="ri-edit-box-line text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Page Builder</h5>
                    <p class="text-muted">Visual drag & drop page builder like Elementor for creating stunning pages</p>
                </div>
            </div>
            
            {{-- Feature 2: Widget System --}}
            <div class="col-12 col-md-6 col-lg-{{ 12 / $columns }}">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="ri-layout-grid-line text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Widget System</h5>
                    <p class="text-muted">Widget system con drag & drop nativo in Laravel</p>
                </div>
            </div>
            
            {{-- Feature 3: Menu Builder --}}
            <div class="col-12 col-md-6 col-lg-{{ 12 / $columns }}">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="ri-menu-line text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Menu Builder</h5>
                    <p class="text-muted">Intuitive menu builder for creating custom navigation menus</p>
                </div>
            </div>
        </div>
        
        @if($content)
        <div class="mt-5">
            {!! $content !!}
        </div>
        @endif
    </div>
</section>

