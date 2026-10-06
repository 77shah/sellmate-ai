<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIController extends Controller
{
    protected $aiServiceUrl = 'http://localhost:8001';

    public function chat(Request $request)
    {
        $response = Http::post($this->aiServiceUrl . '/api/ai/chat', [
            'tenant_id' => $request->tenant_id ?? 'tenant_001',
            'conversation_id' => $request->conversation_id ?? 'conv_001',
            'message' => $request->message,
            'customer_name' => $request->customer_name ?? null
        ]);

        return $response->json();
    }

    public function health()
    {
        $response = Http::get($this->aiServiceUrl . '/health');

        return $response->json();
    }
}