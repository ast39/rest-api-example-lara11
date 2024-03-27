<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider {

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
        Gate::define('is-admin', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('is-moderator', function (User $user) {
            return $user->isModerator();
        });

        Gate::define('is-user', function (User $user) {
            return $user->isUser();
        });
    }
}
