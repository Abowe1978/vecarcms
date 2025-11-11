<?php
    $shortcodeService = app(\App\Services\ShortcodeService::class);
    $payload = $shortcodeService->resolveDeferred($__shortcode_id ?? null);
?>

<?php if($payload && !empty($payload['view'])): ?>
    <?php echo $__env->make($payload['view'], $payload['data'] ?? [], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endif; ?>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/resources/views/shortcodes/render.blade.php ENDPATH**/ ?>