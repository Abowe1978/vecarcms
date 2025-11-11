<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200 ' . $colorClasses()]) }}>
    {{ $slot }}
</a> 