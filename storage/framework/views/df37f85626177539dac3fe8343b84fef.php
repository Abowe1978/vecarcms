<!-- Footer -->
<footer class="bg-dark pt-10 pb-5  ">
    <div class="container">

        <!-- Footer Widgets Row -->
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start pb-5">
            
            <!-- Footer Logo & Brand-->
            <div class="mb-5 mb-lg-0 me-lg-10">
                <a class="d-flex align-items-center text-decoration-none mb-4" href="<?php echo e(url('/')); ?>">
                    <?php if(theme_setting('custom_logo')): ?>
                        <span class="d-flex align-items-center">
                            <img src="<?php echo e(theme_setting('custom_logo')); ?>" alt="<?php echo e(settings('site_name', 'VeCarCMS')); ?>" class="img-fluid" style="max-height: 35px; filter: brightness(0) invert(1);">
                            <span class="fw-bold text-white ms-2"><?php echo e(settings('site_name', 'VeCarCMS')); ?></span>
                        </span>
                    <?php else: ?>
                        <span class="d-flex align-items-center">
                            <img src="<?php echo e(theme_asset('assets/images/logo.png')); ?>" alt="<?php echo e(settings('site_name', 'VeCarCMS')); ?>" class="img-fluid" style="max-height: 35px; filter: brightness(0) invert(1);">
                            <span class="fw-bold text-white ms-2"><?php echo e(settings('site_name', 'VeCarCMS')); ?></span>
                        </span>
                    <?php endif; ?>
                </a>
                <p class="text-white opacity-75" style="max-width: 300px;">
                    <?php echo e(settings('site_description', 'A modern Laravel CMS for building beautiful digital experiences')); ?>

                </p>

                <!-- Footer socials-->
                <ul class="list-unstyled d-flex align-items-center mt-4">
                    <?php if($facebook = settings('social_facebook')): ?>
                        <li class="me-4"><a href="<?php echo e($facebook); ?>" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-facebook-circle-line ri-lg"></i></a></li>
                    <?php endif; ?>
                    <?php if($twitter = settings('social_twitter')): ?>
                        <li class="me-4"><a href="<?php echo e($twitter); ?>" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-twitter-line ri-lg"></i></a></li>
                    <?php endif; ?>
                    <?php if($instagram = settings('social_instagram')): ?>
                        <li class="me-4"><a href="<?php echo e($instagram); ?>" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-instagram-line ri-lg"></i></a></li>
                    <?php endif; ?>
                    <?php if($linkedin = settings('social_linkedin')): ?>
                        <li class="me-4"><a href="<?php echo e($linkedin); ?>" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-linkedin-line ri-lg"></i></a></li>
                    <?php endif; ?>
                    <?php if($youtube = settings('social_youtube')): ?>
                        <li class="me-4"><a href="<?php echo e($youtube); ?>" target="_blank" class="text-white text-decoration-none opacity-50-hover transition-opacity"><i class="ri-youtube-line ri-lg"></i></a></li>
                    <?php endif; ?>
                </ul>
                <!-- /Footer socials-->
            </div>
            <!-- / Footer Logo & Brand-->


            <div class="d-flex flex-wrap flex-grow-1 justify-content-between">

                <!-- Footer Widget Column 1-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    <?php if(has_widgets('footer-1')): ?>
                        <?php echo widget_area('footer-1'); ?>

                    <?php else: ?>
                        <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Company</h6>
                        <ul class="list-unstyled footer-nav">
                            <li><a href="<?php echo e(url('/about')); ?>">About Us</a></li>
                            <li><a href="<?php echo e(url('/blog')); ?>">Blog</a></li>
                            <li><a href="<?php echo e(url('/contact')); ?>">Contact</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                <!-- /Footer Widget Column 1-->

                <!-- Footer Widget Column 2-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    <?php if(has_widgets('footer-2')): ?>
                        <?php echo widget_area('footer-2'); ?>

                    <?php else: ?>
                        <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Resources</h6>
                        <ul class="list-unstyled footer-nav">
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Support</a></li>
                            <li><a href="#">FAQ</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                <!-- /Footer Widget Column 2-->

                <!-- Footer Widget Column 3-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    <?php if(has_widgets('footer-3')): ?>
                        <?php echo widget_area('footer-3'); ?>

                    <?php else: ?>
                        <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Legal</h6>
                        <ul class="list-unstyled footer-nav">
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                        </ul>
                    <?php endif; ?>
                </div>
                <!-- /Footer Widget Column 3-->

                <!-- Footer Widget Column 4-->
                <div class="mb-4 mb-lg-0" style="min-width: 200px;">
                    <?php if(has_widgets('footer-4')): ?>
                        <?php echo widget_area('footer-4'); ?>

                    <?php else: ?>
                        <h6 class="text-uppercase fs-xs fw-bolder tracking-wider text-white opacity-50">Newsletter</h6>
                        <p class="text-white opacity-75 fs-sm">Stay up to date with our latest news and updates.</p>
                        <?php if (isset($component)) { $__componentOriginal39143693f4ee58c7bbd242bf6d69ee5c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal39143693f4ee58c7bbd242bf6d69ee5c = $attributes; } ?>
<?php $component = App\View\Components\Widgets\Newsletter::resolve(['title' => ''] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widgets.newsletter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Widgets\Newsletter::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal39143693f4ee58c7bbd242bf6d69ee5c)): ?>
<?php $attributes = $__attributesOriginal39143693f4ee58c7bbd242bf6d69ee5c; ?>
<?php unset($__attributesOriginal39143693f4ee58c7bbd242bf6d69ee5c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal39143693f4ee58c7bbd242bf6d69ee5c)): ?>
<?php $component = $__componentOriginal39143693f4ee58c7bbd242bf6d69ee5c; ?>
<?php unset($__componentOriginal39143693f4ee58c7bbd242bf6d69ee5c); ?>
<?php endif; ?>
                    <?php endif; ?>
                </div>
                <!-- /Footer Widget Column 4-->

            </div>

        </div>
        <!-- / Footer Widgets Row -->

        <!-- Footer Copyright-->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-5 border-top border-white-10">
            <small class="text-muted">
                &copy; <?php echo e(date('Y')); ?> <?php echo e(settings('site_name', 'VeCarCMS')); ?>. All rights reserved. Powered by <a href="https://vecar cms.com" class="text-white text-decoration-none">VeCarCMS</a>.
            </small>

            
            <?php if(has_menu('footer')): ?>
                <?php echo menu('footer', ['class' => 'list-unstyled d-flex mt-3 mt-md-0 mb-0']); ?>

            <?php else: ?>
                <ul class="list-unstyled d-flex mt-3 mt-md-0 mb-0">
                    <li class="ms-5"><a href="<?php echo e(url('/privacy')); ?>" class="text-muted text-decoration-none fs-sm opacity-75-hover transition-opacity">Privacy</a></li>
                    <li class="ms-5"><a href="<?php echo e(url('/terms')); ?>" class="text-muted text-decoration-none fs-sm opacity-75-hover transition-opacity">Terms</a></li>
                    <li class="ms-5"><a href="<?php echo e(url('/sitemap.xml')); ?>" class="text-muted text-decoration-none fs-sm opacity-75-hover transition-opacity">Sitemap</a></li>
                </ul>
            <?php endif; ?>
        </div>
        <!-- / Footer Copyright-->

    </div>
</footer>
<!-- / Footer -->

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/partials/footer.blade.php ENDPATH**/ ?>