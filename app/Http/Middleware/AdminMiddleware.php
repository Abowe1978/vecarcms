<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized access.');
        }

        $user = auth()->user();
        
        // Check if user has admin roles
        if ($user->hasRole(['admin', 'developer', 'super-admin'])) {
            return $next($request);
        }
        
        // Check if user is section leadership (chair, secretary, treasurer)
        $isSectionLeadership = \App\Models\Section::where(function($query) use ($user) {
            $query->where('chair_id', $user->id)
                  ->orWhere('secretary_id', $user->id)
                  ->orWhere('treasurer_id', $user->id);
        })->exists();
        
        if ($isSectionLeadership) {
            return $next($request);
        }
        
        abort(403, 'Unauthorized access.');
    }
} 