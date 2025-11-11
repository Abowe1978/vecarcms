<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

// VeCarCMS Services
use App\Services\Interfaces\PostServiceInterface;
use App\Services\Interfaces\MediaServiceInterface;
use App\Services\Interfaces\CategoryServiceInterface;
use App\Services\Interfaces\TagServiceInterface;
use App\Services\Interfaces\AdminServiceInterface;
use App\Services\Interfaces\RoleServiceInterface;
use App\Services\Interfaces\IntegrationServiceInterface;

use App\Services\PostService;
use App\Services\MediaService;
use App\Services\CategoryService;
use App\Services\TagService;
use App\Services\AdminService;
use App\Services\RoleService;
use App\Services\IntegrationService;
use App\Services\Admin\DashboardService;

// VeCarCMS Repositories
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\Interfaces\MediaRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\TagRepositoryInterface;
use App\Repositories\Interfaces\AdminRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use App\Repositories\Interfaces\IntegrationRepositoryInterface;

use App\Repositories\PostRepository;
use App\Repositories\MediaRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\TagRepository;
use App\Repositories\AdminRepository;
use App\Repositories\RoleRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\IntegrationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // VeCarCMS Repositories
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(TagRepositoryInterface::class, TagRepository::class);
        $this->app->bind(IntegrationRepositoryInterface::class, IntegrationRepository::class);
        
        // VeCarCMS Services
        $this->app->bind(AdminServiceInterface::class, AdminService::class);
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(\App\Services\Admin\DashboardServiceInterface::class, DashboardService::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(MediaServiceInterface::class, MediaService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(TagServiceInterface::class, function ($app) {
            return new TagService($app->make(TagRepositoryInterface::class));
        });
        $this->app->singleton(IntegrationServiceInterface::class, IntegrationService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        // Gates for admin panel
        Gate::define('manage_roles', function ($user) {
            return $user->hasRole('developer');
        });

        Gate::define('manage_admins', function ($user) {
            return $user->hasRole(['developer', 'super_admin']);
        });

        Gate::define('delete_admin', function ($user, $admin = null) {
            if ($admin && ($admin->hasRole('developer') || $admin->id === $user->id)) {
                return false;
            }
            return $user->hasRole(['super_admin', 'developer']);
        });

        // Super admin e developer hanno accesso completo
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('developer')) {
                return true;
            }
            return null;
        });

        $this->configureRateLimiting();
    }

    /**
     * Configure the rate limiters for the application.
     */
    protected function configureRateLimiting(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $limiterConfig = Config::get('limiter.login', [
                'max_attempts' => 5,
                'decay_minutes' => 1,
                'key' => 'login|:ip',
            ]);
            $key = str_replace(':ip', $request->ip(), $limiterConfig['key']);
            return Limit::perMinutes($limiterConfig['decay_minutes'], $limiterConfig['max_attempts'])->by($key);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            $limiterConfig = Config::get('limiter.two-factor', [
                'max_attempts' => 5,
                'decay_minutes' => 1,
                'key' => 'two-factor|:ip',
            ]);
            $key = str_replace(':ip', $request->ip(), $limiterConfig['key']);
            return Limit::perMinutes($limiterConfig['decay_minutes'], $limiterConfig['max_attempts'])->by($key);
        });

        RateLimiter::for('forgot-password', function (Request $request) {
            $limiterConfig = Config::get('limiter.forgot-password', [
                'max_attempts' => 5,
                'decay_minutes' => 60,
                'key' => 'forgot-password|:ip',
            ]);
            $key = str_replace(':ip', $request->ip(), $limiterConfig['key']);
            return Limit::perMinutes($limiterConfig['decay_minutes'], $limiterConfig['max_attempts'])->by($key);
        });

        RateLimiter::for('verify-email', function (Request $request) {
            $limiterConfig = Config::get('limiter.verify-email', [
                'max_attempts' => 6,
                'decay_minutes' => 1,
                'key' => 'verify-email|:ip',
            ]);
            $key = str_replace(':ip', $request->ip(), $limiterConfig['key']);
            return Limit::perMinutes($limiterConfig['decay_minutes'], $limiterConfig['max_attempts'])->by($key);
        });
    }
}
