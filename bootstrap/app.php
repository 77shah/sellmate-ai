<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\TrackLoginAttempts;
use App\Http\Middleware\LogApiCalls;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            RateLimiter::for('api', function (Request $request) {
                return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
            });

            RateLimiter::for('login', function (Request $request) {
                return Limit::perMinute(5)->by($request->ip());
            });

            RateLimiter::for('whatsapp', function (Request $request) {
                return Limit::perMinute(30)->by($request->ip());
            });
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Role Middleware
        $middleware->alias([
            'role' => RoleMiddleware::class,
            'track.login' => TrackLoginAttempts::class,
        ]);

        // CSRF Except URLs
        $middleware->validateCsrfTokens(except: [
            'ai/chat',
            'ai/*',
            'webhooks/*',
            'api/*',
        ]);

        // API Middleware Group
        $middleware->group('api', [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LogApiCalls::class,
        ]);

        // 🔥 Web Middleware Group — Hatao (Default apply ho jate hain)
        // $middleware->group('web', [...])

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();