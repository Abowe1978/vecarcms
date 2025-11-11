<?php
    $imageSource = $image ?? 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=1200&q=80';
    $imageSource = filter_var($imageSource, FILTER_VALIDATE_URL)
        ? $imageSource
        : theme_asset('assets/images/' . ltrim($imageSource, '/'));
?>

<section class="py-6">
    <div class="container">
        <div class="row gx-8 align-items-center <?php echo e($reverse ? 'flex-row-reverse' : ''); ?>">
            <div class="col-12 col-lg-6">
                <?php if(!empty($eyebrow)): ?>
                    <p class="mb-3 small fw-bolder tracking-wider text-uppercase text-primary"><?php echo e($eyebrow); ?></p>
                <?php endif; ?>
                <?php if(!empty($title)): ?>
                    <h2 class="display-5 fw-bold mb-6"><?php echo e($title); ?></h2>
                <?php endif; ?>
                <?php if(!empty($content)): ?>
                    <?php echo $content; ?>

                <?php endif; ?>
            </div>
            <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                <picture>
                    <img class="img-fluid rounded shadow-sm" src="<?php echo e($imageSource); ?>" alt="<?php echo e($title ?? 'About story image'); ?>">
                </picture>
            </div>
        </div>
    </div>
</section>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-about-story.blade.php ENDPATH**/ ?>