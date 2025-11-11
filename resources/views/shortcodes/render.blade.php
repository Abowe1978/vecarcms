@php
    $shortcodeService = app(\App\Services\ShortcodeService::class);
    $payload = $shortcodeService->resolveDeferred($__shortcode_id ?? null);
@endphp

@if($payload && !empty($payload['view']))
    @include($payload['view'], $payload['data'] ?? [])
@endif

