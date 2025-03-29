<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Policies\UserPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class, // Map User to UserPolicy
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();

        // Define additional gates if needed
        Gate::define('view-admin-dashboard', function (User $user) {
            return $user->hasRole('SuperAdmin') || $user->hasRole('Admin');
        });
    }
}
