<?php $__env->startSection('title', (isset($blogPage) ? $blogPage->title : 'Blog') . ' - ' . settings('site_name', 'VeCarCMS')); ?>

<?php $__env->startSection('content'); ?>


<section class="py-4 bg-light border-bottom">
    <div class="container">
        <?php echo render_breadcrumbs(); ?>

    </div>
</section>


<section class="py-10 py-lg-12 bg-light">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-12 col-lg-8">
                <h1 class="display-4 fw-bold mb-4">
                    <?php echo e(isset($blogPage) ? $blogPage->title : 'Blog'); ?>

                </h1>
                <?php if(isset($blogPage) && $blogPage->content): ?>
                    <div class="lead text-muted">
                        <?php echo $blogPage->content; ?>

                    </div>
                <?php else: ?>
                    <p class="lead text-muted">Insights, stories, and updates from our team</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>


<section class="py-10">
    <div class="container">
        <div class="row g-8">
            
            
            <div class="col-12 col-lg-8">
                <?php if($posts->count() > 0): ?>
                    <div class="row g-5">
                        <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12">
                            <article class="card border-0 shadow-sm hover-lift transition-all">
                                <div class="row g-0">
                                    <?php if($post->featured_image): ?>
                                    <div class="col-md-4">
                                        <a href="<?php echo e(url('/' . $post->slug)); ?>">
                                            <img src="<?php echo e($post->getImageUrl()); ?>" alt="<?php echo e($post->title); ?>" class="img-fluid h-100 w-100" style="object-fit: cover;">
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    <div class="<?php echo e($post->featured_image ? 'col-md-8' : 'col-12'); ?>">
                                        <div class="card-body p-5">
                                            <div class="d-flex align-items-center text-sm text-muted mb-3">
                                                <time datetime="<?php echo e($post->published_at); ?>">
                                                    <?php echo e($post->published_at->format('M d, Y')); ?>

                                                </time>
                                                <?php if($post->categories->count() > 0): ?>
                                                    <span class="mx-2">•</span>
                                                    <a href="<?php echo e(get_term_link('category', $post->categories->first())); ?>" class="text-primary text-decoration-none">
                                                        <?php echo e($post->categories->first()->name); ?>

                                                    </a>
                                                <?php endif; ?>
                                                <span class="mx-2">•</span>
                                                <span><?php echo e($post->author->name); ?></span>
                                            </div>
                                            <h3 class="card-title fw-bold mb-3">
                                                <a href="<?php echo e(url('/' . $post->slug)); ?>" class="text-dark text-decoration-none hover-primary">
                                                    <?php echo e($post->title); ?>

                                                </a>
                                            </h3>
                                            <?php if($post->excerpt): ?>
                                            <p class="card-text text-muted mb-4"><?php echo e(str($post->excerpt)->limit(200)); ?></p>
                                            <?php endif; ?>
                                            
                                            
                                            <?php if($post->tags->count() > 0): ?>
                                            <div class="d-flex flex-wrap gap-2 mb-4">
                                                <?php $__currentLoopData = $post->tags->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a href="<?php echo e(get_term_link('tag', $tag)); ?>" class="badge bg-light text-muted text-decoration-none">
                                                        <?php echo e($tag->name); ?>

                                                    </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                            <?php endif; ?>
                                            
                                            <a href="<?php echo e(url('/' . $post->slug)); ?>" class="btn btn-link text-primary p-0 fw-medium">
                                                Read More <i class="ri-arrow-right-line ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="mt-8">
                        <?php echo e($posts->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-10">
                        <div class="f-w-20 text-muted mb-4 mx-auto">
                            <i class="ri-article-line"></i>
                        </div>
                        <h3 class="fw-bold mb-2">No posts found</h3>
                        <p class="text-muted">Check back later for new content.</p>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="col-12 col-lg-4">
                <div class="position-sticky" style="top: 2rem;">
                    <?php if(has_widgets('sidebar-blog')): ?>
                        <?php echo widget_area('sidebar-blog'); ?>

                    <?php else: ?>
                        
                        <?php if (isset($component)) { $__componentOriginal2fde69a23dd0ee83a921f51c1d8dc4f3 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2fde69a23dd0ee83a921f51c1d8dc4f3 = $attributes; } ?>
<?php $component = App\View\Components\Widgets\SearchBox::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widgets.search-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Widgets\SearchBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2fde69a23dd0ee83a921f51c1d8dc4f3)): ?>
<?php $attributes = $__attributesOriginal2fde69a23dd0ee83a921f51c1d8dc4f3; ?>
<?php unset($__attributesOriginal2fde69a23dd0ee83a921f51c1d8dc4f3); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2fde69a23dd0ee83a921f51c1d8dc4f3)): ?>
<?php $component = $__componentOriginal2fde69a23dd0ee83a921f51c1d8dc4f3; ?>
<?php unset($__componentOriginal2fde69a23dd0ee83a921f51c1d8dc4f3); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal2b3a2d8351cecff0c3dbde674f23f375 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal2b3a2d8351cecff0c3dbde674f23f375 = $attributes; } ?>
<?php $component = App\View\Components\Widgets\CategoriesList::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widgets.categories-list'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Widgets\CategoriesList::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal2b3a2d8351cecff0c3dbde674f23f375)): ?>
<?php $attributes = $__attributesOriginal2b3a2d8351cecff0c3dbde674f23f375; ?>
<?php unset($__attributesOriginal2b3a2d8351cecff0c3dbde674f23f375); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal2b3a2d8351cecff0c3dbde674f23f375)): ?>
<?php $component = $__componentOriginal2b3a2d8351cecff0c3dbde674f23f375; ?>
<?php unset($__componentOriginal2b3a2d8351cecff0c3dbde674f23f375); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginale2c682ef274c330f39df2cfe360c3cf8 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginale2c682ef274c330f39df2cfe360c3cf8 = $attributes; } ?>
<?php $component = App\View\Components\Widgets\TagCloud::resolve(['limit' => 20] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widgets.tag-cloud'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Widgets\TagCloud::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginale2c682ef274c330f39df2cfe360c3cf8)): ?>
<?php $attributes = $__attributesOriginale2c682ef274c330f39df2cfe360c3cf8; ?>
<?php unset($__attributesOriginale2c682ef274c330f39df2cfe360c3cf8); ?>
<?php endif; ?>
<?php if (isset($__componentOriginale2c682ef274c330f39df2cfe360c3cf8)): ?>
<?php $component = $__componentOriginale2c682ef274c330f39df2cfe360c3cf8; ?>
<?php unset($__componentOriginale2c682ef274c330f39df2cfe360c3cf8); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal427ab6b161c0038920146eabdb57d2e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal427ab6b161c0038920146eabdb57d2e9 = $attributes; } ?>
<?php $component = App\View\Components\Widgets\RecentPosts::resolve(['limit' => 5] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('widgets.recent-posts'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\Widgets\RecentPosts::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mt-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal427ab6b161c0038920146eabdb57d2e9)): ?>
<?php $attributes = $__attributesOriginal427ab6b161c0038920146eabdb57d2e9; ?>
<?php unset($__attributesOriginal427ab6b161c0038920146eabdb57d2e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal427ab6b161c0038920146eabdb57d2e9)): ?>
<?php $component = $__componentOriginal427ab6b161c0038920146eabdb57d2e9; ?>
<?php unset($__componentOriginal427ab6b161c0038920146eabdb57d2e9); ?>
<?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('themes.dwntheme::layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/views/blog.blade.php ENDPATH**/ ?>