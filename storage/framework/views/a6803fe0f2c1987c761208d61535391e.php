
<section class="dwn-full-bleed dwn-features-summary py-10">
    <div class="container text-center">
        <h4 class="fs-1 fw-bold mb-3"><?php echo e($title ?? 'Explore our features'); ?></h4>
        <p class="text-muted mb-5"><?php echo e($subtitle ?? 'Build landing pages faster with powerful tools and integrations.'); ?></p>
        <?php
            $items = $items ?? [
                ['icon' => 'icon-1.svg', 'title' => 'Intuitive Builder', 'description' => 'Design responsive pages visually with drag & drop controls.'],
                ['icon' => 'icon-2.svg', 'title' => 'Real-time Collaboration', 'description' => 'Comment, iterate and approve updates alongside your team.'],
                ['icon' => 'icon-3.svg', 'title' => 'Integrations', 'description' => 'Connect to your favourite marketing automation and analytics tools.'],
                ['icon' => 'icon-4.svg', 'title' => 'Theme Shortcodes', 'description' => 'Compose complex layouts with reusable theme-specific shortcodes.'],
                ['icon' => 'icon-5.svg', 'title' => 'Global Widgets', 'description' => 'Reuse content blocks across multiple pages with one click.'],
                ['icon' => 'icon-6.svg', 'title' => 'Performance Insights', 'description' => 'Monitor conversions and metrics directly from your dashboard.'],
            ];
        ?>
        <div class="row gx-10 gy-7 mt-4 text-start">
            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-12 col-md-6 col-lg-4 d-flex flex-column justify-content-start" data-aos="fade-in">
                    <span class="f-w-8 d-block text-primary">
                        <img src="<?php echo e(theme_asset('assets/images/' . $item['icon'])); ?>" alt="<?php echo e($item['title']); ?>" class="img-fluid" style="max-width: 48px;">
                    </span>
                    <p class="fw-medium mb-1 mt-3 fs-5"><?php echo e($item['title']); ?></p>
                    <span class="text-muted fs-7"><?php echo e($item['description']); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <a href="<?php echo e($button_url ?? '#'); ?>" class="btn btn-primary d-table mx-auto mt-5 w-100 w-md-auto mt-lg-8 mb-3" role="button">
            <?php echo e($button_text ?? 'Start your trial'); ?>

        </a>
        <ul class="list-unstyled d-none d-md-flex align-items-center justify-content-center small text-muted mt-3 pt-1 fw-medium fs-9">
            <li class="me-4 d-flex align-items-center"><i class="ri-checkbox-circle-fill text-primary ri-lg me-1"></i> No credit card required</li>
            <li class="me-4 d-flex align-items-center"><i class="ri-checkbox-circle-fill text-primary ri-lg me-1"></i> Cancel anytime</li>
            <li class="me-4 d-flex align-items-center"><i class="ri-checkbox-circle-fill text-primary ri-lg me-1"></i> 30 day free trial</li>
        </ul>
    </div>
</section>
<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-features-summary.blade.php ENDPATH**/ ?>