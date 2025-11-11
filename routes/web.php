<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PublicController;

/*
|--------------------------------------------------------------------------
| Web Routes - VeCarCMS
|--------------------------------------------------------------------------
|
| Public frontend routes and authenticated admin routes.
| Admin routes are in routes/admin.php
|
*/

// ============================================================================
// PUBLIC FRONTEND ROUTES
// ============================================================================

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/blog', [PublicController::class, 'blog'])->name('blog');
Route::get('/search', [PublicController::class, 'search'])->name('search');

// Newsletter
Route::post('/newsletter/subscribe', [\App\Http\Controllers\NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Contact Form
Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'submit'])->name('contact.submit');

// Comments
Route::post('/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comments.store')->middleware('auth');

// ============================================================================
// ADMIN ROUTES
// ============================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // System Actions
    Route::post('/clear-cache', [App\Http\Controllers\Admin\DashboardController::class, 'clearCache'])->name('clear-cache');
    
    // Admin Profile (My Profile)
    Route::get('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');

    // Posts Management
    Route::middleware(['can:manage_posts'])->group(function () {
        Route::resource('posts', App\Http\Controllers\Admin\PostController::class);
        Route::post('posts/{post}/duplicate', [App\Http\Controllers\Admin\PostController::class, 'duplicate'])->name('posts.duplicate');
        Route::post('posts/bulk-action', [App\Http\Controllers\Admin\PostController::class, 'bulkAction'])->name('posts.bulk');
    });

    // Pages Management
    Route::middleware(['can:manage_pages'])->group(function () {
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class);
        Route::post('pages/{page}/duplicate', [App\Http\Controllers\Admin\PageController::class, 'duplicate'])->name('pages.duplicate');
        Route::post('pages/bulk-action', [App\Http\Controllers\Admin\PageController::class, 'bulkAction'])->name('pages.bulk');
    });

    // Categories Management
    Route::middleware(['can:manage_categories'])->group(function () {
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    });

    // Tags Management
    Route::middleware(['can:manage_tags'])->group(function () {
        Route::resource('tags', App\Http\Controllers\Admin\TagController::class);
    });

    // Media Library
    Route::middleware(['can:manage_media'])->group(function () {
        Route::get('media', [App\Http\Controllers\Admin\MediaController::class, 'index'])->name('media.index');
        Route::post('media/upload', [App\Http\Controllers\Admin\MediaController::class, 'upload'])->name('media.upload');
        Route::delete('media/{media}', [App\Http\Controllers\Admin\MediaController::class, 'destroy'])->name('media.destroy');
    });

    // Menus Management
    Route::middleware(['can:manage_menus'])->group(function () {
        Route::resource('menus', App\Http\Controllers\Admin\MenuController::class);
        Route::post('menus/{menu}/items', [App\Http\Controllers\Admin\MenuController::class, 'addItem'])->name('menus.items.add');
        Route::put('menus/{menu}/items/{menuItem}', [App\Http\Controllers\Admin\MenuController::class, 'updateItem'])->name('menus.items.update');
        Route::delete('menus/{menu}/items/{menuItem}', [App\Http\Controllers\Admin\MenuController::class, 'deleteItem'])->name('menus.items.delete');
        Route::post('menus/{menu}/reorder', [App\Http\Controllers\Admin\MenuController::class, 'reorder'])->name('menus.reorder');
    });

    // Widgets Management
    Route::middleware(['can:manage_widgets'])->group(function () {
        Route::get('widgets', [App\Http\Controllers\Admin\WidgetController::class, 'index'])->name('widgets.index');
        Route::get('widgets/{widget}/edit', [App\Http\Controllers\Admin\WidgetController::class, 'edit'])->name('widgets.edit');
        Route::post('widgets', [App\Http\Controllers\Admin\WidgetController::class, 'store'])->name('widgets.store');
        Route::put('widgets/{widget}', [App\Http\Controllers\Admin\WidgetController::class, 'update'])->name('widgets.update');
        Route::delete('widgets/{widget}', [App\Http\Controllers\Admin\WidgetController::class, 'destroy'])->name('widgets.destroy');
        Route::post('widgets/reorder', [App\Http\Controllers\Admin\WidgetController::class, 'reorder'])->name('widgets.reorder');
    });

    // Themes Management
    Route::middleware(['can:manage_themes'])->group(function () {
        Route::get('themes', [App\Http\Controllers\Admin\ThemeController::class, 'index'])->name('themes.index');
        Route::post('themes/upload', [App\Http\Controllers\Admin\ThemeController::class, 'upload'])->name('themes.upload');
        Route::post('themes/{theme}/activate', [App\Http\Controllers\Admin\ThemeController::class, 'activate'])->name('themes.activate');
        Route::post('themes/scan', [App\Http\Controllers\Admin\ThemeController::class, 'scan'])->name('themes.scan');
        Route::get('themes/{theme}/settings', [App\Http\Controllers\Admin\ThemeController::class, 'settings'])->name('themes.settings');
        Route::put('themes/{theme}/settings', [App\Http\Controllers\Admin\ThemeController::class, 'updateSettings'])->name('themes.settings.update');
        Route::delete('themes/{theme}', [App\Http\Controllers\Admin\ThemeController::class, 'destroy'])->name('themes.destroy');
        
        // Theme Customizer
        Route::get('themes/{theme}/customizer', [App\Http\Controllers\Admin\ThemeCustomizerController::class, 'index'])->name('themes.customizer');
        Route::put('themes/{theme}/customizer', [App\Http\Controllers\Admin\ThemeCustomizerController::class, 'update'])->name('themes.customizer.update');
        Route::get('themes/{theme}/customizer/reset', [App\Http\Controllers\Admin\ThemeCustomizerController::class, 'reset'])->name('themes.customizer.reset');
    });

    // Plugins Management
    Route::middleware(['can:manage_plugins'])->group(function () {
        Route::get('plugins', [App\Http\Controllers\Admin\PluginController::class, 'index'])->name('plugins.index');
        Route::post('plugins/scan', [App\Http\Controllers\Admin\PluginController::class, 'scan'])->name('plugins.scan');
        Route::post('plugins/upload', [App\Http\Controllers\Admin\PluginController::class, 'upload'])->name('plugins.upload');
        Route::post('plugins/{plugin}/activate', [App\Http\Controllers\Admin\PluginController::class, 'activate'])->name('plugins.activate');
        Route::post('plugins/{plugin}/deactivate', [App\Http\Controllers\Admin\PluginController::class, 'deactivate'])->name('plugins.deactivate');
        Route::delete('plugins/{plugin}', [App\Http\Controllers\Admin\PluginController::class, 'destroy'])->name('plugins.destroy');
        Route::get('plugins/{plugin}/settings', [App\Http\Controllers\Admin\PluginController::class, 'settings'])->name('plugins.settings');
        Route::put('plugins/{plugin}/settings', [App\Http\Controllers\Admin\PluginController::class, 'updateSettings'])->name('plugins.settings.update');
    });

    // Comments Management
    Route::middleware(['can:manage_comments'])->group(function () {
        Route::get('comments', [App\Http\Controllers\Admin\CommentController::class, 'index'])->name('comments.index');
        Route::get('comments/{comment}/edit', [App\Http\Controllers\Admin\CommentController::class, 'edit'])->name('comments.edit');
        Route::put('comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'update'])->name('comments.update');
        Route::delete('comments/{comment}', [App\Http\Controllers\Admin\CommentController::class, 'destroy'])->name('comments.destroy');
        Route::post('comments/{comment}/approve', [App\Http\Controllers\Admin\CommentController::class, 'approve'])->name('comments.approve');
        Route::post('comments/{comment}/spam', [App\Http\Controllers\Admin\CommentController::class, 'spam'])->name('comments.spam');
        Route::post('comments/{comment}/trash', [App\Http\Controllers\Admin\CommentController::class, 'trash'])->name('comments.trash');
        Route::post('comments/bulk', [App\Http\Controllers\Admin\CommentController::class, 'bulkAction'])->name('comments.bulk');
    });

    // Contact Forms Management
    Route::middleware(['can:view_submissions'])->group(function () {
        Route::get('contact-submissions', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'index'])->name('contact.index');
        Route::get('contact-submissions/{contactSubmission}', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'show'])->name('contact.show');
        Route::put('contact-submissions/{contactSubmission}', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'update'])->name('contact.update');
        Route::delete('contact-submissions/{contactSubmission}', [App\Http\Controllers\Admin\ContactSubmissionController::class, 'destroy'])->name('contact.destroy');
    });

    // Settings Management
    Route::middleware(['can:manage_settings'])->group(function () {
        Route::get('settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::put('settings', [App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        
        // SEO Settings
        Route::get('seo', [App\Http\Controllers\Admin\SeoController::class, 'index'])->name('seo.index');
        Route::put('seo', [App\Http\Controllers\Admin\SeoController::class, 'update'])->name('seo.update');
        Route::post('seo/regenerate-sitemap', [App\Http\Controllers\Admin\SeoController::class, 'regenerateSitemap'])->name('seo.regenerate-sitemap');
    });

    // SEO Tools
    Route::middleware(['can:manage_seo'])->group(function () {
        Route::get('seo', [App\Http\Controllers\Admin\SeoController::class, 'index'])->name('seo.index');
        Route::put('seo', [App\Http\Controllers\Admin\SeoController::class, 'update'])->name('seo.update');
        Route::post('seo/generate-sitemap', [App\Http\Controllers\Admin\SeoController::class, 'generateSitemap'])->name('seo.sitemap');
    });

    // User Management
    Route::middleware(['can:manage_users'])->group(function () {
        Route::resource('admins', App\Http\Controllers\Admin\AdminController::class);
    });

    // Roles & Permissions
    Route::middleware(['can:manage_roles'])->group(function () {
        Route::resource('roles', App\Http\Controllers\Admin\RoleController::class);
    });

    // Integrations
    Route::get('integrations', [App\Http\Controllers\Admin\IntegrationController::class, 'index'])->name('integrations.index');
});

// ============================================================================
// DYNAMIC CONTENT ROUTES (WordPress-like)
// ============================================================================

Route::get('/{slug}', [PublicController::class, 'dynamicContent'])
    ->where('slug', '[a-zA-Z0-9\-]+')
    ->where('slug', '(?!admin).*'); // Exclude /admin/*
    
Route::get('/{parent}/{slug}', [PublicController::class, 'dynamicContent'])
    ->where('parent', '[a-zA-Z0-9\-]+')
    ->where('slug', '[a-zA-Z0-9\-]+');

// ============================================================================
// LANGUAGE SWITCHER
// ============================================================================

Route::post('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

// ============================================================================
// AUTHENTICATED ROUTES (Profile, User Settings)
// ============================================================================

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // User Profile (Jetstream)
    Route::get('/user/profile', function () {
        return view('admin.profile.my-profile');
    })->name('profile.show');
});

// ============================================================================
// 404 FALLBACK ROUTE
// ============================================================================

Route::fallback(function () {
    $themeName = active_theme();
    
    if (view()->exists("themes.{$themeName}::views.404")) {
        return response()->view("themes.{$themeName}::views.404", [], 404);
    }
    
    return response()->view('errors.404', [], 404);
});
