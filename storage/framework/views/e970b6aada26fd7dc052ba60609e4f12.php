<?php $__env->startSection('title', $page->title . ' - ' . settings('site_name', 'VeCarCMS')); ?>
<?php $__env->startSection('meta_description', $page->meta_description ?? str($page->content)->limit(160)); ?>

<?php $__env->startSection('content'); ?>

<?php if($page->use_page_builder && $page->page_builder_content): ?>
    
    <?php echo do_shortcode(render_page_builder_content($page->page_builder_content)); ?>

<?php else: ?>
    
    
    
    <?php if(!$page->isHomepage()): ?>
    <section class="py-4 bg-light border-bottom">
        <div class="container">
            <?php echo render_breadcrumbs($page); ?>

        </div>
    </section>
    <?php endif; ?>

    
    <article class="py-10 py-lg-12">
        <div class="container">
            
            <?php if(!$page->isHomepage() && ($page->show_title ?? true)): ?>
            <header class="mb-8 text-center">
                <h1 class="display-4 fw-bold mb-4"><?php echo e($page->title); ?></h1>
                
                <?php if($page->excerpt): ?>
                <p class="lead text-muted"><?php echo e($page->excerpt); ?></p>
                <?php endif; ?>
            </header>
            <?php endif; ?>

            
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="prose prose-lg max-w-full">
                        <?php echo do_shortcode($page->content); ?>

                    </div>
                </div>
            </div>
        </div>
    </article>
<?php endif; ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('themes.dwntheme::layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/views/page-full-width.blade.php ENDPATH**/ ?>