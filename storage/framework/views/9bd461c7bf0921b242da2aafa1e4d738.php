
<section class="dwn-pricing-shortcode py-10">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-6 col-lg-<?php echo e(12 / $columns); ?>">
                <div class="card border-0 shadow-sm h-100 <?php echo e(isset($plan['featured']) && $plan['featured'] ? 'border-primary border-3' : ''); ?>">
                    <?php if(isset($plan['featured']) && $plan['featured']): ?>
                    <div class="card-header bg-primary text-white text-center py-2">
                        <small class="fw-bold">MOST POPULAR</small>
                    </div>
                    <?php endif; ?>
                    <div class="card-body p-5 text-center">
                        <h5 class="text-uppercase text-muted mb-4"><?php echo e($plan['name']); ?></h5>
                        <div class="mb-4">
                            <span class="display-4 fw-bold">$<?php echo e($plan['price']); ?></span>
                            <span class="text-muted">/<?php echo e($plan['period']); ?></span>
                        </div>
                        <ul class="list-unstyled mb-5">
                            <?php $__currentLoopData = $plan['features']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-3">
                                <i class="ri-check-line text-success me-2"></i><?php echo e($feature); ?>

                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <a href="<?php echo e($plan['button_url']); ?>" class="btn btn-<?php echo e(isset($plan['featured']) && $plan['featured'] ? 'primary' : 'outline-primary'); ?> w-100">
                            <?php echo e($plan['button_text']); ?>

                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-pricing.blade.php ENDPATH**/ ?>