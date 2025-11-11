<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SectionAccessMiddleware
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
        
        // Admin roles have full access
        if ($user->hasRole(['admin', 'developer', 'super-admin'])) {
            return $next($request);
        }
        
        // Check if user is section leadership
        $userSections = \App\Models\Section::where(function($query) use ($user) {
            $query->where('chair_id', $user->id)
                  ->orWhere('secretary_id', $user->id)
                  ->orWhere('treasurer_id', $user->id);
        })->pluck('id')->toArray();
        
        if (empty($userSections)) {
            abort(403, 'You are not authorized to access any section data.');
        }
        
        // Store user's sections in the request for use in controllers
        $request->merge(['user_sections' => $userSections]);
        
        return $next($request);
    }
}
