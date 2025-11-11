<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// VeCarCMS Repositories
use App\Repositories\Interfaces\PageRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;

use App\Repositories\PageRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\PostRepository;
use App\Repositories\TagRepository;
use App\Repositories\AdminRepository;
use App\Repositories\RoleRepository;

// VeCarCMS Services
use App\Services\SlugService;
use App\Services\PageTemplateService;
use App\Services\PageService;
use App\Services\CategoryService;
use App\Services\PostService;
use App\Services\TagSelectorService;
use App\Services\Interfaces\CategoryServiceInterface;

// Models
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Page Repository & Services
        $this->app->singleton(PageRepositoryInterface::class, function ($app) {
            return new PageRepository();
        });
        
        $this->app->singleton(SlugService::class, function ($app) {
            return new SlugService();
        });
        
        $this->app->singleton(PageTemplateService::class, function ($app) {
            return new PageTemplateService();
        });
        
        $this->app->singleton(PageService::class, function ($app) {
            return new PageService($app->make(PageRepositoryInterface::class));
        });
        
        // Category Repository & Service
        $this->app->singleton(CategoryRepositoryInterface::class, function ($app) {
            return new CategoryRepository($app->make(Category::class));
        });
        
        $this->app->singleton(CategoryService::class, function ($app) {
            return new CategoryService($app->make(CategoryRepositoryInterface::class));
        });
        
        $this->app->singleton(CategoryServiceInterface::class, CategoryService::class);
        
        // Post Repository & Service
        $this->app->singleton(PostRepositoryInterface::class, function ($app) {
            return new PostRepository($app->make(Post::class));
        });
        
        $this->app->singleton(PostService::class, function ($app) {
            return new PostService($app->make(PostRepositoryInterface::class));
        });
        
        // Tag Service (Repository binding is in AppServiceProvider)
        $this->app->singleton(TagSelectorService::class, function ($app) {
            return new TagSelectorService($app->make(TagRepositoryInterface::class));
        });
        
        // Admin & Role Repositories
        $this->app->singleton(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->singleton(RoleRepositoryInterface::class, RoleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
