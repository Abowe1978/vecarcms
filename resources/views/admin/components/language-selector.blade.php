<div x-data="{ open: false }" class="relative inline-block">
    <button @click="open = !open"
            class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none">
        <span class="text-xl">{{ $languages[$currentLocale]['flag'] }}</span>
        <span class="hidden md:inline">{{ $languages[$currentLocale]['name'] }}</span>
        <i class="fas fa-chevron-down text-xs"></i>
    </button>

    <div x-show="open"
         x-cloak
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute {{ config('app.available_locales')[app()->getLocale()]['rtl'] ? 'right-0' : 'left-0' }} origin-top-right mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 z-50">
        
        @foreach($languages as $locale => $language)
            <form action="{{ route('language.switch', $locale) }}" method="POST" class="block">
                @csrf
                <button type="submit" 
                        class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ $currentLocale === $locale ? 'bg-gray-100' : '' }}">
                    <span class="text-xl mr-2">{{ $language['flag'] }}</span>
                    <span>{{ $language['name'] }}</span>
                </button>
            </form>
        @endforeach
    </div>
</div> 