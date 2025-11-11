<div class="widget widget-recent-posts bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
        {{ $title }}
    </h3>
    
    @if($posts->count() > 0)
        <ul class="space-y-4">
            @foreach($posts as $post)
                <li class="group">
                    <article class="flex gap-3">
                        @if($showThumbnail && $post->featured_image)
                            <a href="{{ url('/' . $post->slug) }}" class="flex-shrink-0">
                                <img src="{{ $post->getImageUrl() }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-16 h-16 object-cover rounded-md group-hover:opacity-80 transition">
                            </a>
                        @endif
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-sm">
                                <a href="{{ url('/' . $post->slug) }}" 
                                   class="text-gray-900 hover:text-purple-600 transition">
                                    {{ $post->title }}
                                </a>
                            </h4>
                            
                            @if($showDate && $post->published_at)
                                <time class="text-xs text-gray-500" datetime="{{ $post->published_at }}">
                                    {{ $post->published_at->format('M d, Y') }}
                                </time>
                            @endif
                        </div>
                    </article>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500 text-sm">No recent posts available.</p>
    @endif
</div>

