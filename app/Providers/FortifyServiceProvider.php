<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Contracts\RegisterResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Custom login response
        $this->app->singleton(LoginResponse::class, function () {
            return new class implements LoginResponse {
                public function toResponse($request)
                {
                    $user = auth()->user();

                    // Admin roles → Admin dashboard
                    if ($user->hasAnyRole(['developer', 'super_admin', 'admin', 'manager'])) {
                        return redirect('/admin/dashboard');
                    }

                    // Regular users → Dashboard
                    return redirect('/dashboard');
                }
            };
        });

        // Custom registration response (redirect to home with logout)
        $this->app->singleton(RegisterResponse::class, function () {
            return new class implements RegisterResponse {
                public function toResponse($request)
                {
                    // User will be logged out by LogoutAfterRegistration listener
                    // Redirect to home page with registration success message
                    return redirect('/');
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
