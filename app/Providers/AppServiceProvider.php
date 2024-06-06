<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Listeners\SendLetterAboutNewUser;
use App\Models\User;
use App\Repositories\ItemRepository;
use App\Repositories\ItemRepositoryInterface;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider {

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
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
