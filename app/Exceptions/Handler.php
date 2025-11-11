<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Don't customize error pages for admin routes
        if ($request->is('admin/*') || $request->is('login') || $request->is('register')) {
            return parent::render($request, $e);
        }

        // Get active theme
        $themeName = active_theme();
        
        if (!$themeName) {
            return parent::render($request, $e);
        }

        // Handle specific HTTP exceptions
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            
            $viewPath = "themes.{$themeName}::views.errors.{$statusCode}";
            
            // Check if custom error view exists
            if (view()->exists($viewPath)) {
                return response()->view($viewPath, ['exception' => $e], $statusCode);
            }
            
            // Fallback to theme's default error view
            if (view()->exists("themes.{$themeName}::views.errors.error")) {
                return response()->view("themes.{$themeName}::views.errors.error", [
                    'exception' => $e,
                    'statusCode' => $statusCode
                ], $statusCode);
            }
        }

        // Handle 404 specifically
        if ($e instanceof NotFoundHttpException) {
            $viewPath = "themes.{$themeName}::views.404";
            
            if (view()->exists($viewPath)) {
                return response()->view($viewPath, ['exception' => $e], 404);
            }
        }

        return parent::render($request, $e);
    }
}

