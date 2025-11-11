
<section class="dwn-full-bleed py-10 py-lg-15 bg-<?php echo e($background ?? 'primary'); ?> text-white">
    <div class="container text-center">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <h2 class="display-5 fw-bold mb-4" data-aos="fade-up">
                    <?php echo e($title ?? 'Ready to Get Started?'); ?>

                </h2>
                
                <?php if(isset($description) && $description): ?>
                <p class="lead mb-5 opacity-90" data-aos="fade-up" data-aos-delay="100">
                    <?php echo e($description); ?>

                </p>
                <?php endif; ?>
                
                <?php if(!empty($content)): ?>
                <div class="mb-5" data-aos="fade-up" data-aos-delay="150">
                    <?php echo $content; ?>

                </div>
                <?php endif; ?>
                
                <?php if((isset($button1_text) && $button1_text) || (isset($button2_text) && $button2_text)): ?>
                <div class="d-flex flex-wrap gap-3 justify-content-center" data-aos="fade-up" data-aos-delay="200">
                    <?php if(isset($button1_text) && $button1_text): ?>
                    <a href="<?php echo e($button1_url ?? '#'); ?>" class="btn btn-light btn-lg">
                        <?php echo e($button1_text); ?>

                        <i class="ri-arrow-right-line ms-2"></i>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(isset($button2_text) && $button2_text): ?>
                    <a href="<?php echo e($button2_url ?? '#'); ?>" class="btn btn-outline-light btn-lg">
                        <?php echo e($button2_text); ?>

                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/cta.blade.php ENDPATH**/ ?>