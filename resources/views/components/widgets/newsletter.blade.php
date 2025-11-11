<div class="widget widget-newsletter bg-gradient-to-br from-purple-600 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
    <h3 class="text-lg font-bold mb-2">
        <i class="fas fa-envelope mr-2"></i>{{ $title }}
    </h3>
    
    <p class="text-purple-100 text-sm mb-4">{{ $description }}</p>
    
    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="space-y-3" id="newsletter-form">
        @csrf
        
        <div>
            <input type="email" 
                   name="email" 
                   placeholder="{{ $placeholder }}" 
                   required
                   class="w-full px-4 py-2 rounded-lg text-gray-900 focus:ring-2 focus:ring-purple-300 focus:outline-none">
        </div>
        
        <button type="submit" 
                class="w-full bg-white text-purple-600 font-semibold px-4 py-2 rounded-lg hover:bg-purple-50 transition">
            {{ $buttonText }}
        </button>
    </form>
    
    <p class="text-xs text-purple-200 mt-3">
        We respect your privacy. Unsubscribe at any time.
    </p>
</div>

