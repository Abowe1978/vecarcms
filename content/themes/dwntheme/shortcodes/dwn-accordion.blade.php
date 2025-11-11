{{-- DWN Accordion/FAQ --}}
<div class="dwn-accordion-shortcode">
    <div class="accordion" id="{{ $id }}">
        @foreach($items as $index => $item)
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapse-{{ $id }}-{{ $index }}">
                    {{ $item['title'] }}
                </button>
            </h2>
            <div id="collapse-{{ $id }}-{{ $index }}" 
                 class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                 data-bs-parent="#{{ $id }}">
                <div class="accordion-body">
                    {!! $item['content'] !!}
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

