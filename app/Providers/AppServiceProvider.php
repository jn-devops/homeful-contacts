<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;

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
        Gate::define('viewPulse', function (User $user) {
//            return $user->isAdmin();
            //To do: implement only admins can view laravel pulse
            return true;
        });
        Vite::prefetch(concurrency: 3);
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(10000)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
