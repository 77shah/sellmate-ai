<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Business;
use App\Models\WhatsAppNumber;
use App\Models\UnansweredQuery;
use App\Models\Customer;

class WhatsAppWebhookController extends Controller
{
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = "sellmate_webhook_123";

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('WhatsApp Verified Successfully');
            return response($challenge, 200);
        }

        return response('Verification failed', 403);
    }

    public function webhook(Request $request)
    {
        Log::info('POST WEBHOOK HIT');

        if (isset($request['entry'][0]['changes'][0]['value']['messages'][0])) {
            $value = $request['entry'][0]['changes'][0]['value'];
            $message = $value['messages'][0];

            $messageId = $message['id'] ?? null;
            
            if ($messageId) {
                $alreadyProcessed = Cache::has('wa_msg_' . $messageId);
                
                if ($alreadyProcessed) {
                    Log::info('⚠️ Duplicate skipped: ' . $messageId);
                    return response()->json(['status' => 'duplicate']);
                }
                
                Cache::put('wa_msg_' . $messageId, true, 3600);
            }

            $phoneNumberId = $value['metadata']['phone_number_id'] ?? null;

            $this->handleMessage($message, $phoneNumberId);
        }

        return response()->json(['status' => 'ok']);
    }

    protected function handleMessage($message, $phoneNumberId = null)
    {
        $from = $message['from'] ?? null;
        $text = $message['text']['body'] ?? null;

        if (!$from || !$text) return;

        Log::info("From: " . $from);
        Log::info("Text: " . $text);

        $tenantId = $this->getTenantId($phoneNumberId, $from);
        Log::info("✅ Tenant: " . $tenantId);

        // Customer name
        $customerName = null;
        try {
            $customer = Customer::where('tenant_id', $tenantId)
                ->where('phone', $from)
                ->first();
            $customerName = $customer->name ?? null;
        } catch (\Exception $e) {}

        // 🔥 CONVERSATION HISTORY — pichli 10 messages lo
        $history = $this->getConversationHistory($tenantId, $from);

        // AI Call
        $aiResult = $this->callAI($text, $tenantId, $customerName, $history);
        $reply = $aiResult['reply'];
        $isAnswered = $aiResult['is_answered'];
        $shouldEscalate = $aiResult['should_escalate'] ?? !$isAnswered;

        // 🔥 Save to conversation history
        $this->saveToHistory($tenantId, $from, 'user', $text);
        $this->saveToHistory($tenantId, $from, 'assistant', $reply);

        // Save unanswered query
        if ($shouldEscalate || !$isAnswered) {
            try {
                $existing = UnansweredQuery::where('tenant_id', $tenantId)
                    ->where('customer_number', $from)
                    ->where('question', $text)
                    ->where('created_at', '>=', now()->subHour())
                    ->first();
                
                if (!$existing) {
                    UnansweredQuery::create([
                        'tenant_id' => $tenantId,
                        'customer_number' => $from,
                        'customer_name' => $customerName,
                        'question' => $text,
                        'ai_reply' => $reply,
                        'status' => 'pending',
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Unanswered save failed: ' . $e->getMessage());
            }
        }

        $this->sendWhatsAppMessage($from, $reply, $phoneNumberId);
    }

    /**
     * 🔥 Get conversation history from cache
     */
    protected function getConversationHistory($tenantId, $customerNumber)
    {
        $key = 'conv_' . $tenantId . '_' . $customerNumber;
        $history = Cache::get($key, []);
        
        // Return last 10 messages
        return array_slice($history, -10);
    }

    /**
     * 🔥 Save message to conversation history
     */
    protected function saveToHistory($tenantId, $customerNumber, $role, $content)
    {
        $key = 'conv_' . $tenantId . '_' . $customerNumber;
        $history = Cache::get($key, []);
        
        $history[] = [
            'role' => $role,
            'content' => $content,
            'timestamp' => now()->toDateTimeString(),
        ];
        
        // Keep only last 20 messages
        if (count($history) > 20) {
            $history = array_slice($history, -20);
        }
        
        // Save for 2 hours
        Cache::put($key, $history, 7200);
    }

    protected function callAI($message, $tenantId, $customerName = null, $history = [])
    {
        try {
            Log::info('📤 Sending to AI...');

            $payload = [
                "tenant_id" => $tenantId,
                "conversation_id" => "whatsapp_" . time(),
                "message" => $message,
                "customer_name" => $customerName,
                "conversation_history" => $history,
            ];

            $response = Http::timeout(30)->post(
                'http://127.0.0.1:8001/api/ai/chat',
                $payload
            );

            $data = $response->json();
            
            return [
                'reply' => $data['reply'] ?? "Ye information mere paas abhi nahi hai. Main aapki query team ko forward kar rahi hun — wo aapko jaldi contact karenge. 😊",
                'is_answered' => !($data['should_escalate'] ?? false),
                'should_escalate' => $data['should_escalate'] ?? false,
            ];
        } catch (\Exception $e) {
            Log::error("AI ERROR: " . $e->getMessage());
            return [
                'reply' => "Ye information mere paas abhi nahi hai. Main aapki query team ko forward kar rahi hun — wo aapko jaldi contact karenge. 😊",
                'is_answered' => false,
                'should_escalate' => true,
            ];
        }
    }

    protected function getTenantId($phoneNumberId, $customerNumber)
    {
        if ($phoneNumberId) {
            try {
                $whatsapp = WhatsAppNumber::where('phone_number_id', $phoneNumberId)
                    ->where('is_active', true)
                    ->first();
                if ($whatsapp) return $whatsapp->tenant_id;
            } catch (\Exception $e) {}
        }

        if ($phoneNumberId) {
            try {
                $businesses = Business::all();
                foreach ($businesses as $business) {
                    $settings = $business->settings ?? [];
                    if (isset($settings['phone_number_id']) &&
                        $settings['phone_number_id'] == $phoneNumberId) {
                        return $business->tenant_id;
                    }
                }
            } catch (\Exception $e) {}
        }

        return env('DEFAULT_TENANT_ID', 'tenant_PMQHm8');
    }

    protected function sendWhatsAppMessage($to, $message, $phoneNumberId = null)
    {
        $token = env('WHATSAPP_ACCESS_TOKEN');
        $phoneId = $phoneNumberId ?? env('WHATSAPP_PHONE_NUMBER_ID');

        if ($phoneNumberId) {
            try {
                $whatsapp = WhatsAppNumber::where('phone_number_id', $phoneNumberId)
                    ->where('is_active', true)
                    ->first();
                if ($whatsapp && !empty($whatsapp->settings['access_token'])) {
                    $token = $whatsapp->settings['access_token'];
                }
            } catch (\Exception $e) {}
        }

        Log::info('📱 Sending WhatsApp Message');

        $response = Http::withToken($token)
            ->acceptJson()
            ->post(
                "https://graph.facebook.com/v20.0/{$phoneId}/messages",
                [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => $to,
                    "type" => "text",
                    "text" => [
                        "preview_url" => false,
                        "body" => $message
                    ]
                ]
            );

        Log::info('HTTP Status: ' . $response->status());
    }
}