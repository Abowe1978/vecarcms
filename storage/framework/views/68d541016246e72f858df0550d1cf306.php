<div class="widget widget-recent-posts bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
        <?php echo e($title); ?>

    </h3>
    
    <?php if($posts->count() > 0): ?>
        <ul class="space-y-4">
            <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="group">
                    <article class="flex gap-3">
                        <?php if($showThumbnail && $post->featured_image): ?>
                            <a href="<?php echo e(url('/' . $post->slug)); ?>" class="flex-shrink-0">
                                <img src="<?php echo e($post->getImageUrl()); ?>" 
                                     alt="<?php echo e($post->title); ?>" 
                                     class="w-16 h-16 object-cover rounded-md group-hover:opacity-80 transition">
                            </a>
                        <?php endif; ?>
                        
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-sm">
                                <a href="<?php echo e(url('/' . $post->slug)); ?>" 
                                   class="text-gray-900 hover:text-purple-600 transition">
                                    <?php echo e($post->title); ?>

                                </a>
                            </h4>
                            
                            <?php if($showDate && $post->published_at): ?>
                                <time class="text-xs text-gray-500" datetime="<?php echo e($post->published_at); ?>">
                                    <?php echo e($post->published_at->format('M d, Y')); ?>

                                </time>
                            <?php endif; ?>
                        </div>
                    </article>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500 text-sm">No recent posts available.</p>
    <?php endif; ?>
</div>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/resources/views/components/widgets/recent-posts.blade.php ENDPATH**/ ?>