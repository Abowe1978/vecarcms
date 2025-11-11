<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Providers\RouteServiceProvider;

class RedirectAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only redirect after successful login
        if ($request->is('login') && $request->isMethod('post') && auth()->check()) {
            $redirectPath = RouteServiceProvider::redirectTo();
            return redirect($redirectPath);
        }

        return $response;
    }
}
