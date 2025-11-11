
<section class="dwn-timeline-shortcode py-10">
    <div class="container">
        <?php if($style === 'vertical'): ?>
        <div class="row">
            <div class="col-12 col-lg-10 mx-auto">
                <?php $__currentLoopData = $events; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="d-flex mb-5">
                    <div class="flex-shrink-0 me-4 text-center" style="width: 80px;">
                        <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold" style="width: 60px; height: 60px;">
                            <?php echo e($event['year']); ?>

                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-2"><?php echo e($event['title']); ?></h5>
                                <p class="text-muted mb-0"><?php echo e($event['description']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-timeline.blade.php ENDPATH**/ ?>