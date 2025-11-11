<div>
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('admin.members.segments_title') }}</h2>
            <p class="text-sm text-gray-600">{{ $totalMembers }} {{ __('admin.members.total_records') }}. {{ __('admin.members.select_segment') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($categories as $category)
                <div 
                    wire:click="showCategory('{{ $category['id'] }}')"
                    class="flex items-center p-4 {{ $category['bg_color'] }} rounded-lg cursor-pointer hover:shadow-md transition-shadow duration-200"
                >
                    <div class="flex-shrink-0 mr-3">
                        <span class="{{ $category['text_color'] }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                @if($category['icon'] === 'user-check')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                @elseif($category['icon'] === 'clock')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                @elseif($category['icon'] === 'credit-card')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                @elseif($category['icon'] === 'toggle-left')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12a4 4 0 014 4v0a4 4 0 01-4 4H8a4 4 0 110-8z" />
                                @elseif($category['icon'] === 'link')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.172 13.828a4 4 0 005.656 0l4-4a4 4 0 10-5.656-5.656l-1.102 1.101" />
                                @elseif($category['icon'] === 'users')
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                @endif
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="font-medium {{ $category['text_color'] }}">{{ $category['name'] }}</h3>
                        <p class="text-sm {{ $category['text_color'] }} opacity-80">{{ $category['count'] }} {{ $category['count'] == 1 ? __('admin.members.member_singular') : __('admin.members.member_plural') }}</p>
                    </div>
                    @if(isset($category['has_submenu']) && $category['has_submenu'])
                        <div class="ml-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 {{ $category['text_color'] }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Visualizzazione sottomenu piani -->
    @if(count($planCounts) > 0)
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('admin.members.plans_title') }}</h2>
            <p class="text-sm text-gray-600">{{ __('admin.members.select_plan') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($planCounts as $plan)
                <div 
                    wire:click="showCategory('{{ $plan['id'] }}')"
                    class="flex items-center p-4 bg-blue-50 rounded-lg cursor-pointer hover:shadow-md transition-shadow duration-200"
                >
                    <div class="flex-shrink-0 mr-3">
                        <span class="text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="font-medium text-blue-700">{{ $plan['name'] }}</h3>
                        <p class="text-sm text-blue-600 opacity-80">{{ $plan['count'] }} {{ $plan['count'] == 1 ? __('admin.members.member_singular') : __('admin.members.member_plural') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Visualizzazione sottomenu stati -->
    @if(count($statusCounts) > 0)
    <div class="bg-white p-4 rounded-lg shadow-sm mb-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold text-gray-800">{{ __('admin.members.status_title') }}</h2>
            <p class="text-sm text-gray-600">{{ __('admin.members.select_status') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($statusCounts as $status)
                <div 
                    wire:click="showCategory('{{ $status['id'] }}')"
                    class="flex items-center p-4 bg-purple-50 rounded-lg cursor-pointer hover:shadow-md transition-shadow duration-200"
                >
                    <div class="flex-shrink-0 mr-3">
                        <span class="text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12a4 4 0 014 4v0a4 4 0 01-4 4H8a4 4 0 110-8z" />
                            </svg>
                        </span>
                    </div>
                    <div>
                        <h3 class="font-medium text-purple-700">{{ $status['name'] }}</h3>
                        <p class="text-sm text-purple-600 opacity-80">{{ $status['count'] }} {{ $status['count'] == 1 ? __('admin.members.member_singular') : __('admin.members.member_plural') }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div> 