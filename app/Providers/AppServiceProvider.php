<?php

namespace App\Providers;

use App\Support\PersistentNotificationService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
        View::composer(['layouts.admin', 'layouts.user', 'layouts.driver'], function ($view) {
            $authUser = Auth::user();

            $view->with('appNotifications', $authUser ? PersistentNotificationService::syncAndFetch($authUser) : [
                'count' => 0,
                'items' => collect(),
            ]);
        });
    }
}
