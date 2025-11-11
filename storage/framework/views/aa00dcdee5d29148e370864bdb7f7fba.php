
<div class="dwn-accordion-shortcode">
    <div class="accordion" id="<?php echo e($id); ?>">
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button <?php echo e($index === 0 ? '' : 'collapsed'); ?>" 
                        type="button" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#collapse-<?php echo e($id); ?>-<?php echo e($index); ?>">
                    <?php echo e($item['title']); ?>

                </button>
            </h2>
            <div id="collapse-<?php echo e($id); ?>-<?php echo e($index); ?>" 
                 class="accordion-collapse collapse <?php echo e($index === 0 ? 'show' : ''); ?>" 
                 data-bs-parent="#<?php echo e($id); ?>">
                <div class="accordion-body">
                    <?php echo $item['content']; ?>

                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-accordion.blade.php ENDPATH**/ ?>