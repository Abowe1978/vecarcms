
<?php if($posts && $posts->count() > 0): ?>
<section class="dwn-full-bleed py-10 py-lg-15 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-end mb-10">
            <div>
                <h2 class="display-5 fw-bold mb-3" data-aos="fade-up">Latest Articles</h2>
                <p class="lead text-muted" data-aos="fade-up" data-aos-delay="100">Insights, stories, and updates from our team</p>
            </div>
            <a href="<?php echo e(url('/blog')); ?>" class="btn btn-link text-primary fw-medium" data-aos="fade-up">
                View All Posts <i class="ri-arrow-right-line ms-2"></i>
            </a>
        </div>

        <div class="row g-5">
            <?php $__currentLoopData = $posts->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="<?php echo e($index * 100); ?>">
                <article class="card border-0 shadow-sm h-100 hover-lift transition-all">
                    <?php if($post->featured_image): ?>
                    <a href="<?php echo e(url('/' . $post->slug)); ?>">
                        <img src="<?php echo e($post->getImageUrl()); ?>" alt="<?php echo e($post->title); ?>" class="card-img-top" style="height: 240px; object-fit: cover;">
                    </a>
                    <?php endif; ?>
                    <div class="card-body">
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
                        </div>
                        <h5 class="card-title fw-bold mb-3">
                            <a href="<?php echo e(url('/' . $post->slug)); ?>" class="text-dark text-decoration-none hover-primary">
                                <?php echo e($post->title); ?>

                            </a>
                        </h5>
                        <?php if($post->excerpt): ?>
                        <p class="card-text text-muted"><?php echo e(str($post->excerpt)->limit(120)); ?></p>
                        <?php endif; ?>
                        <a href="<?php echo e(url('/' . $post->slug)); ?>" class="btn btn-link text-primary p-0 fw-medium">
                            Read More <i class="ri-arrow-right-line ms-1"></i>
                        </a>
                    </div>
                </article>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/shortcodes/latest-posts.blade.php ENDPATH**/ ?>