{{-- DWN Features Section --}}
<section class="dwn-full-bleed dwn-features-shortcode py-10 py-lg-15">
    <div class="container">
        @if(isset($title) && $title)
        <div class="text-center mb-10">
            <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">{{ $title }}</h2>
            @if(isset($subtitle) && $subtitle)
            <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">{{ $subtitle }}</p>
            @endif
        </div>
        @endif
        
        <div class="row g-5">
            {{-- Feature 1: Page Builder --}}
            <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <div class="f-w-20 text-primary mb-4 mx-auto">
                        <i class="ri-edit-box-line"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Page Builder</h5>
                    <p class="text-muted">Visual drag & drop page builder like Elementor for creating stunning pages</p>
                </div>
            </div>
            
            {{-- Feature 2: Widget System --}}
            <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center">
                    <div class="f-w-20 text-primary mb-4 mx-auto">
                        <i class="ri-layout-grid-line"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Widget System</h5>
                    <p class="text-muted">Native drag & drop widgets built directly on top of Laravel.</p>
                </div>
            </div>
            
            {{-- Feature 3: Menu Builder --}}
            <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <div class="f-w-20 text-primary mb-4 mx-auto">
                        <i class="ri-menu-line"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Menu Builder</h5>
                    <p class="text-muted">Intuitive menu builder for creating custom navigation menus</p>
                </div>
            </div>
        </div>
    </div>
</section>
