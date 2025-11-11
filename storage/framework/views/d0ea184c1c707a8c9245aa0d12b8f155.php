<?php $__env->startSection('title', settings('site_name', 'VeCarCMS') . ' - ' . settings('site_tagline', 'A Powerful CMS')); ?>

<?php $__env->startSection('content'); ?>


<?php echo do_shortcode($page->content ?? ''); ?>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('themes.dwntheme::layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/views/home.blade.php ENDPATH**/ ?>