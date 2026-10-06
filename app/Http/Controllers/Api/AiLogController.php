<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AiLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AiLogController extends Controller
{
    public function store(Request $request)
    {
        $log = AiLog::create([
            'id' => (string) Str::uuid(),
            'tenant_id' => $request->tenant_id,
            'conversation_id' => $request->conversation_id ?? null,
            'intent' => $request->intent ?? 'general',
            'sentiment' => $request->sentiment ?? 'neutral',
            'tokens_used' => $request->tokens_used ?? 0,
            'cost' => $request->cost ?? 0,
            'model_used' => $request->model_used ?? 'unknown',
            'response_time' => $request->response_time ?? 0,
            'metadata' => $request->metadata ?? [],
        ]);

        return response()->json([
            'success' => true,
            'id' => $log->id
        ]);
    }

    public function index(Request $request)
    {
        $tenantId = $request->tenant_id;
        
        $query = AiLog::query();
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        $logs = $query->latest()->paginate(20);
        
        return response()->json($logs);
    }

    public function tenantLogs($tenantId)
    {
        $logs = AiLog::where('tenant_id', $tenantId)
            ->latest()
            ->paginate(20);
            
        return response()->json($logs);
    }

    public function stats(Request $request)
    {
        $tenantId = $request->tenant_id;
        
        $query = AiLog::query();
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        $stats = [
            'total_messages' => $query->count(),
            'total_tokens' => $query->sum('tokens_used'),
            'total_cost' => $query->sum('cost'),
            'average_response_time' => round($query->avg('response_time') ?? 0, 2),
            'today_messages' => (clone $query)->whereDate('created_at', today())->count(),
            'this_month_messages' => (clone $query)->whereMonth('created_at', now()->month)->count(),
            'intent_breakdown' => (clone $query)
                ->select('intent', \DB::raw('count(*) as count'))
                ->groupBy('intent')
                ->get(),
        ];
        
        return response()->json($stats);
    }
}