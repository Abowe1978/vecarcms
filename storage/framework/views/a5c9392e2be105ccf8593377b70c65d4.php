
<section class="dwn-team-shortcode py-10">
    <div class="container">
        <div class="row g-5">
            <?php $__currentLoopData = $team; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-6 col-lg-<?php echo e(12 / $columns); ?>">
                <div class="card border-0 shadow-sm text-center h-100">
                    <div class="card-body p-5">
                        <img src="<?php echo e($member['avatar']); ?>" class="rounded-circle mb-4" width="128" height="128" alt="<?php echo e($member['name']); ?>">
                        <h5 class="fw-bold mb-1"><?php echo e($member['name']); ?></h5>
                        <p class="text-muted mb-3"><?php echo e($member['role']); ?></p>
                        <?php if(isset($member['bio'])): ?>
                        <p class="small text-muted mb-4"><?php echo e($member['bio']); ?></p>
                        <?php endif; ?>
                        <?php if(isset($member['social'])): ?>
                        <div class="d-flex justify-content-center gap-2">
                            <?php $__currentLoopData = $member['social']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $platform => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e($url); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="ri-<?php echo e($platform); ?>-line"></i>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-team.blade.php ENDPATH**/ ?>