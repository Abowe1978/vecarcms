<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<!-- Head -->
<head>
    <!-- Page Meta Tags-->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', settings('site_description', '')); ?>">
    <meta name="author" content="<?php echo $__env->yieldContent('meta_author', settings('site_name', '')); ?>">
    <meta name="keywords" content="<?php echo $__env->yieldContent('meta_keywords', ''); ?>">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    
    <meta property="og:title" content="<?php echo $__env->yieldContent('title', settings('site_name', 'VeCarCMS')); ?>">
    <meta property="og:description" content="<?php echo $__env->yieldContent('meta_description', settings('site_description', '')); ?>">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:type" content="website">
    <?php echo $__env->yieldPushContent('og_image'); ?>

    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo $__env->yieldContent('title', settings('site_name', 'VeCarCMS')); ?>">
    <meta name="twitter:description" content="<?php echo $__env->yieldContent('meta_description', settings('site_description', '')); ?>">

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(theme_asset('assets/images/logo.png')); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo e(theme_asset('assets/images/logo.png')); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo e(theme_asset('assets/images/logo.png')); ?>">
    <link rel="mask-icon" href="<?php echo e(theme_asset('assets/images/logo.png')); ?>" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    
    <!-- Vendor CSS -->
    <link rel="stylesheet" href="<?php echo e(theme_asset('assets/css/libs.bundle.css')); ?>" />

    <!-- Main CSS -->
    <link rel="stylesheet" href="<?php echo e(theme_asset('assets/css/theme.bundle.css')); ?>" />

    <style>
        body {
            overflow-x: hidden;
        }

        .dwn-full-bleed {
            position: relative;
            width: 100vw;
            left: 50%;
            right: 50%;
            margin-left: -50vw;
            margin-right: -50vw;
        }

        main > section,
        main > .dwn-full-bleed {
            padding-top: 4rem;
            padding-bottom: 4rem;
        }
    </style>

    
    <?php if(auth()->guard()->check()): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/admin-bar.css']); ?>
    <?php endif; ?>

    
    <?php if($customCss = theme_setting('custom_css')): ?>
        <style><?php echo $customCss; ?></style>
    <?php endif; ?>

    <!-- Fix for custom scrollbar if JS is disabled-->
    <noscript>
        <style>
          /**
          * Reinstate scrolling for non-JS clients
          */
          .simplebar-content-wrapper {
            overflow: auto;
          }
        </style>
    </noscript>

    <!-- Page Title -->
    <title><?php echo $__env->yieldContent('title', settings('site_name', 'VeCarCMS')); ?></title>

    
    <?php echo $__env->yieldPushContent('head'); ?>
    
</head>
<body class="<?php echo $__env->yieldContent('body_class', ''); ?> <?php if(auth()->guard()->check()): ?> admin-logged-in <?php endif; ?>">

    
    <?php echo $__env->make('themes.dwntheme::partials.admin-bar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('themes.dwntheme::partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->make('themes.dwntheme::partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <!-- Vendor JS -->
    <script src="<?php echo e(theme_asset('assets/js/vendor.bundle.js')); ?>"></script>

    <!-- Theme JS -->
    <script src="<?php echo e(theme_asset('assets/js/theme.bundle.js')); ?>"></script>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/layouts/main.blade.php ENDPATH**/ ?>