<div class="widget widget-custom-html bg-white rounded-lg shadow-md p-6">
    @if($showTitle && $title)
        <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
            {{ $title }}
        </h3>
    @endif
    
    <div class="custom-html-content">
        {!! $content !!}
    </div>
</div>

