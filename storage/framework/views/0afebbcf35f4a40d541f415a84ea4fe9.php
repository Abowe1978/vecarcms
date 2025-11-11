<?php if(auth()->guard()->check()): ?>

<div class="admin-bar">
    <div class="admin-bar-container">
        <div class="admin-bar-flex">
            
            <div class="admin-bar-left">
                
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="admin-bar-logo">
                    <i class="ri-dashboard-line"></i>
                    <span>VeCarCMS</span>
                </a>

                
                <div class="admin-bar-dropdown dropdown">
                    <a class="admin-bar-item dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="true">
                        <i class="ri-add-line"></i>
                        <span>New</span>
                    </a>
                    <ul class="dropdown-menu admin-bar-bootstrap-dropdown">
                        <li><a class="dropdown-item admin-bar-dropdown-item" href="<?php echo e(route('admin.posts.create')); ?>">
                            <i class="ri-article-line"></i>
                            <span>New Post</span>
                        </a></li>
                        <li><a class="dropdown-item admin-bar-dropdown-item" href="<?php echo e(route('admin.pages.create')); ?>">
                            <i class="ri-file-text-line"></i>
                            <span>New Page</span>
                        </a></li>
                        <li><hr class="dropdown-divider admin-bar-dropdown-divider"></li>
                        <li><a class="dropdown-item admin-bar-dropdown-item" href="<?php echo e(route('admin.posts.index')); ?>">
                            <i class="ri-list-check"></i>
                            <span>All Posts</span>
                        </a></li>
                        <li><a class="dropdown-item admin-bar-dropdown-item" href="<?php echo e(route('admin.pages.index')); ?>">
                            <i class="ri-pages-line"></i>
                            <span>All Pages</span>
                        </a></li>
                        <li><a class="dropdown-item admin-bar-dropdown-item" href="<?php echo e(route('admin.media.index')); ?>">
                            <i class="ri-image-line"></i>
                            <span>Media Library</span>
                        </a></li>
                    </ul>
                </div>

                
                <?php if(isset($page) && $page): ?>
                    <a href="<?php echo e(route('admin.pages.edit', $page)); ?>" class="admin-bar-item admin-bar-edit">
                        <i class="ri-edit-line"></i>
                        <span>Edit Page</span>
                    </a>
                    
                    <?php if($page->use_page_builder): ?>
                        <a href="<?php echo e(route('admin.pages.edit', $page)); ?>#page-builder" 
                           class="admin-bar-item" 
                           style="background: #9333ea;">
                            <i class="ri-layout-4-line"></i>
                            <span>Edit with Builder</span>
                        </a>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if(isset($post) && $post): ?>
                    <a href="<?php echo e(route('admin.posts.edit', $post)); ?>" class="admin-bar-item admin-bar-edit">
                        <i class="ri-edit-line"></i>
                        <span>Edit Post</span>
                    </a>
                <?php endif; ?>
            </div>

            
            <div class="admin-bar-right">
                
                <form method="POST" action="<?php echo e(route('admin.clear-cache')); ?>" style="margin: 0; display: inline;">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="admin-bar-item" style="border: none; cursor: pointer;" title="Clear Cache">
                        <i class="ri-delete-bin-line"></i>
                        <span class="admin-bar-mobile-hide">Clear Cache</span>
                    </button>
                </form>

                
                <a href="<?php echo e(url('/')); ?>" class="admin-bar-item admin-bar-mobile-hide" target="_blank">
                    <i class="ri-eye-line"></i>
                    <span>View Site</span>
                </a>

                
                <div class="admin-bar-dropdown dropdown">
                    <a class="admin-bar-item dropdown-toggle" href="#" data-bs-toggle="dropdown" data-bs-auto-close="true">
                        <i class="ri-user-line"></i>
                        <span><?php echo e(auth()->user()->name); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end admin-bar-bootstrap-dropdown">
                        <li><a class="dropdown-item admin-bar-dropdown-item" href="<?php echo e(route('admin.profile.show')); ?>">
                            <i class="ri-user-settings-line"></i>
                            <span>Profile</span>
                        </a></li>
                        <li><hr class="dropdown-divider admin-bar-dropdown-divider"></li>
                        <li>
                            <form method="POST" action="<?php echo e(route('logout')); ?>" style="margin: 0;">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="dropdown-item admin-bar-dropdown-item" style="width: 100%; text-align: left; border: none; background: none; padding: 10px 16px;">
                                    <i class="ri-logout-box-line"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


<link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">


<style>
    body {
        padding-top: 46px !important;
    }
    
    /* Override Bootstrap dropdown styles to match admin bar theme */
    .admin-bar-bootstrap-dropdown {
        background: #32373c !important;
        border: none !important;
        box-shadow: 0 3px 5px rgba(0,0,0,0.2) !important;
        margin-top: 0 !important;
        min-width: 200px !important;
        padding: 8px 0 !important;
    }
    
    .admin-bar-bootstrap-dropdown .admin-bar-dropdown-item {
        display: flex !important;
        align-items: center !important;
        gap: 10px !important;
        padding: 10px 16px !important;
        color: #eee !important;
        text-decoration: none !important;
        transition: all 0.2s !important;
        white-space: nowrap !important;
        background: transparent !important;
        border: none !important;
    }
    
    .admin-bar-bootstrap-dropdown .admin-bar-dropdown-item:hover,
    .admin-bar-bootstrap-dropdown .admin-bar-dropdown-item:focus {
        background: #0073aa !important;
        color: #fff !important;
    }
    
    .admin-bar-bootstrap-dropdown .admin-bar-dropdown-item i {
        font-size: 16px !important;
        width: 20px !important;
        text-align: center !important;
    }
    
    .admin-bar-bootstrap-dropdown .admin-bar-dropdown-divider {
        height: 1px !important;
        background: rgba(255,255,255,0.1) !important;
        margin: 4px 0 !important;
        border: none !important;
    }
    
    /* Remove Bootstrap dropdown arrow */
    .admin-bar .dropdown-toggle::after {
        display: none !important;
    }
</style>
<?php endif; ?>
<?php /**PATH /Users/carminedamore/Development/gruppobonifacio/content/themes/dwntheme/partials/admin-bar.blade.php ENDPATH**/ ?>