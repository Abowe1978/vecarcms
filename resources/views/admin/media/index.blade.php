@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('admin.media.title') }}</h1>
        
        <button 
            x-data
            @click="$dispatch('open-media-upload')"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg inline-flex items-center">
            <i class="fas fa-upload mr-2"></i>
            {{ __('admin.media.upload') }}
        </button>
    </div>

    <!-- Media Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
        @forelse($media as $item)
        <div class="relative group bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="aspect-w-1 aspect-h-1">
                @if(\Illuminate\Support\Str::contains($item->mime_type, 'image'))
                    <img src="{{ $item->url }}" 
                         alt="{{ $item->name }}"
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-100">
                        <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                    </div>
                @endif
            </div>
            
            <div class="p-2">
                <p class="text-sm font-medium text-gray-900 truncate" title="{{ $item->name }}">
                    {{ $item->name }}
                </p>
                <p class="text-xs text-gray-500">
                    {{ $item->created_at->format('d M Y') }}
                </p>
            </div>

            <!-- Hover Actions -->
            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <div class="flex space-x-2">
                    <button 
                        onclick="copyToClipboard('{{ $item->url }}')"
                        class="p-2 bg-white rounded-full text-gray-700 hover:text-blue-600"
                        title="{{ __('admin.media.copy_url') }}">
                        <i class="fas fa-link"></i>
                    </button>
                    
                    <form action="{{ route('admin.media.destroy', $item) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('{{ __('admin.media.confirm_delete') }}')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="p-2 bg-white rounded-full text-gray-700 hover:text-red-600"
                                title="{{ __('admin.media.delete') }}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <div class="text-gray-400">
                <i class="fas fa-images text-4xl mb-4"></i>
                <p>{{ __('admin.media.no_media') }}</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $media->links() }}
    </div>

    <!-- Upload Modal -->
    <x-media-library />
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        alert('{{ __('admin.media.url_copied') }}');
    });
}
</script>
@endpush
@endsection 