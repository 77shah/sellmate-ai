<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LogApiCalls
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);
        
        $response = $next($request);
        
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000);
        
        try {
            // Check if api_logs table exists
            if (DB::getSchemaBuilder()->hasTable('api_logs')) {
                DB::table('api_logs')->insert([
                    'id' => (string) Str::uuid(),
                    'endpoint' => $request->path(),
                    'method' => $request->method(),
                    'tenant_id' => $request->header('X-Tenant-ID') ?? $request->tenant_id ?? null,
                    'status_code' => $response->getStatusCode(),
                    'response_time' => $responseTime,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'request_data' => json_encode($request->except(['password', 'password_confirmation'])),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('API Log failed: ' . $e->getMessage());
        }
        
        return $response;
    }
}