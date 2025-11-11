<div class="widget widget-search bg-white rounded-lg shadow-md p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-200">
        <?php echo e($title); ?>

    </h3>
    
    <form action="<?php echo e(route('search')); ?>" method="GET" class="relative">
        <div class="relative">
            <input type="text" 
                   name="q" 
                   value="<?php echo e(request('q')); ?>"
                   placeholder="<?php echo e($placeholder); ?>" 
                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
            
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            
            <?php if($showButton): ?>
                <button type="submit" 
                        class="absolute inset-y-0 right-0 px-4 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700 transition">
                    <i class="fas fa-arrow-right"></i>
                </button>
            <?php endif; ?>
        </div>
    </form>
</div>

<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/resources/views/components/widgets/search-box.blade.php ENDPATH**/ ?>