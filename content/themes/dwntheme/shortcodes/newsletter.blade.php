{{-- Newsletter Signup Shortcode --}}
<div class="newsletter-shortcode style-{{ $style }}">
    @if($style === 'default')
    <div class="card border-0 shadow-sm">
        <div class="card-body p-5 text-center">
            <h3 class="h4 fw-bold mb-3">{{ $title }}</h3>
            <p class="text-muted mb-4">{{ $subtitle }}</p>
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="row g-3 justify-content-center">
                @csrf
                <div class="col-auto flex-grow-1" style="max-width: 400px;">
                    <input type="email" name="email" class="form-control" placeholder="Your email address" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="ri-mail-send-line me-2"></i>Subscribe
                    </button>
                </div>
            </form>
        </div>
    </div>
    @elseif($style === 'inline')
    <div class="d-flex gap-3 align-items-center">
        <div class="flex-shrink-0">
            <strong>{{ $title }}</strong>
        </div>
        <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex-grow-1">
            @csrf
            <div class="input-group">
                <input type="email" name="email" class="form-control" placeholder="Your email address" required>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </div>
        </form>
    </div>
    @endif
</div>

