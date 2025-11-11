<section class="py-4">
    <div class="container">
        <div class="col-12 col-md-8 col-lg-6 mx-auto text-center border-bottom pb-5">
            <div class="my-4 d-flex d-md-none flex-column gap-3">
                <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <span class="display-5 fw-bold text-primary d-block"><?php echo e($stat['value'] ?? ''); ?></span>
                        <span class="d-block fs-9 fw-bolder tracking-wide text-uppercase text-muted"><?php echo e($stat['label'] ?? ''); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="my-5 d-none d-md-flex align-items-start justify-content-between">
                <?php $__currentLoopData = $stats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <span class="display-3 fw-bold text-primary d-block"><?php echo e($stat['value'] ?? ''); ?></span>
                        <span class="d-block fs-9 fw-bolder tracking-wide text-uppercase text-muted"><?php echo e($stat['label'] ?? ''); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-about-stats.blade.php ENDPATH**/ ?>