<div class="widget widget-tag-cloud bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
        <?php echo e($title); ?>

    </h3>
    
    <?php if($tags->count() > 0): ?>
        <div class="flex flex-wrap gap-2">
            <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(get_term_link('tag', $tag)); ?>" 
                   class="<?php echo e($getFontSize($tag)); ?> px-3 py-1 bg-gray-100 hover:bg-purple-100 text-gray-700 hover:text-purple-700 rounded-full transition-all duration-200 hover:shadow-md">
                    <?php echo e($tag->name); ?>

                    <?php if(isset($tag->posts_count) && $tag->posts_count > 0): ?>
                        <span class="text-xs opacity-60">(<?php echo e($tag->posts_count); ?>)</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <p class="text-gray-500 text-sm">No tags available.</p>
    <?php endif; ?>
</div>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/resources/views/components/widgets/tag-cloud.blade.php ENDPATH**/ ?>