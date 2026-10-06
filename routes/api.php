<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppWebhookController;
use App\Http\Controllers\Api\AiLogController;

// 🔥 WhatsApp Webhook Routes
Route::get('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'verify']);
Route::post('/webhooks/whatsapp', [WhatsAppWebhookController::class, 'webhook']);

// AI Logs Routes
Route::post('/ai/logs', [AiLogController::class, 'store']);
Route::get('/ai/logs', [AiLogController::class, 'index']);
Route::get('/ai/logs/stats', [AiLogController::class, 'stats']);
Route::get('/ai/logs/tenant/{tenantId}', [AiLogController::class, 'tenantLogs']);