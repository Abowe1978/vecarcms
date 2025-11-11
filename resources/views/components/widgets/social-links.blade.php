<div class="widget widget-social-links bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
        {{ $title }}
    </h3>
    
    @if(count($links) > 0)
        <div class="flex flex-wrap gap-3">
            @foreach($links as $link)
                <a href="{{ $link['url'] }}" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="{{ $getIconSize() }} flex items-center justify-center bg-gray-100 hover:bg-purple-600 text-gray-700 hover:text-white rounded-full transition-all duration-200 hover:scale-110"
                   title="{{ $link['label'] }}">
                    <i class="{{ $link['icon'] }}"></i>
                </a>
            @endforeach
        </div>
        
        @if($showLabels)
            <div class="mt-4 space-y-2">
                @foreach($links as $link)
                    <a href="{{ $link['url'] }}" 
                       target="_blank"
                       class="flex items-center gap-2 text-sm text-gray-700 hover:text-purple-600 transition">
                        <i class="{{ $link['icon'] }} w-5"></i>
                        <span>{{ $link['label'] }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    @else
        <p class="text-gray-500 text-sm">No social links configured.</p>
    @endif
</div>

