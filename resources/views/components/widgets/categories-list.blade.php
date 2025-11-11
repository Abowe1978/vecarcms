<div class="widget widget-categories bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
        {{ $title }}
    </h3>
    
    @if($categories->count() > 0)
        <ul class="space-y-2">
            @foreach($categories as $category)
                <li>
                    <a href="{{ get_term_link('category', $category) }}" 
                       class="flex items-center justify-between text-gray-700 hover:text-purple-600 transition group">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-folder text-purple-500 text-sm"></i>
                            <span class="group-hover:translate-x-1 transition-transform">{{ $category->name }}</span>
                        </span>
                        
                        @if($showCount)
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full group-hover:bg-purple-100 group-hover:text-purple-700 transition">
                                {{ $category->posts_count ?? 0 }}
                            </span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-gray-500 text-sm">No categories available.</p>
    @endif
</div>

