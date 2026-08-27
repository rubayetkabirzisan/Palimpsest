<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate: Can view raw (unredacted) findings
        Gate::define('view-raw-findings', function (User $user) {
            return $user->canViewRawFindings();
        });

        // Gate: Can manage custom detection rules
        Gate::define('manage-rules', function (User $user) {
            return in_array($user->role, ['admin', 'compliance']);
        });

        // Gate: Admin-only actions
        Gate::define('admin', function (User $user) {
            return $user->isAdmin();
        });
    }
}
