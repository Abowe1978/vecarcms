<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardServiceInterface;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class DashboardController extends Controller
{
    public function __construct(
        protected DashboardServiceInterface $dashboardService
    ) {}

    /**
     * Show the admin dashboard with VeCarCMS statistics
     */
    public function index(): View
    {
        // Get stats from service
        $stats = $this->dashboardService->getDashboardStats();
        
        // Get recent posts from service
        $recentPosts = $this->dashboardService->getRecentPosts(5);

        return view('admin.dashboard', compact('stats', 'recentPosts'));
    }

    /**
     * Clear all application cache
     */
    public function clearCache(): RedirectResponse
    {
        try {
            Artisan::call('optimize:clear');
            
            return back()->with('success', 'Cache cancellata con successo! ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'Errore durante la cancellazione della cache: ' . $e->getMessage());
        }
    }
} 