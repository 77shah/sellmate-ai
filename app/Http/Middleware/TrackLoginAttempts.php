<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LoginAttempt;
use App\Models\SuspiciousIp;
use Illuminate\Support\Facades\Auth;

class TrackLoginAttempts
{
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function login(Request $request, $user)
    {
        // Log successful login
        LoginAttempt::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'success' => true,
        ]);
    }

    public function failed(Request $request)
    {
        $ip = $request->ip();
        $email = $request->email;

        // Log failed attempt
        LoginAttempt::create([
            'email' => $email,
            'ip_address' => $ip,
            'user_agent' => $request->userAgent(),
            'success' => false,
            'failure_reason' => 'Invalid credentials',
        ]);

        // Update or create suspicious IP
        $suspicious = SuspiciousIp::where('ip_address', $ip)->first();
        if ($suspicious) {
            $suspicious->increment('attempts');
            $suspicious->update(['last_attempt_at' => now()]);
        } else {
            SuspiciousIp::create([
                'ip_address' => $ip,
                'reason' => 'Failed login attempts',
                'attempts' => 1,
                'last_attempt_at' => now(),
            ]);
        }

        // Block IP after 5 failed attempts
        $attempts = LoginAttempt::where('ip_address', $ip)
            ->where('success', false)
            ->whereDate('created_at', today())
            ->count();

        if ($attempts >= 5) {
            $this->blockIp($ip, 'Too many failed login attempts');
        }
    }

    private function blockIp($ip, $reason)
    {
        \App\Models\BlockedIp::updateOrCreate(
            ['ip_address' => $ip],
            [
                'reason' => $reason,
                'blocked_until' => now()->addHours(24),
                'permanent' => false,
            ]
        );
    }
}