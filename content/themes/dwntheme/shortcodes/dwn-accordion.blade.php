{{-- DWN Accordion/FAQ --}}
@once
    <style>
        .faq-info {
            max-width: 860px;
            margin: 0 auto;
        }

        .faq-info .accordion-item {
            border: 1px solid rgba(148, 163, 184, 0.25);
            border-radius: 14px;
            margin-bottom: 1rem;
            overflow: hidden;
            background: #ffffff;
            box-shadow: 0 25px 55px -30px rgba(15, 23, 42, 0.25);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .faq-info .accordion-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 30px 60px -32px rgba(15, 23, 42, 0.3);
        }

        .faq-info .accordion-button {
            padding: 1.3rem 1.5rem;
            font-weight: 600;
            font-size: 1.05rem;
            color: #1f2937;
            background-color: #ffffff;
            border: none;
            box-shadow: none;
            transition: background .2s ease, color .2s ease;
        }

        .faq-info .accordion-button:focus {
            box-shadow: none;
        }

        .faq-info .accordion-button:not(.collapsed) {
            color: #ffffff;
            background: linear-gradient(135deg, #376bff, #6087ff);
        }

        .faq-info .accordion-button::after {
            width: 1.1rem;
            height: 1.1rem;
            background-size: 1.1rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' fill='none' viewBox='0 0 24 24'%3E%3Cpath fill='%23343f52' d='M12 14.25a.75.75 0 0 1-.53-.22l-4.5-4.5a.75.75 0 1 1 1.06-1.06L12 12.44l3.97-3.97a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-.53.22Z'/%3E%3C/svg%3E");
            transform: rotate(0deg);
            transition: transform .2s ease, filter .2s ease;
        }

        .faq-info .accordion-button:not(.collapsed)::after {
            filter: brightness(0) invert(1);
            transform: rotate(180deg);
        }

        .faq-info .accordion-body {
            padding: 1.4rem 1.5rem 1.6rem;
            background: #f8fafc;
            color: #475569;
            line-height: 1.65;
        }

        .faq-info .accordion-body p:last-child {
            margin-bottom: 0;
        }

        @media (max-width: 575px) {
            .faq-info .accordion-button {
                font-size: 1rem;
                padding: 1.15rem 1.2rem;
            }

            .faq-info .accordion-body {
                padding: 1.25rem 1.2rem 1.35rem;
            }
        }
    </style>
@endonce

<div class="faq-info">
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


