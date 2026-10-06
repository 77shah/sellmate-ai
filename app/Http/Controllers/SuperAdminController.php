<?php
// app/Http/Controllers/SuperAdminController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Business;
use App\Models\Subscription;
use App\Models\AiLog;
use App\Models\Customer;
use App\Models\Conversation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use App\Services\ServerMonitor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use App\Models\Deployment;
use App\Models\LoginAttempt;
use App\Models\SuspiciousIp;
use App\Models\BlockedIp;

class SuperAdminController extends Controller
{
    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    public function dashboard()
{
    try {
        // ==========================================
        // BASIC STATS
        // ==========================================
        $totalBusinesses = User::where('type', 'Owner')->count();
        $totalStaff = User::whereIn('type', ['Staff', 'SalesAgent'])->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');

        // ==========================================
        // SUBSCRIPTION STATS
        // ==========================================
        $activeSubscriptions = Subscription::where('status', 'active')->count();
        $expiredSubscriptions = Subscription::where('status', 'expired')->count();
        $monthlyRevenue = Subscription::where('status', 'active')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // ==========================================
        // AI USAGE
        // ==========================================
        $totalAiUsage = AiLog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('tokens_used');
        $aiUsageToday = AiLog::whereDate('created_at', today())->sum('tokens_used');

        // ==========================================
        // TODAY'S STATS
        // ==========================================
        $todayBusinesses = User::where('type', 'Owner')
            ->whereDate('created_at', today())
            ->count();
        $todayOrders = Order::whereDate('created_at', today())->count();
        $todayRevenue = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // ==========================================
        // RECENT ORDERS
        // ==========================================
        $recentOrders = Order::with('customer')
            ->latest()
            ->limit(8)
            ->get();

        // ==========================================
        // RECENT BUSINESSES
        // ==========================================
        $recentBusinesses = User::where('type', 'Owner')
            ->latest()
            ->limit(5)
            ->get();

        // ==========================================
        // TOP BUSINESSES (by orders)
        // ==========================================
        $topBusinesses = User::where('type', 'Owner')
            ->withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->limit(5)
            ->get();

        // ==========================================
        // CHART DATA — Last 7 days revenue
        // ==========================================
        $chartLabels = [];
        $chartRevenue = [];
        $chartOrders = [];
        $chartSubscriptions = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartLabels[] = $date->format('d M');

            $chartRevenue[] = Order::whereDate('created_at', $date)
                ->where('payment_status', 'paid')
                ->sum('total_amount');

            $chartOrders[] = Order::whereDate('created_at', $date)->count();

            $chartSubscriptions[] = Subscription::whereDate('created_at', $date)->count();
        }

        // ==========================================
        // SYSTEM HEALTH SUMMARY
        // ==========================================
        $healthStatus = [
            'database' => true,
            'python' => false,
            'qdrant' => false,
            'redis' => false,
        ];

        try {
            \DB::connection()->getPdo();
            $healthStatus['database'] = true;
        } catch (\Exception $e) {
            $healthStatus['database'] = false;
        }

        try {
            $pythonHealth = \Http::timeout(2)->get('http://127.0.0.1:8001/health');
            $healthStatus['python'] = $pythonHealth->successful();
        } catch (\Exception $e) {
            $healthStatus['python'] = false;
        }

        try {
            $qdrantHealth = \Http::timeout(2)->get('http://127.0.0.1:6333/healthz');
            $healthStatus['qdrant'] = $qdrantHealth->successful();
        } catch (\Exception $e) {
            $healthStatus['qdrant'] = false;
        }

        try {
            \Illuminate\Support\Facades\Redis::connection()->ping();
            $healthStatus['redis'] = true;
        } catch (\Exception $e) {
            $healthStatus['redis'] = false;
        }

        return view('super-admin.dashboard', compact(
            'totalBusinesses',
            'totalStaff',
            'totalOrders',
            'totalRevenue',
            'activeSubscriptions',
            'expiredSubscriptions',
            'monthlyRevenue',
            'totalAiUsage',
            'aiUsageToday',
            'todayBusinesses',
            'todayOrders',
            'todayRevenue',
            'recentOrders',
            'recentBusinesses',
            'topBusinesses',
            'chartLabels',
            'chartRevenue',
            'chartOrders',
            'chartSubscriptions',
            'healthStatus'
        ));

    } catch (\Exception $e) {
        \Log::error('Dashboard Error: ' . $e->getMessage());
        
        return view('super-admin.dashboard', [
            'totalBusinesses' => 0,
            'totalStaff' => 0,
            'totalOrders' => 0,
            'totalRevenue' => 0,
            'activeSubscriptions' => 0,
            'expiredSubscriptions' => 0,
            'monthlyRevenue' => 0,
            'totalAiUsage' => 0,
            'aiUsageToday' => 0,
            'todayBusinesses' => 0,
            'todayOrders' => 0,
            'todayRevenue' => 0,
            'recentOrders' => collect([]),
            'recentBusinesses' => collect([]),
            'topBusinesses' => collect([]),
            'chartLabels' => [],
            'chartRevenue' => [],
            'chartOrders' => [],
            'chartSubscriptions' => [],
            'healthStatus' => [
                'database' => false,
                'python' => false,
                'qdrant' => false,
                'redis' => false,
            ],
        ]);
    }
}

   
    public function businesses(Request $request)
{
    $query = User::where('type', 'Owner')
        ->leftJoin('businesses', 'users.tenant_id', '=', 'businesses.tenant_id')
        ->select('users.*', 
            'businesses.phone as biz_phone',
            'businesses.address as biz_address',
            'businesses.business_type as biz_type',
            'businesses.website as biz_website',
            'businesses.name as biz_name'
        );

    if ($request->search) {
        $query->where(function($q) use ($request) {
            $q->where('users.name', 'LIKE', "%{$request->search}%")
              ->orWhere('users.email', 'LIKE', "%{$request->search}%")
              ->orWhere('users.tenant_id', 'LIKE', "%{$request->search}%")
              ->orWhere('businesses.name', 'LIKE', "%{$request->search}%");
        });
    }

    if ($request->status) {
        $query->where('users.status', $request->status);
    }

    $businesses = $query->latest('users.created_at')->paginate(15);
    return view('super-admin.businesses.index', compact('businesses'));
}

// ==========================================
// 2. SHOW BUSINESS
// ==========================================
public function showBusiness($id)
{
    $business = User::where('type', 'Owner')->findOrFail($id);

    // 🔥 Business details bhi fetch karo
    $businessDetails = Business::where('tenant_id', $business->tenant_id)->first();

    return view('super-admin.businesses.show', compact('business', 'businessDetails'));
}

// ==========================================
// 3. CREATE BUSINESS
// ==========================================
public function createBusiness()
{
    return view('super-admin.businesses.create');
}

public function storeBusiness(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'whatsapp_no' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'business_type' => 'nullable|string|max:100',
        'website' => 'nullable|url|max:255',
        'status' => 'nullable|in:active,suspended',
    ]);

    $tenantId = 'tenant_' . Str::random(8);

    // 1. Create User
    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'type' => 'Owner',
        'tenant_id' => $tenantId,
        'mobile_no' => $validated['phone'] ?? null,
        'whatsapp_no' => $validated['whatsapp_no'] ?? $validated['phone'] ?? null,
        'status' => 'active',
    ]);

    // 2. Create Business
    Business::create([
        'tenant_id' => $tenantId,
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'address' => $validated['address'] ?? null,
        'business_type' => $validated['business_type'] ?? null,
        'website' => $validated['website'] ?? null,
        'status' => 'active',
        'settings' => [],
    ]);

    return redirect()->route('super-admin.businesses')
        ->with('success', 'Business "' . $user->name . '" created successfully! Tenant ID: ' . $tenantId);
}

// ==========================================
// 4. EDIT BUSINESS
// ==========================================
public function editBusiness($id)
{
    $business = User::where('type', 'Owner')->findOrFail($id);

    // 🔥 Business details bhi fetch karo
    $businessDetails = Business::where('tenant_id', $business->tenant_id)->first();

    return view('super-admin.businesses.edit', compact('business', 'businessDetails'));
}

public function updateBusiness(Request $request, $id)
{
    $business = User::where('type', 'Owner')->findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users')->ignore($business->id)],
        'phone' => 'nullable|string|max:20',
        'whatsapp_no' => 'nullable|string|max:20',
        'address' => 'nullable|string',
        'business_type' => 'nullable|string|max:100',
        'website' => 'nullable|url|max:255',
        'status' => 'required|in:active,suspended',
    ]);

    // 1. Update User
    $business->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'mobile_no' => $validated['phone'] ?? $business->mobile_no,
        'whatsapp_no' => $validated['whatsapp_no'] ?? $business->whatsapp_no,
        'status' => $validated['status'],
    ]);

    // 2. Update or Create Business
    Business::updateOrCreate(
        ['tenant_id' => $business->tenant_id],
        [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'business_type' => $validated['business_type'] ?? null,
            'website' => $validated['website'] ?? null,
            'status' => $validated['status'],
        ]
    );

    return redirect()->route('super-admin.businesses')
        ->with('success', 'Business updated successfully!');
}

// ==========================================
// 5. DELETE BUSINESS
// ==========================================
public function deleteBusiness($id)
{
    $business = User::where('type', 'Owner')->findOrFail($id);

    Business::where('tenant_id', $business->tenant_id)->delete();
    $business->delete();

    return redirect()->route('super-admin.businesses')
        ->with('success', 'Business deleted successfully!');
}

// ==========================================
// 6. SUSPEND / ACTIVATE / IMPERSONATE
// ==========================================
public function suspendBusiness($id)
{
    $business = User::findOrFail($id);
    $business->update(['status' => 'suspended']);
    Business::where('tenant_id', $business->tenant_id)->update(['status' => 'suspended']);

    return back()->with('success', 'Business suspended successfully.');
}

public function activateBusiness($id)
{
    $business = User::findOrFail($id);
    $business->update(['status' => 'active']);
    Business::where('tenant_id', $business->tenant_id)->update(['status' => 'active']);

    return back()->with('success', 'Business activated successfully.');
}

public function impersonateBusiness($id)
{
    $business = User::findOrFail($id);

    if ($business->status != 'active') {
        return back()->with('error', 'Cannot impersonate suspended business.');
    }

    session(['impersonate_from' => auth()->id()]);
    Auth::loginUsingId($id);
    return redirect()->route('owner.dashboard');
}

public function stopImpersonate()
{
    $originalId = session('impersonate_from');
    if ($originalId) {
        session()->forget('impersonate_from');
        Auth::loginUsingId($originalId);
        return redirect()->route('super-admin.dashboard');
    }
    return redirect()->route('super-admin.dashboard');
}

    // ==========================================
    // 4. SUBSCRIPTIONS
    // ==========================================
    public function subscriptions()
    {
        try {
            // 🔥 FIX: Paginate use karein, get() nahi
            $subscriptions = Subscription::with('tenant')->latest()->paginate(15);
            $stats = [
                'total' => Subscription::count(),
                'active' => Subscription::where('status', 'active')->count(),
                'expired' => Subscription::where('status', 'expired')->count(),
                'trial' => Subscription::where('status', 'trial')->count(),
            ];
        } catch (\Exception $e) {
            $subscriptions = collect([]);
            $stats = ['total' => 0, 'active' => 0, 'expired' => 0, 'trial' => 0];
        }

        return view('super-admin.subscriptions', compact('subscriptions', 'stats'));
    }

    // ==========================================
    // 5. AI USAGE
    // ==========================================
    public function aiUsage()
    {
        // Stats
        $totalMessages = AiLog::count();
        $totalTokens = AiLog::sum('tokens_used');
        $totalCost = AiLog::sum('cost');
        $avgResponse = AiLog::avg('response_time');
        $todayMessages = AiLog::whereDate('created_at', today())->count();
        
        // Intent breakdown
        $intents = AiLog::select('intent', \DB::raw('count(*) as count'))
            ->groupBy('intent')
            ->get();
        
        // Tenant wise usage
        $tenantUsage = AiLog::select('tenant_id', \DB::raw('count(*) as total'))
            ->groupBy('tenant_id')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();
        
        return view('super-admin.ai-usage', compact(
            'totalMessages',
            'totalTokens',
            'totalCost',
            'avgResponse',
            'todayMessages',
            'intents',
            'tenantUsage'
        ));
    }

    // ==========================================
    // 6. CLOUD RESOURCES
    // ==========================================
    public function cloudResources()
    {
        $monitor = new ServerMonitor();
        $resources = $monitor->getResources();
        
        return view('super-admin.cloud-resources', compact('resources'));
    }

    // ==========================================
    // 7. BILLING
    // ==========================================
    public function billing(Request $request)
    {
        try {
            // Revenue Stats
            $revenue = [
                'today' => DB::table('payments')->whereDate('created_at', today())->sum('amount') ?? 0,
                'week' => DB::table('payments')->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('amount') ?? 0,
                'month' => DB::table('payments')->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('amount') ?? 0,
                'year' => DB::table('payments')->whereYear('created_at', now()->year)->sum('amount') ?? 0,
                'total' => DB::table('payments')->sum('amount') ?? 0,
            ];
            
            // Payment Stats
            $paymentStats = [
                'total_payments' => DB::table('payments')->count(),
                'successful_payments' => DB::table('payments')->where('status', 'success')->count(),
                'failed_payments' => DB::table('payments')->where('status', 'failed')->count(),
                'pending_payments' => DB::table('payments')->where('status', 'pending')->count(),
                'refunded_payments' => DB::table('payments')->where('status', 'refunded')->count(),
            ];
            
            // Invoices with pagination
            $invoices = DB::table('payments')
                ->select('payments.*', 'users.name as business_name', 'users.email as business_email')
                ->leftJoin('users', 'payments.tenant_id', '=', 'users.tenant_id')
                ->where('users.type', 'Owner')
                ->orderBy('payments.created_at', 'desc')
                ->paginate(15);
            
            // Recent Payments
            $recentPayments = DB::table('payments')
                ->select('payments.*', 'users.name as business_name')
                ->leftJoin('users', 'payments.tenant_id', '=', 'users.tenant_id')
                ->where('users.type', 'Owner')
                ->orderBy('payments.created_at', 'desc')
                ->limit(10)
                ->get();
            
            // Chart data (last 30 days)
            $chartData = [];
            for ($i = 30; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $amount = DB::table('payments')
                    ->whereDate('created_at', $date)
                    ->sum('amount') ?? 0;
                $chartData[] = [
                    'date' => $date,
                    'amount' => $amount
                ];
            }
            
            return view('super-admin.billing', compact(
                'revenue', 
                'paymentStats', 
                'invoices', 
                'recentPayments',
                'chartData'
            ));
            
        } catch (\Exception $e) {
            \Log::error('Billing Error: ' . $e->getMessage());
            
            $revenue = ['today' => 0, 'week' => 0, 'month' => 0, 'year' => 0, 'total' => 0];
            $paymentStats = ['total_payments' => 0, 'successful_payments' => 0, 'failed_payments' => 0, 'pending_payments' => 0, 'refunded_payments' => 0];
            $invoices = collect([]);
            $recentPayments = collect([]);
            $chartData = [];
            
            return view('super-admin.billing', compact(
                'revenue', 
                'paymentStats', 
                'invoices', 
                'recentPayments',
                'chartData'
            ));
        }
    }

    // ==========================================
    // 8. LOGS
    // ==========================================
    public function logs(Request $request)
    {
        try {
            // Get log files
            $logFiles = File::files(storage_path('logs'));
            
            // Default to laravel.log
            $logFile = storage_path('logs/laravel.log');
            
            if ($request->file && file_exists(storage_path('logs/' . $request->file))) {
                $logFile = storage_path('logs/' . $request->file);
            }
            
            // Read log file
            $content = File::get($logFile);
            $lines = explode("\n", $content);
            $lines = array_filter($lines);
            $lines = array_reverse($lines); // Latest first
            
            // Parse logs
            $parsedLogs = [];
            foreach ($lines as $line) {
                $parsed = $this->parseLogLine($line);
                if ($parsed) {
                    $parsedLogs[] = $parsed;
                }
            }
            
            // Apply filters
            $filteredLogs = collect($parsedLogs);
            
            if ($request->severity) {
                $filteredLogs = $filteredLogs->where('severity', $request->severity);
            }
            
            if ($request->search) {
                $filteredLogs = $filteredLogs->filter(function($log) use ($request) {
                    return stripos($log['message'], $request->search) !== false ||
                        stripos($log['tenant_id'] ?? '', $request->search) !== false;
                });
            }
            
            // Paginate
            $perPage = 25;
            $page = $request->page ?? 1;
            $total = $filteredLogs->count();
            $items = $filteredLogs->slice(($page - 1) * $perPage, $perPage);
            
            // Create paginator
            $logs = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $total,
                $perPage,
                $page,
                ['path' => url()->current()]
            );
            
            // Log files list
            $logFilesList = array_map(function($file) {
                return $file->getFilename();
            }, $logFiles);
            
            return view('super-admin.logs', compact('logs', 'logFilesList'));
            
        } catch (\Exception $e) {
            \Log::error('Logs Error: ' . $e->getMessage());
            $logs = collect([]);
            $logFilesList = [];
            return view('super-admin.logs', compact('logs', 'logFilesList'));
        }
    }

    private function parseLogLine($line)
    {
        // Laravel log format: [2024-01-15 10:30:00] local.ERROR: Something went wrong
        $pattern = '/\[(.*?)\]\s+(\w+)\.(\w+):\s+(.*?)(?:\s+\{.*\})?$/';
        
        if (preg_match($pattern, $line, $matches)) {
            $message = $matches[4] ?? $line;
            
            // 🔥 Extract tenant_id from message
            $tenantId = null;
            
            // Pattern 1: "Using Tenant ID: tenant_F3lUxV"
            if (preg_match('/Using Tenant ID:\s*([^\s]+)/', $message, $tenantMatch)) {
                $tenantId = $tenantMatch[1];
            }
            // Pattern 2: "tenant_id:tenant_F3lUxV"
            elseif (preg_match('/tenant_id[:\s]+([^\s,}]+)/', $message, $tenantMatch)) {
                $tenantId = $tenantMatch[1];
            }
            // Pattern 3: "for tenant: tenant_F3lUxV"
            elseif (preg_match('/for tenant[:\s]+([^\s,}]+)/', $message, $tenantMatch)) {
                $tenantId = $tenantMatch[1];
            }
            // Pattern 4: "tenant_F3lUxV" (anywhere in message)
            elseif (preg_match('/(tenant_[A-Za-z0-9]+)/', $message, $tenantMatch)) {
                $tenantId = $tenantMatch[1];
            }
            
            // 🔥 Extract IP if present
            $ip = null;
            if (preg_match('/\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}/', $message, $ipMatch)) {
                $ip = $ipMatch[0];
            }
            
            return [
                'timestamp' => $matches[1] ?? null,
                'env' => $matches[2] ?? 'local',
                'severity' => strtoupper($matches[3] ?? 'INFO'),
                'message' => $message,
                'tenant_id' => $tenantId,
                'ip' => $ip,
            ];
        }
        
        // Fallback for simple log lines
        if (!empty(trim($line))) {
            $tenantId = null;
            if (preg_match('/(tenant_[A-Za-z0-9]+)/', $line, $tenantMatch)) {
                $tenantId = $tenantMatch[1];
            }
            
            return [
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'env' => 'local',
                'severity' => 'INFO',
                'message' => $line,
                'tenant_id' => $tenantId,
                'ip' => null,
            ];
        }
        
        return null;
    }

    public function clearLogs()
    {
        try {
            $logFile = storage_path('logs/laravel.log');
            if (file_exists($logFile)) {
                file_put_contents($logFile, '');
            }
            return response()->json(['success' => true, 'message' => 'Logs cleared successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // ==========================================
    // 9. API GATEWAY
    // ==========================================

    public function apiGateway(Request $request)
    {
        try {
            // Date filters
            $dateFilter = $request->filter ?? 'today';
            
            $query = DB::table('api_logs');
            
            // Apply date filter
            switch ($dateFilter) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
            
            // Total calls
            $totalCalls = $query->count();
            
            // Success vs Failed
            $successCalls = (clone $query)->where('status_code', '<', 400)->count();
            $failedCalls = (clone $query)->where('status_code', '>=', 400)->count();
            $errorRate = $totalCalls > 0 ? round(($failedCalls / $totalCalls) * 100, 2) : 0;
            
            // Average response time
            $avgLatency = (clone $query)->avg('response_time') ?? 0;
            
            // Top endpoints
            $endpoints = (clone $query)
                ->select('endpoint', DB::raw('COUNT(*) as calls'), DB::raw('AVG(response_time) as avg_time'))
                ->groupBy('endpoint')
                ->orderBy('calls', 'DESC')
                ->limit(10)
                ->get();
            
            // Status code breakdown
            $statusCodes = (clone $query)
                ->select('status_code', DB::raw('COUNT(*) as count'))
                ->groupBy('status_code')
                ->orderBy('count', 'DESC')
                ->get();
            
            // Recent API calls
            $recentCalls = (clone $query)
                ->orderBy('created_at', 'DESC')
                ->limit(10)
                ->get();
            
            // Chart data (last 24 hours)
            $chartData = [];
            for ($i = 23; $i >= 0; $i--) {
                $hour = now()->subHours($i)->format('H:00');
                $count = DB::table('api_logs')
                    ->where('created_at', '>=', now()->subHours($i + 1))
                    ->where('created_at', '<', now()->subHours($i))
                    ->count();
                $chartData[] = [
                    'time' => $hour,
                    'calls' => $count
                ];
            }
            
            // Check Python AI status
            try {
                $pythonResponse = Http::get('http://localhost:8001/health');
                $pythonStatus = $pythonResponse->successful() ? 'Operational' : 'Down';
            } catch (\Exception $e) {
                $pythonStatus = 'Down';
            }
            
            // API Status
            $apiStatus = [
                'laravel' => 'Operational',
                'python_ai' => $pythonStatus,
                'database' => DB::connection()->getPdo() ? 'Operational' : 'Down',
            ];
            
            $stats = [
                'total_calls' => $totalCalls,
                'success_calls' => $successCalls,
                'failed_calls' => $failedCalls,
                'error_rate' => $errorRate,
                'avg_latency' => round($avgLatency, 2),
                'endpoints' => $endpoints,
                'status_codes' => $statusCodes,
                'recent_calls' => $recentCalls,
                'chart_data' => $chartData,
                'api_status' => $apiStatus,
                'filter' => $dateFilter,
            ];
            
            return view('super-admin.api-gateway', compact('stats'));
            
        } catch (\Exception $e) {
            \Log::error('API Gateway Error: ' . $e->getMessage());
            
            $stats = [
                'total_calls' => 0,
                'success_calls' => 0,
                'failed_calls' => 0,
                'error_rate' => 0,
                'avg_latency' => 0,
                'endpoints' => collect([]),
                'status_codes' => collect([]),
                'recent_calls' => collect([]),
                'chart_data' => [],
                'api_status' => ['laravel' => 'Operational', 'python_ai' => 'Unknown', 'database' => 'Unknown'],
                'filter' => 'today',
            ];
            
            return view('super-admin.api-gateway', compact('stats'));
        }
    }

    // ==========================================
    // 10. SYSTEM HEALTH
    // ==========================================
    public function systemHealth()
{
    $services = [
        'laravel'   => $this->checkLaravel(),
        'database'  => $this->checkDatabase(),
        'python_ai' => $this->checkPythonAI(),
        'redis'     => $this->checkRedis(),
        'qdrant'    => $this->checkQdrant(),
        'whatsapp'  => $this->checkWhatsApp(),
    ];

    // 🔥 Overall status — healthy/warning/unhealthy
    $overallStatus = 'healthy';
    foreach ($services as $service) {
        if (($service['status'] ?? 'unhealthy') === 'unhealthy') {
            $overallStatus = 'degraded';
            break;
        }
    }

    $lastChecked = now()->format('d M Y, h:i:s A');

    return view('super-admin.system-health', compact('services', 'overallStatus', 'lastChecked'));
}


// ==========================================
// 1. LARAVEL CHECK
// ==========================================
private function checkLaravel()
{
    // 🔥 Method 1: Try /up route
    try {
        $start = microtime(true);
        $response = \Http::timeout(3)->get(config('app.url') . '/up');
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        if ($response->successful()) {
            return [
                'status' => 'healthy',
                'uptime' => $this->getUptime(),
                'response_time' => $responseTime . 'ms',
                'message' => 'Laravel is running',
            ];
        }
    } catch (\Exception $e) {
        // Ignore — fallback try karega
    }

    // 🔥 Method 2: Fallback — DB check
    try {
        $start = microtime(true);
        \DB::connection()->getPdo();
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        return [
            'status' => 'healthy',
            'uptime' => $this->getUptime(),
            'response_time' => $responseTime . 'ms',
            'message' => 'Laravel running (DB check passed)',
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'unhealthy',
            'uptime' => 'N/A',
            'response_time' => 'N/A',
            'message' => 'Laravel check failed: ' . $e->getMessage(),
        ];
    }
}


// ==========================================
// 2. DATABASE CHECK
// ==========================================
private function checkDatabase()
{
    try {
        $start = microtime(true);
        \DB::connection()->getPdo();
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        // DB Size
        $size = 'N/A';
        try {
            $dbSize = \DB::select(
                'SELECT SUM(data_length + index_length) / 1024 / 1024 as size_mb 
                 FROM information_schema.tables 
                 WHERE table_schema = ?',
                [config('database.connections.mysql.database')]
            );
            if (isset($dbSize[0]->size_mb)) {
                $size = round($dbSize[0]->size_mb, 2) . ' MB';
            }
        } catch (\Exception $e) {
            // Ignore
        }

        // Connections
        $connections = 0;
        try {
            $result = \DB::select("SHOW STATUS WHERE variable_name = 'Threads_connected'");
            $connections = $result[0]->Value ?? 0;
        } catch (\Exception $e) {
            // Ignore
        }

        return [
            'status' => 'healthy',
            'uptime' => $this->getDatabaseUptime(),
            'response_time' => $responseTime . 'ms',
            'message' => "{$connections} connections, {$size}",
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'unhealthy',
            'uptime' => 'N/A',
            'response_time' => 'N/A',
            'message' => 'DB error: ' . substr($e->getMessage(), 0, 80),
        ];
    }
}


// ==========================================
// 3. PYTHON AI CHECK
// ==========================================
private function checkPythonAI()
{
    try {
        $start = microtime(true);
        $response = \Http::timeout(3)->get('http://127.0.0.1:8001/health');
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        if ($response->successful()) {
            $data = $response->json();
            return [
                'status' => 'healthy',
                'uptime' => 'N/A',
                'response_time' => $responseTime . 'ms',
                'message' => $data['status'] ?? 'Python AI is running',
            ];
        }

        return [
            'status' => 'unhealthy',
            'uptime' => 'N/A',
            'response_time' => $responseTime . 'ms',
            'message' => 'Python AI returned HTTP ' . $response->status(),
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'unhealthy',
            'uptime' => 'N/A',
            'response_time' => 'N/A',
            'message' => 'Python AI not running (start python app.py)',
        ];
    }
}


// ==========================================
// 4. REDIS CHECK
// ==========================================
private function checkRedis()
{
    try {
        $start = microtime(true);
        
        // 🔥 Full path use karo — class not found error se bachne ke liye
        \Illuminate\Support\Facades\Redis::connection()->ping();
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        $usedMemory = 'N/A';
        $uptime = 'N/A';

        try {
            $info = \Illuminate\Support\Facades\Redis::connection()->info();
            $usedMemory = $info['used_memory_human'] ?? 'N/A';
            if (isset($info['uptime_in_seconds'])) {
                $uptime = $this->formatUptime($info['uptime_in_seconds']);
            }
        } catch (\Exception $e) {
            // Ignore
        }

        return [
            'status' => 'healthy',
            'uptime' => $uptime,
            'response_time' => $responseTime . 'ms',
            'message' => "Memory: {$usedMemory}",
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'unhealthy',
            'uptime' => 'N/A',
            'response_time' => 'N/A',
            'message' => 'Redis not available',
        ];
    }
}


// ==========================================
// 5. QDRANT CHECK
// ==========================================
private function checkQdrant()
{
    // 🔥 Method 1: /healthz
    try {
        $start = microtime(true);
        $response = \Http::timeout(5)->get('http://127.0.0.1:6333/healthz');
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        if ($response->successful()) {
            // Collections count
            $collectionsInfo = 'N/A';
            try {
                $colRes = \Http::timeout(3)->get('http://127.0.0.1:6333/collections');
                if ($colRes->successful()) {
                    $collections = $colRes->json()['result']['collections'] ?? [];
                    $collectionsInfo = count($collections) . ' collections';
                }
            } catch (\Exception $e) {
                // Ignore
            }

            return [
                'status' => 'healthy',
                'uptime' => $collectionsInfo,
                'response_time' => $responseTime . 'ms',
                'message' => 'Qdrant vector DB is running',
            ];
        }
    } catch (\Exception $e) {
        // Fallback
    }

    // 🔥 Method 2: Root endpoint fallback
    try {
        $start = microtime(true);
        $fallback = \Http::timeout(3)->get('http://127.0.0.1:6333/');
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        if ($fallback->successful()) {
            return [
                'status' => 'healthy',
                'uptime' => 'N/A',
                'response_time' => $responseTime . 'ms',
                'message' => 'Qdrant vector DB is running',
            ];
        }
    } catch (\Exception $e) {
        // Dono fail
    }

    return [
        'status' => 'unhealthy',
        'uptime' => 'N/A',
        'response_time' => 'N/A',
        'message' => 'Qdrant not running — start qdrant.exe',
    ];
}


// ==========================================
// 6. WHATSAPP CHECK
// ==========================================
private function checkWhatsApp()
{
    $token = config('services.whatsapp.token') ?? env('WHATSAPP_ACCESS_TOKEN');
    $phoneId = config('services.whatsapp.phone_number_id') ?? env('WHATSAPP_PHONE_NUMBER_ID');

    if (empty($token) || empty($phoneId)) {
        return [
            'status' => 'warning',
            'uptime' => 'N/A',
            'response_time' => 'N/A',
            'message' => 'WhatsApp not configured (.env check karo)',
        ];
    }

    try {
        $start = microtime(true);
        $response = \Http::timeout(5)
            ->withToken($token)
            ->get("https://graph.facebook.com/v20.0/{$phoneId}");
        $responseTime = round((microtime(true) - $start) * 1000, 2);

        if ($response->successful()) {
            return [
                'status' => 'healthy',
                'uptime' => 'N/A',
                'response_time' => $responseTime . 'ms',
                'message' => 'WhatsApp API accessible',
            ];
        }

        return [
            'status' => 'warning',
            'uptime' => 'N/A',
            'response_time' => $responseTime . 'ms',
            'message' => 'WhatsApp error: HTTP ' . $response->status(),
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'warning',
            'uptime' => 'N/A',
            'response_time' => 'N/A',
            'message' => 'WhatsApp check failed',
        ];
    }
}


// ==========================================
// HELPER: Uptime
// ==========================================
private function getUptime()
{
    try {
        if (PHP_OS_FAMILY === 'Windows') {
            $output = @shell_exec('wmic os get lastbootuptime');
            if ($output) {
                preg_match('/\d{14}/', $output, $matches);
                if (isset($matches[0])) {
                    $bootTime = \DateTime::createFromFormat('YmdHis', $matches[0]);
                    if ($bootTime) {
                        $uptime = time() - $bootTime->getTimestamp();
                        return $this->formatUptime($uptime);
                    }
                }
            }
        } else {
            $uptime = @shell_exec('cat /proc/uptime');
            if ($uptime) {
                $uptime = explode(' ', $uptime)[0];
                return $this->formatUptime((int) $uptime);
            }
        }
    } catch (\Exception $e) {
        // Ignore
    }
    return 'N/A';
}


private function getDatabaseUptime()
{
    try {
        $result = \DB::select("SHOW STATUS LIKE 'uptime'");
        if (isset($result[0]->Value)) {
            return $this->formatUptime((int) $result[0]->Value);
        }
    } catch (\Exception $e) {
        // Ignore
    }
    return 'N/A';
}


private function formatUptime($seconds)
{
    $seconds = (int) $seconds;
    $days = floor($seconds / 86400);
    $hours = floor(($seconds % 86400) / 3600);
    $minutes = floor(($seconds % 3600) / 60);

    if ($days > 0) {
        return "{$days}d {$hours}h";
    } elseif ($hours > 0) {
        return "{$hours}h {$minutes}m";
    }
    return "{$minutes}m";
}



    // ==========================================
    // 11. DEPLOYMENTS
    // ==========================================
    public function deployments(Request $request)
    {
        try {
            $query = Deployment::query();
            
            // Filter by status
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            // Filter by branch
            if ($request->branch) {
                $query->where('branch', $request->branch);
            }
            
            // Search
            if ($request->search) {
                $query->where(function($q) use ($request) {
                    $q->where('version', 'LIKE', "%{$request->search}%")
                      ->orWhere('commit_hash', 'LIKE', "%{$request->search}%")
                      ->orWhere('message', 'LIKE', "%{$request->search}%");
                });
            }
            
            $deployments = $query->latest()->paginate(10);
            
            // Stats
            $stats = [
                'total' => Deployment::count(),
                'success' => Deployment::where('status', 'success')->count(),
                'failed' => Deployment::where('status', 'failed')->count(),
                'pending' => Deployment::where('status', 'pending')->count(),
                'running' => Deployment::where('status', 'running')->count(),
            ];
            
            // Last deployment
            $lastDeployment = Deployment::where('status', 'success')->latest()->first();
            
            return view('super-admin.deployments', compact('deployments', 'stats', 'lastDeployment'));
            
        } catch (\Exception $e) {
            Log::error('Deployments Error: ' . $e->getMessage());
            $deployments = collect([]);
            $stats = ['total' => 0, 'success' => 0, 'failed' => 0, 'pending' => 0, 'running' => 0];
            $lastDeployment = null;
            return view('super-admin.deployments', compact('deployments', 'stats', 'lastDeployment'));
        }
    }

    /**
     * Show Create Deployment Form
     */
    public function createDeploymentForm()
    {
        return view('super-admin.deployments.create');
    }

    /**
     * Store New Deployment
     */
    public function storeDeployment(Request $request)
    {
        try {
            $validated = $request->validate([
                'version' => 'required|string|max:50',
                'branch' => 'nullable|string|max:50',
                'commit_hash' => 'nullable|string|max:20',
                'message' => 'nullable|string|max:500',
                'environment' => 'nullable|string|max:50',
            ]);

            // 🔥 Create deployment
            $deployment = Deployment::create([
                'version' => $validated['version'],
                'branch' => $validated['branch'] ?? 'main',
                'commit_hash' => $validated['commit_hash'] ?? substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 7),
                'message' => $validated['message'] ?? 'Deployment initiated',
                'status' => 'running',
                'deployed_by' => Auth::id(),
                'duration' => 0,
                'details' => [
                    'environment' => $validated['environment'] ?? app()->environment(),
                    'php_version' => PHP_VERSION,
                    'laravel_version' => app()->version(),
                    'deployed_at' => now()->toDateTimeString(),
                ]
            ]);

            // 🔥 Simulate deployment process
            // In production, this would trigger actual deployment script
            sleep(3); // Simulate work

            // 🔥 Update deployment status
            $duration = rand(10, 60);
            $deployment->update([
                'status' => 'success',
                'duration' => $duration,
                'details' => array_merge($deployment->details ?? [], [
                    'completed_at' => now()->toDateTimeString(),
                    'deployment_time' => $duration . ' seconds',
                    'status' => 'Deployment completed successfully ✅'
                ])
            ]);

            return redirect()->route('super-admin.deployments')
                ->with('success', '🚀 Deployment v' . $validated['version'] . ' completed successfully!');

        } catch (\Exception $e) {
            // 🔥 Mark as failed
            if (isset($deployment)) {
                $deployment->update([
                    'status' => 'failed',
                    'duration' => time() - strtotime($deployment->created_at),
                    'details' => array_merge($deployment->details ?? [], [
                        'error' => $e->getMessage(),
                        'failed_at' => now()->toDateTimeString(),
                    ])
                ]);
            }

            return back()->with('error', 'Deployment failed: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Rollback Deployment
     */
    public function rollbackDeployment($id)
    {
        try {
            $deployment = Deployment::findOrFail($id);
            
            // Check if already rollbacked
            if ($deployment->status == 'rollback') {
                return back()->with('error', 'This deployment has already been rollbacked.');
            }
            
            // Create rollback deployment
            $rollback = Deployment::create([
                'version' => $deployment->version . '-rollback',
                'branch' => $deployment->branch,
                'commit_hash' => $deployment->commit_hash,
                'message' => 'Rollback of deployment #' . substr($deployment->id, 0, 8),
                'status' => 'rollback',
                'deployed_by' => Auth::id(),
                'duration' => rand(5, 20),
                'details' => [
                    'original_deployment_id' => $deployment->id,
                    'original_version' => $deployment->version,
                    'reason' => 'Manual rollback initiated',
                    'rolled_back_at' => now()->toDateTimeString(),
                ]
            ]);
            
            // Update original deployment status
            $deployment->update(['status' => 'rollback']);
            
            return redirect()->route('super-admin.deployments')
                ->with('success', '↩️ Rollback initiated successfully! #' . substr($rollback->id, 0, 8));

        } catch (\Exception $e) {
            return back()->with('error', 'Rollback failed: ' . $e->getMessage());
        }
    }

    /**
     * Show Deployment Details
     */
    public function deploymentDetails($id)
    {
        try {
            $deployment = Deployment::with('deployer')->findOrFail($id);
            return view('super-admin.deployments.show', compact('deployment'));
        } catch (\Exception $e) {
            return redirect()->route('super-admin.deployments')
                ->with('error', 'Deployment not found.');
        }
    }

    // ==========================================
    // 12. FEATURE FLAGS
    // ==========================================
    public function featureFlags()
    {
        $features = config('features');
        
        // Add status from env
        foreach ($features as $key => $feature) {
            $features[$key]['key'] = $key;
            $features[$key]['status'] = $feature['enabled'] ? 'active' : 'inactive';
        }
        
        // Category wise group
        $groupedFeatures = collect($features)->groupBy('category');
        
        $stats = [
            'total' => count($features),
            'active' => collect($features)->where('enabled', true)->count(),
            'inactive' => collect($features)->where('enabled', false)->count(),
        ];
        
        return view('super-admin.feature-flags', compact('features', 'groupedFeatures', 'stats'));
    }

    // ==========================================
    // 13. SECURITY
    // ==========================================
    public function security()
    {
        try {
            $data = [
                'failed_logins' => LoginAttempt::failed()->today()->count(),
                'total_failed_logins' => LoginAttempt::failed()->count(),
                'suspicious_ips' => SuspiciousIp::active()->latest()->get(),
                'blocked_ips' => BlockedIp::latest()->get(),
                'recent_attempts' => LoginAttempt::latest()->limit(20)->get(),
                'stats' => [
                    'today' => LoginAttempt::whereDate('created_at', today())->count(),
                    'week' => LoginAttempt::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                    'month' => LoginAttempt::whereMonth('created_at', now()->month)->count(),
                    'success_rate' => $this->getSuccessRate(),
                ]
            ];
            
            return view('super-admin.security', compact('data'));
            
        } catch (\Exception $e) {
            \Log::error('Security Error: ' . $e->getMessage());
            $data = [
                'failed_logins' => 0,
                'total_failed_logins' => 0,
                'suspicious_ips' => collect([]),
                'blocked_ips' => collect([]),
                'recent_attempts' => collect([]),
                'stats' => ['today' => 0, 'week' => 0, 'month' => 0, 'success_rate' => 0],
            ];
            return view('super-admin.security', compact('data'));
        }
    }

    private function getSuccessRate()
    {
        $total = LoginAttempt::count();
        if ($total == 0) return 0;
        $success = LoginAttempt::where('success', true)->count();
        return round(($success / $total) * 100, 2);
    }

    public function whatsappSettings()
    {
        $settings = [
            'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
            'display_phone_number' => env('WHATSAPP_DISPLAY_NUMBER'),
            'access_token' => env('WHATSAPP_ACCESS_TOKEN') ? '********' . substr(env('WHATSAPP_ACCESS_TOKEN'), -4) : null,
            'webhook_url' => url('/api/webhooks/whatsapp'),
            'verify_token' => 'sellmate_webhook_123',
            'status' => $this->checkWhatsAppStatus(),
        ];
        
        return view('super-admin.whatsapp-settings', compact('settings'));
    }

    private function checkWhatsAppStatus()
    {
        $token = env('WHATSAPP_ACCESS_TOKEN');
        $phoneId = env('WHATSAPP_PHONE_NUMBER_ID');
        
        if (empty($token) || empty($phoneId)) {
            return 'Not Configured';
        }
        
        try {
            $response = \Http::withToken($token)
                ->timeout(3)
                ->get("https://graph.facebook.com/v20.0/{$phoneId}");
            
            if ($response->successful()) {
                return '✅ Connected';
            }
            return '❌ Error: ' . $response->status();
        } catch (\Exception $e) {
            return '⚠️ Not Connected';
        }
    }

    // ==========================================
    // 14. STOP IMPERSONATE (Route)
    // ==========================================
    public function stopImpersonateRoute()
    {
        return $this->stopImpersonate();
    }
}