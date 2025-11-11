<?php
    $defaultImages = [
        'about-1.jpeg',
        'about-2.jpeg',
        'about-3.jpeg',
        'about-4.jpeg',
        'about-5.jpeg',
        'about-6.jpeg',
    ];

    $images = $images && is_array($images) && count($images)
        ? array_values($images)
        : $defaultImages;

    $images = array_pad($images, 6, null);

    $resolveImage = function ($path, $fallback) {
        return filter_var($path, FILTER_VALIDATE_URL)
            ? $path
            : theme_asset('assets/images/' . ($path ?: $fallback));
    };
?>

<section class="py-7">
    <div class="container position-relative z-index-20">
        <div class="row g-3">
            <div class="col-12 col-lg-6 d-none d-lg-block">
                <div class="row g-3">
                    <div class="col-12 col-md-6">
                        <picture>
                            <img class="img-fluid rounded shadow-sm" src="<?php echo e($resolveImage($images[0], 'about-1.jpeg')); ?>" alt="Team workspace">
                        </picture>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <picture>
                                    <img class="img-fluid rounded shadow-sm" src="<?php echo e($resolveImage($images[1], 'about-2.jpeg')); ?>" alt="Brainstorming session">
                                </picture>
                            </div>
                            <div class="col-12">
                                <picture>
                                    <img class="img-fluid rounded shadow-sm" src="<?php echo e($resolveImage($images[2], 'about-3.jpeg')); ?>" alt="Team collaboration">
                                </picture>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-6">
                <div class="row g-3">
                    <div class="col-12 col-md-6 d-none d-lg-block">
                        <picture>
                            <img class="img-fluid rounded shadow-sm" src="<?php echo e($resolveImage($images[3], 'about-4.jpeg')); ?>" alt="Team meeting">
                        </picture>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="row gy-3">
                            <div class="col-12">
                                <picture>
                                    <img class="img-fluid rounded shadow-sm" src="<?php echo e($resolveImage($images[4], 'about-5.jpeg')); ?>" alt="Design review">
                                </picture>
                            </div>
                            <div class="col-12">
                                <picture>
                                    <img class="img-fluid rounded shadow-sm" src="<?php echo e($resolveImage($images[5], 'about-6.jpeg')); ?>" alt="Team planning">
                                </picture>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/dwn-about-gallery.blade.php ENDPATH**/ ?>