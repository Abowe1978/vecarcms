{{-- Contact Form Shortcode --}}
<div class="contact-form-shortcode">
    @if($title)
    <h3 class="h4 fw-bold mb-4">{{ $title }}</h3>
    @endif
    
    <form action="{{ route('contact.submit') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        
        <div class="row g-4">
            <div class="col-md-6">
                <label for="name" class="form-label">Name *</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="col-md-6">
                <label for="email" class="form-label">Email *</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="col-12">
                <label for="subject" class="form-label">Subject *</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>
            
            <div class="col-12">
                <label for="message" class="form-label">Message *</label>
                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="ri-send-plane-fill me-2"></i>Send Message
                </button>
            </div>
        </div>
    </form>
    
    @if($show_map && settings('google_maps_api_key'))
    <div class="mt-5">
        <iframe
            width="100%"
            height="400"
            frameborder="0"
            style="border:0; border-radius: 8px;"
            src="https://www.google.com/maps/embed/v1/place?key={{ settings('google_maps_api_key') }}&q={{ urlencode(settings('company_address')) }}"
            allowfullscreen>
        </iframe>
    </div>
    @endif
</div>

