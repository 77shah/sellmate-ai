<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use App\Models\Conversation;

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
        // 🔥 API Rate Limiter (Laravel 11+ me zaroori)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // 🔥 Share unread count with all views
        View::composer('components.sidebar', function ($view) {
            $unreadCount = 0;
            
            if (Auth::check() && Auth::user()->type == 'Owner') {
                $tenantId = Auth::user()->tenant_id ?? Auth::id();
                $unreadCount = Conversation::where('tenant_id', $tenantId)
                    ->where('status', 'open')
                    ->count();
            }
            
            $view->with('unreadCount', $unreadCount);
        });
    }
}