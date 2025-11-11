<div class="widget widget-tag-cloud bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
        {{ $title }}
    </h3>
    
    @if($tags->count() > 0)
        <div class="flex flex-wrap gap-2">
            @foreach($tags as $tag)
                <a href="{{ get_term_link('tag', $tag) }}" 
                   class="{{ $getFontSize($tag) }} px-3 py-1 bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-700 rounded-full transition-all duration-200 hover:shadow-md">
                    {{ $tag->name }}
                    @if(isset($tag->posts_count) && $tag->posts_count > 0)
                        <span class="text-xs opacity-60">({{ $tag->posts_count }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    @else
        <p class="text-gray-500 text-sm">No tags available.</p>
    @endif
</div>

