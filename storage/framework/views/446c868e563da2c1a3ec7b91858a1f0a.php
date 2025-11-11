<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">

        <!-- Logo-->
        <a class="navbar-brand d-flex align-items-center lh-1 me-10 transition-opacity opacity-75-hover" href="<?php echo e(url('/')); ?>">
            <?php if(theme_setting('custom_logo')): ?>
                <span class="d-flex align-items-center">
                    <img src="<?php echo e(theme_setting('custom_logo')); ?>" alt="<?php echo e(settings('site_name', 'VeCarCMS')); ?>" class="img-fluid" style="max-height: 40px;">
                    <span class="fw-bold text-body ms-2"><?php echo e(settings('site_name', 'VeCarCMS')); ?></span>
                </span>
            <?php else: ?>
                <span class="d-flex align-items-center">
                    <img src="<?php echo e(theme_asset('assets/images/logo.png')); ?>" alt="<?php echo e(settings('site_name', 'VeCarCMS')); ?>" class="img-fluid" style="max-height: 40px;">
                    <span class="fw-bold text-body ms-2"><?php echo e(settings('site_name', 'VeCarCMS')); ?></span>
                </span>
            <?php endif; ?>
        </a>
        <!-- / Logo-->

        <!-- Mobile Menu Btn-->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="ri-menu-line"></i>
        </button>
        <!-- /Mobile Menu Btn-->

        <div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarSupportedContent">
            
            <?php if(has_menu('primary')): ?>
                <?php echo menu('primary', ['class' => 'navbar-nav']); ?>

            <?php else: ?>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(url('/')); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(url('/blog')); ?>">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo e(url('/contact')); ?>">Contact</a>
                    </li>
                </ul>
            <?php endif; ?>

            <!-- CTA Buttons-->
            <div class="d-flex align-items-center">
                <?php if(auth()->guard()->check()): ?>
                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="d-inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-sm btn-primary">
                            Logout
                        </button>
                    </form>
                <?php else: ?>
                    <a class="btn btn-sm btn-link text-muted fw-medium d-none d-lg-block me-3" href="<?php echo e(route('login')); ?>">
                        Sign In
                    </a>
                    <?php if(settings('allow_registration', true)): ?>
                        <a class="btn btn-sm btn-primary" href="<?php echo e(route('register')); ?>">
                            Get Started
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <!-- / CTA Buttons-->
        </div>

    </div>
</nav>
<!-- / Navbar -->

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/partials/header.blade.php ENDPATH**/ ?>