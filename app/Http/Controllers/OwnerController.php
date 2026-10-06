<?php
// app/Http/Controllers/OwnerController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Conversation;
use App\Models\Product;
use App\Models\KnowledgeBase;
use App\Models\Workflow;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AiLog;
use App\Models\WhatsAppNumber;

class OwnerController extends Controller
{
    // ==========================================
    // 1. DASHBOARD
    // ==========================================
    public function dashboard()
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    
    // ==========================================
    // BASIC STATS
    // ==========================================
    $totalCustomers = Customer::where('tenant_id', $tenantId)->count();
    $totalOrders = Order::where('tenant_id', $tenantId)->count();
    $totalRevenue = Order::where('tenant_id', $tenantId)
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    $pendingOrders = Order::where('tenant_id', $tenantId)
        ->where('status', 'pending')
        ->count();
    $activeConversations = Conversation::where('tenant_id', $tenantId)
        ->where('status', 'open')
        ->count();
    $averageLeadScore = Customer::where('tenant_id', $tenantId)->avg('lead_score');
    
    // ==========================================
    // TODAY'S STATS
    // ==========================================
    $todayOrders = Order::where('tenant_id', $tenantId)
        ->whereDate('created_at', today())
        ->count();
    $todayRevenue = Order::where('tenant_id', $tenantId)
        ->whereDate('created_at', today())
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    
    // 🔥 FIXED: messages table me tenant_id nahi hai, conversation se lo
    $todayMessages = \App\Models\Message::whereHas('conversation', function($q) use ($tenantId) {
            $q->where('tenant_id', $tenantId);
        })
        ->whereDate('created_at', today())
        ->count();
    
    // ==========================================
    // THIS MONTH STATS
    // ==========================================
    $monthRevenue = Order::where('tenant_id', $tenantId)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->where('payment_status', 'paid')
        ->sum('total_amount');
    $monthOrders = Order::where('tenant_id', $tenantId)
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();
    
    // ==========================================
    // RECENT ORDERS
    // ==========================================
    $recentOrders = Order::with('customer')
        ->where('tenant_id', $tenantId)
        ->latest()
        ->limit(10)
        ->get();
    
    // ==========================================
    // RECENT CONVERSATIONS
    // ==========================================
    $recentConversations = Conversation::with('customer')
        ->where('tenant_id', $tenantId)
        ->latest()
        ->limit(5)
        ->get();
    
    // ==========================================
    // WHATSAPP NUMBERS
    // ==========================================
    $whatsappNumbers = \App\Models\WhatsAppNumber::where('tenant_id', $tenantId)->get();
    $whatsappCount = $whatsappNumbers->count();
    $whatsappActive = $whatsappNumbers->where('is_active', true)->count();
    
    // ==========================================
    // KNOWLEDGE BASE
    // ==========================================
    $knowledgeCount = \App\Models\KnowledgeBase::where('tenant_id', $tenantId)->count();
    $knowledgeProcessed = \App\Models\KnowledgeBase::where('tenant_id', $tenantId)
        ->where('status', 'processed')
        ->count();
    
    // ==========================================
    // UNANSWERED QUERIES
    // ==========================================
    $unansweredCount = \App\Models\UnansweredQuery::where('tenant_id', $tenantId)
        ->where('status', 'pending')
        ->count();
    
    // ==========================================
    // SUBSCRIPTION
    // ==========================================
    $subscription = \App\Models\Subscription::where('tenant_id', $tenantId)
        ->where('status', 'active')
        ->latest()
        ->first();
    $plan = Auth::user()->currentPlan;
    
    // ==========================================
    // CHART DATA — Last 7 days
    // ==========================================
    $chartLabels = [];
    $chartRevenue = [];
    $chartOrders = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $chartLabels[] = $date->format('d M');
        
        $chartRevenue[] = Order::where('tenant_id', $tenantId)
            ->whereDate('created_at', $date)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        
        $chartOrders[] = Order::where('tenant_id', $tenantId)
            ->whereDate('created_at', $date)
            ->count();
    }
    
    // ==========================================
    // TOP PRODUCTS
    // ==========================================
    $topProducts = \App\Models\Product::where('tenant_id', $tenantId)
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();
    
    return view('owner.dashboard', compact(
        'totalCustomers',
        'totalOrders',
        'totalRevenue',
        'pendingOrders',
        'activeConversations',
        'averageLeadScore',
        'todayOrders',
        'todayRevenue',
        'todayMessages',
        'monthRevenue',
        'monthOrders',
        'recentOrders',
        'recentConversations',
        'whatsappNumbers',
        'whatsappCount',
        'whatsappActive',
        'knowledgeCount',
        'knowledgeProcessed',
        'unansweredCount',
        'subscription',
        'plan',
        'chartLabels',
        'chartRevenue',
        'chartOrders',
        'topProducts'
    ));
}
    // ==========================================
    // 2. INBOX
    // ==========================================
    public function inbox(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $query = Conversation::with('customer')
            ->where('tenant_id', $tenantId)
            ->withCount('messages');
        
        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->channel) {
            $query->where('channel', $request->channel);
        }
        if ($request->search) {
            $query->whereHas('customer', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                ->orWhere('phone', 'LIKE', "%{$request->search}%");
            });
        }
        
        // 🔥 Unread count (Open conversations)
        $unreadCount = Conversation::where('tenant_id', $tenantId)
            ->where('status', 'open')
            ->count();
        
        $conversations = $query->latest('last_message_at')->paginate(20);
        
        return view('owner.inbox', compact('conversations', 'unreadCount'));
    }

    public function inboxShow($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $conversation = Conversation::with(['customer', 'messages'])
            ->where('tenant_id', $tenantId)
            ->findOrFail($id);
        
        return view('owner.inbox-show', compact('conversation'));
    }

    // ==========================================
    // 3. CRM
    // ==========================================
    public function crm(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $query = Customer::where('tenant_id', $tenantId);
        
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%");
            });
        }
        
        if ($request->stage) {
            $query->where('stage', $request->stage);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $customers = $query->latest()->paginate(15);
        
        // Stage counts
        $stages = [
            'new' => Customer::where('tenant_id', $tenantId)->where('stage', 'new')->count(),
            'contacted' => Customer::where('tenant_id', $tenantId)->where('stage', 'contacted')->count(),
            'qualified' => Customer::where('tenant_id', $tenantId)->where('stage', 'qualified')->count(),
            'order_placed' => Customer::where('tenant_id', $tenantId)->where('stage', 'order_placed')->count(),
            'closed' => Customer::where('tenant_id', $tenantId)->where('stage', 'closed')->count(),
        ];
        
        // Total customers
        $totalCustomers = Customer::where('tenant_id', $tenantId)->count();
        
        return view('owner.crm', compact('customers', 'stages', 'totalCustomers'));
    }

    // ==========================================
    // 2. CRM - CREATE
    // ==========================================
    public function crmCreate()
    {
        return view('owner.crm-create');
    }

    public function crmStore(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'stage' => 'required|in:new,contacted,qualified,order_placed,closed',
            'lead_score' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        
        Customer::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'stage' => $validated['stage'] ?? 'new',
            'lead_score' => $validated['lead_score'] ?? 0,
            'tags' => $validated['tags'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'active',
        ]);
        
        return redirect()->route('owner.crm')
            ->with('success', 'Customer added successfully!');
    }

    // ==========================================
    // 3. CRM - EDIT
    // ==========================================
    public function crmEdit($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);
        return view('owner.crm-edit', compact('customer'));
    }

    public function crmUpdate(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'stage' => 'required|in:new,contacted,qualified,order_placed,closed',
            'lead_score' => 'nullable|integer|min:0|max:100',
            'tags' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        
        $customer->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'address' => $validated['address'] ?? null,
            'stage' => $validated['stage'] ?? 'new',
            'lead_score' => $validated['lead_score'] ?? 0,
            'tags' => $validated['tags'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);
        
        return redirect()->route('owner.crm')
            ->with('success', 'Customer updated successfully!');
    }

    // ==========================================
    // 4. CRM - DELETE
    // ==========================================
    public function crmDelete($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $customer = Customer::where('tenant_id', $tenantId)->findOrFail($id);
        $customer->delete();
        
        return redirect()->route('owner.crm')
            ->with('success', 'Customer deleted successfully!');
    }

    // ==========================================
    // 5. CRM - VIEW
    // ==========================================
    public function crmShow($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $customer = Customer::where('tenant_id', $tenantId)
            ->with(['orders', 'conversations'])
            ->findOrFail($id);
        
        return view('owner.crm-show', compact('customer'));
    }

    public function unansweredQueries()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $queries = \App\Models\UnansweredQuery::where('tenant_id', $tenantId)
            ->latest()
            ->paginate(20);
        
        $stats = [
            'pending' => \App\Models\UnansweredQuery::where('tenant_id', $tenantId)
                ->where('status', 'pending')->count(),
            'resolved' => \App\Models\UnansweredQuery::where('tenant_id', $tenantId)
                ->where('status', 'resolved')->count(),
            'total' => \App\Models\UnansweredQuery::where('tenant_id', $tenantId)->count(),
        ];
        
        return view('owner.unanswered-queries', compact('queries', 'stats'));
    }

    public function replyToQuery(Request $request, $id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    
    $query = \App\Models\UnansweredQuery::where('tenant_id', $tenantId)->findOrFail($id);
    
    $validated = $request->validate([
        'reply' => 'required|string|max:1000',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        'attachment_url' => 'nullable|url|max:500',
    ]);
    
    // ==========================================
    // 🔥 WhatsApp Config
    // ==========================================
    $whatsappConfig = WhatsAppNumber::where('tenant_id', $tenantId)
        ->where('is_default', true)
        ->first() 
        ?? WhatsAppNumber::where('tenant_id', $tenantId)->first();
    
    $token = $whatsappConfig->settings['access_token'] 
        ?? env('WHATSAPP_ACCESS_TOKEN');
    $phoneId = $whatsappConfig->phone_number_id 
        ?? env('WHATSAPP_PHONE_NUMBER_ID');
    
    // ==========================================
    // 🔥 Images Save Karo — public/owner-replies/{tenant_id}/
    // ==========================================
    $savedImagePaths = [];      // DB me save karne ke liye (relative path)
    $savedImageUrls = [];        // WhatsApp pe bhejne ke liye (full URL)
    
    if ($request->hasFile('images')) {
        // 🔥 Public folder me direct path
        $publicPath = public_path('owner-replies/' . $tenantId);
        
        // Folder create karo agar nahi hai
        if (!file_exists($publicPath)) {
            mkdir($publicPath, 0755, true);
        }
        
        foreach ($request->file('images') as $image) {
            if (!$image->isValid()) {
                \Log::warning('Invalid image upload skipped');
                continue;
            }
            
            // 🔥 Unique filename
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            
            // 🔥 Move to public/owner-replies/{tenant_id}/
            $image->move($publicPath, $filename);
            
            // Relative path for DB: owner-replies/{tenant_id}/{filename}
            $relativePath = 'owner-replies/' . $tenantId . '/' . $filename;
            $savedImagePaths[] = $relativePath;
            
            // Full URL for WhatsApp
            $savedImageUrls[] = asset($relativePath);
            
            \Log::info('Image saved to public', [
                'relative_path' => $relativePath,
                'full_path' => $publicPath . '/' . $filename,
                'url' => asset($relativePath),
            ]);
        }
    }
    
    // ==========================================
    // 🔥 WhatsApp Pe Reply Bhejo
    // ==========================================
    try {
        // 1. Text reply bhejo
        $textResponse = Http::withToken($token)->post(
            "https://graph.facebook.com/v20.0/{$phoneId}/messages",
            [
                "messaging_product" => "whatsapp",
                "recipient_type" => "individual",
                "to" => $query->customer_number,
                "type" => "text",
                "text" => [
                    "preview_url" => true,
                    "body" => $validated['reply']
                ]
            ]
        );
        
        \Log::info('Text reply sent', [
            'status' => $textResponse->status(),
            'customer' => $query->customer_number,
        ]);
        
        // 2. URL bhejo (agar diya)
        if ($request->filled('attachment_url')) {
            $urlResponse = Http::withToken($token)->post(
                "https://graph.facebook.com/v20.0/{$phoneId}/messages",
                [
                    "messaging_product" => "whatsapp",
                    "recipient_type" => "individual",
                    "to" => $query->customer_number,
                    "type" => "text",
                    "text" => [
                        "preview_url" => true,
                        "body" => "📍 " . $request->attachment_url
                    ]
                ]
            );
            
            \Log::info('URL sent', ['status' => $urlResponse->status()]);
        }
        
        // 3. Images WhatsApp pe bhejo — public folder se
        foreach ($savedImagePaths as $index => $relativePath) {
            $fullPath = public_path($relativePath);
            
            if (!file_exists($fullPath)) {
                \Log::error('Image file not found', ['path' => $fullPath]);
                continue;
            }
            
            try {
                // WhatsApp pe upload karo
                $uploadResponse = Http::withToken($token)
                    ->attach(
                        'file', 
                        file_get_contents($fullPath), 
                        basename($relativePath)
                    )
                    ->post(
                        "https://graph.facebook.com/v20.0/{$phoneId}/media",
                        [
                            'messaging_product' => 'whatsapp',
                            'type' => mime_content_type($fullPath) ?: 'image/jpeg',
                        ]
                    );
                
                if ($uploadResponse->successful()) {
                    $mediaId = $uploadResponse->json()['id'];
                    
                    // Image message bhejo
                    $imageResponse = Http::withToken($token)->post(
                        "https://graph.facebook.com/v20.0/{$phoneId}/messages",
                        [
                            "messaging_product" => "whatsapp",
                            "recipient_type" => "individual",
                            "to" => $query->customer_number,
                            "type" => "image",
                            "image" => [
                                "id" => $mediaId,
                                "caption" => ""
                            ]
                        ]
                    );
                    
                    \Log::info('Image sent', [
                        'media_id' => $mediaId,
                        'status' => $imageResponse->status(),
                    ]);
                } else {
                    \Log::error('WhatsApp media upload failed', [
                        'response' => $uploadResponse->body(),
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Image send exception: ' . $e->getMessage());
            }
        }
        
    } catch (\Exception $e) {
        \Log::error('Owner reply failed: ' . $e->getMessage());
        
        // DB me save karo even if WhatsApp fails
        $query->update([
            'status' => 'resolved',
            'owner_reply' => $validated['reply'],
            'owner_reply_images' => $savedImagePaths,
            'owner_reply_url' => $request->attachment_url,
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
            'reply_sent_at' => now(),
        ]);
        
        return back()->with('error', 'Reply save ho gaya but WhatsApp pe send nahi hua: ' . $e->getMessage());
    }
    
    // ==========================================
    // 🔥 Database Me Save Karo
    // ==========================================
    $query->update([
        'status' => 'resolved',
        'owner_reply' => $validated['reply'],
        'owner_reply_images' => $savedImagePaths,      // relative paths
        'owner_reply_url' => $request->attachment_url,
        'resolved_by' => Auth::id(),
        'resolved_at' => now(),
        'reply_sent_at' => now(),
    ]);
    
    $imageCount = count($savedImagePaths);
    $successMsg = '✅ Reply sent successfully to ' . $query->customer_number;
    if ($imageCount > 0) {
        $successMsg .= " with {$imageCount} image(s)";
    }
    
    return back()->with('success', $successMsg);
}

    public function ignoreQuery($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $query = \App\Models\UnansweredQuery::where('tenant_id', $tenantId)->findOrFail($id);
        
        $query->update([
            'status' => 'ignored',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
        ]);
        
        return back()->with('success', 'Query ignored.');
    }

    // ==========================================
    // 4. ORDERS
    // ==========================================
    public function orders(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $query = Order::with('customer')
            ->where('tenant_id', $tenantId);
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->latest()->paginate(20);
        return view('owner.orders', compact('orders'));
    }

    // ==========================================
    // 5. PAYMENTS
    // ==========================================
    public function payments()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $payments = Payment::with('order')
            ->where('tenant_id', $tenantId)
            ->latest()
            ->paginate(15);
            
        $stats = [
            'total' => Payment::where('tenant_id', $tenantId)->sum('amount'),
            'pending' => Payment::where('tenant_id', $tenantId)
                ->where('status', 'pending')
                ->sum('amount'),
            'completed' => Payment::where('tenant_id', $tenantId)
                ->where('status', 'completed')
                ->sum('amount'),
        ];
        
        return view('owner.payments', compact('payments', 'stats'));
    }

    // ==========================================
    // 6. AI SETTINGS
    // ==========================================
    public function aiSettings()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        // Get settings from database
        $settings = DB::table('ai_settings')->where('tenant_id', $tenantId)->first();
        
        // If no settings, create default
        if (!$settings) {
            $settings = (object) [
                'personality' => 'friendly',
                'language' => 'english',
                'business_hours' => '9:00 AM - 6:00 PM',
                'auto_reply_enabled' => true,
                'escalation_rules' => 'Escalate to human when customer is frustrated or asks complex questions.',
                'custom_responses' => null,
            ];
        }
        
        return view('owner.ai-settings', compact('settings'));
    }

    public function updateAiSettings(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $validated = $request->validate([
            'personality' => 'required|in:formal,friendly,sales',
            'language' => 'required|in:english,hindi,hinglish',
            'business_hours' => 'nullable|string|max:255',
            'auto_reply_enabled' => 'nullable',  // 🔥 Changed
            'escalation_rules' => 'nullable|string',
            'custom_responses' => 'nullable|json',
        ]);
        
        // 🔥 Checkbox se value lena
        $autoReply = $request->has('auto_reply_enabled') ? 1 : 0;
        
        $exists = DB::table('ai_settings')->where('tenant_id', $tenantId)->exists();
        
        if ($exists) {
            DB::table('ai_settings')
                ->where('tenant_id', $tenantId)
                ->update([
                    'personality' => $validated['personality'],
                    'language' => $validated['language'],
                    'business_hours' => $validated['business_hours'] ?? '9:00 AM - 6:00 PM',
                    'auto_reply_enabled' => $autoReply,
                    'escalation_rules' => $validated['escalation_rules'] ?? null,
                    'custom_responses' => $validated['custom_responses'] ?? null,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('ai_settings')->insert([
                'tenant_id' => $tenantId,
                'personality' => $validated['personality'],
                'language' => $validated['language'],
                'business_hours' => $validated['business_hours'] ?? '9:00 AM - 6:00 PM',
                'auto_reply_enabled' => $autoReply,
                'escalation_rules' => $validated['escalation_rules'] ?? null,
                'custom_responses' => $validated['custom_responses'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        return back()->with('success', 'AI Settings updated successfully!');
    }

    // ==========================================
    // 7. KNOWLEDGE BASE
    // ==========================================
    /**
 * 📚 Knowledge Base — List all documents
 */
public function knowledge()
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    
    // 🔥 paginate() use karo — get() nahi
    $documents = KnowledgeBase::where('tenant_id', $tenantId)
        ->latest()
        ->paginate(15);
    
    return view('owner.knowledge', compact('documents'));
}

/**
 * 📤 Upload Knowledge Document
 * - Plan limit check
 * - Metadata save
 * - AI processing
 */
public function uploadKnowledge(Request $request)
{
    Log::info('🔥 KNOWLEDGE UPLOAD CALLED');
    
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $user = Auth::user();
    $plan = $user->currentPlan;
    
    // 🔥 PLAN LIMITS
    $maxChunks = $plan->knowledge_max_chunks ?? 15;
    $maxChars = $plan->knowledge_max_chars ?? 15000;
    $maxDocuments = $plan->max_documents ?? 2;
    
    // 🔥 CURRENT USAGE
    $existingDocs = KnowledgeBase::where('tenant_id', $tenantId)
        ->whereIn('status', ['processed', 'processing'])
        ->get();
    
    $currentChunks = 0;
    $currentChars = 0;
    foreach ($existingDocs as $doc) {
        $currentChunks += $doc->metadata['chunks_added'] ?? 0;
        $currentChars += $doc->metadata['text_length'] ?? 0;
    }
    $currentDocumentCount = $existingDocs->count();
    
    Log::info("Current: {$currentDocumentCount}/{$maxDocuments} docs, {$currentChunks}/{$maxChunks} chunks");
    
    // 🔥 CHECK 1: Document limit
    if ($currentDocumentCount >= $maxDocuments) {
        return back()->with('error', 
            "❌ Document limit reached! " .
            "Your {$plan->name} plan allows maximum {$maxDocuments} documents. " .
            "You have {$currentDocumentCount}. " .
            "Please delete old documents or upgrade your plan."
        );
    }
    
    // 🔥 VALIDATION
    $request->validate([
        'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls,csv,txt|max:10240',
        'type' => 'required|in:pdf,excel,website',
        'name' => 'nullable|string|max:255',
    ]);
    
    Log::info('✅ Validation passed');
    
    // 🔥 STORE FILE
    $file = $request->file('file');
    $filename = time() . '_' . $file->getClientOriginalName();
    $path = $file->storeAs('knowledge/' . $tenantId, $filename, 'public');
    
    Log::info('File stored: ' . $path);
    
    // 🔥 EXTRACT TEXT
    $text = $this->extractKnowledgeText($path, $request->type);
    $textLength = strlen($text);
    
    Log::info("Text extracted: {$textLength} chars");
    
    // 🔥 CHECK 2: Char limit
    if ($textLength > $maxChars) {
        Storage::disk('public')->delete($path);
        
        return back()->with('error', 
            "❌ Document too large! " .
            "Your document has " . number_format($textLength) . " characters. " .
            "Your {$plan->name} plan allows maximum " . number_format($maxChars) . " characters per document. " .
            "\n\nPlease: \n" .
            "1. Split your document into smaller parts, OR\n" .
            "2. Upgrade your plan."
        );
    }
    
    // 🔥 ESTIMATE CHUNKS
    $estimatedChunks = ceil($textLength / 500);
    $newTotalChunks = $currentChunks + $estimatedChunks;
    
    // 🔥 CHECK 3: Chunks limit
    if ($newTotalChunks > $maxChunks) {
        Storage::disk('public')->delete($path);
        
        $remainingChunks = max(0, $maxChunks - $currentChunks);
        
        return back()->with('error', 
            "❌ Not enough chunks available! " .
            "This document needs ~{$estimatedChunks} chunks. " .
            "Your {$plan->name} plan has only {$remainingChunks} chunks remaining (out of {$maxChunks}). " .
            "\n\nOptions:\n" .
            "1. Delete old documents, OR\n" .
            "2. Split this document into smaller parts, OR\n" .
            "3. Upgrade your plan."
        );
    }
    
    // 🔥 CREATE RECORD
    $knowledge = KnowledgeBase::create([
        'tenant_id' => $tenantId,
        'name' => $request->name ?? $file->getClientOriginalName(),
        'type' => $request->type,
        'file_path' => $path,
        'status' => 'processing',
        'uploaded_by' => Auth::id(),
        'metadata' => [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'text_length' => $textLength,
            'estimated_chunks' => $estimatedChunks,
            'uploaded_at' => now()->toDateTimeString(),
        ],
    ]);
    
    Log::info('Knowledge created: ' . $knowledge->id);
    
    // 🔥 PROCESS WITH AI
    $this->processKnowledgeWithAI($knowledge, $text);
    
    return redirect()->route('owner.knowledge')
        ->with('success', 
            "✅ Document uploaded successfully! " .
            "~{$estimatedChunks} chunks added. " .
            "Plan usage: " . ($currentChunks + $estimatedChunks) . "/{$maxChunks} chunks, " .
            ($currentDocumentCount + 1) . "/{$maxDocuments} documents."
        );
}

/**
 * 🔥 Extract Text from File
 */
private function extractKnowledgeText($path, $type)
{
    $fullPath = Storage::disk('public')->path($path);
    
    if ($type === 'pdf') {
        return $this->extractKnowledgePdfText($fullPath);
    } elseif ($type === 'excel') {
        return $this->extractKnowledgeExcelText($fullPath);
    }
    
    return file_get_contents($fullPath);
}

/**
 * 🔥 Extract PDF Text
 */
private function extractKnowledgePdfText($path)
{
    try {
        $parser = new \Smalot\PdfParser\Parser();
        $pdf = $parser->parseFile($path);
        $text = $pdf->getText();
        
        $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        $text = preg_replace('/[^\x20-\x7E\x0A\x0D\x09]/u', ' ', $text);
        $text = preg_replace('/\s+/', ' ', trim($text));
        $text = iconv('UTF-8', 'UTF-8//IGNORE', $text);
        
        return $text;
    } catch (\Exception $e) {
        Log::error('PDF Extract Error: ' . $e->getMessage());
        return "Error extracting PDF text: " . $e->getMessage();
    }
}

/**
 * 🔥 Extract Excel Text
 */
private function extractKnowledgeExcelText($path)
{
    try {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($path);
        $text = '';
        foreach ($spreadsheet->getWorksheetIterator() as $worksheet) {
            foreach ($worksheet->getRowIterator() as $row) {
                foreach ($row->getCellIterator() as $cell) {
                    $text .= $cell->getValue() . ' ';
                }
                $text .= "\n";
            }
        }
        return $text;
    } catch (\Exception $e) {
        Log::error('Excel Extract Error: ' . $e->getMessage());
        return "Error extracting Excel text: " . $e->getMessage();
    }
}

/**
 * 🔥 Process with Python AI
 */
private function processKnowledgeWithAI($knowledge, $text)
{
    try {
        Log::info('🔥 PROCESS WITH AI STARTED');
        Log::info('Document: ' . $knowledge->id);
        Log::info('Text length: ' . strlen($text));
        
        // Python health check
        try {
            $health = Http::timeout(5)->get('http://127.0.0.1:8001/health');
            if (!$health->successful()) {
                throw new \Exception('Python not responding');
            }
            Log::info('✅ Python running');
        } catch (\Exception $e) {
            Log::error('❌ Python check failed: ' . $e->getMessage());
            $knowledge->update([
                'status' => 'failed',
                'metadata' => array_merge($knowledge->metadata ?? [], [
                    'error' => 'Python AI not running: ' . $e->getMessage()
                ])
            ]);
            return;
        }
        
        // Send to Python
        $response = Http::timeout(60)->post('http://127.0.0.1:8001/api/ai/knowledge', [
            'tenant_id' => $knowledge->tenant_id,
            'text' => $text,
            'metadata' => [
                'document_id' => $knowledge->id,
                'document_name' => $knowledge->name,
                'type' => $knowledge->type,
            ]
        ]);
        
        Log::info('Python response: ' . $response->status());
        
        if ($response->successful()) {
            $data = $response->json();
            $chunksAdded = $data['chunks_added'] ?? 0;
            
            $knowledge->update([
                'status' => 'processed',
                'metadata' => array_merge($knowledge->metadata ?? [], [
                    'chunks_added' => $chunksAdded,
                    'processed_at' => now()->toDateTimeString(),
                    'ai_status' => 'success'
                ])
            ]);
            
            Log::info('✅ Document processed! Chunks: ' . $chunksAdded);
        } else {
            Log::error('❌ AI failed: ' . $response->status());
            $knowledge->update([
                'status' => 'failed',
                'metadata' => array_merge($knowledge->metadata ?? [], [
                    'ai_status' => 'failed',
                    'error' => 'Status: ' . $response->status()
                ])
            ]);
        }
    } catch (\Exception $e) {
        Log::error('❌ AI Error: ' . $e->getMessage());
        $knowledge->update([
            'status' => 'failed',
            'metadata' => array_merge($knowledge->metadata ?? [], [
                'ai_status' => 'failed',
                'error' => $e->getMessage()
            ])
        ]);
    }
}

    // ==========================================
    // 8. WORKFLOWS
    // ==========================================
    public function workflows()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $workflows = Workflow::where('tenant_id', $tenantId)->latest()->get();
        return view('owner.workflows', compact('workflows'));
    }

    public function storeWorkflow(Request $request)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'trigger' => 'required|string',
            'conditions' => 'nullable|json',
            'actions' => 'required|json',
        ]);
        
        Workflow::create([
            'tenant_id' => $tenantId,
            'name' => $data['name'],
            'trigger' => $data['trigger'],
            'conditions' => $data['conditions'] ?? null,
            'actions' => $data['actions'],
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);
        
        return back()->with('success', 'Workflow created successfully!');
    }

    public function editWorkflow($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $workflow = Workflow::where('tenant_id', $tenantId)->findOrFail($id);
        return response()->json($workflow);
    }

    public function updateWorkflow(Request $request, $id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $workflow = Workflow::where('tenant_id', $tenantId)->findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'trigger' => 'required|string',
            'conditions' => 'nullable|json',
            'actions' => 'required|json',
        ]);
        
        $workflow->update([
            'name' => $data['name'],
            'trigger' => $data['trigger'],
            'conditions' => $data['conditions'] ?? null,
            'actions' => $data['actions'],
        ]);
        
        return back()->with('success', 'Workflow updated successfully!');
    }

    public function toggleWorkflow($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $workflow = Workflow::where('tenant_id', $tenantId)->findOrFail($id);
        
        $workflow->update([
            'is_active' => !$workflow->is_active
        ]);
        
        return back()->with('success', 'Workflow ' . ($workflow->is_active ? 'activated' : 'deactivated') . '!');
    }

    public function deleteWorkflow($id)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $workflow = Workflow::where('tenant_id', $tenantId)->findOrFail($id);
        $workflow->delete();
        
        return back()->with('success', 'Workflow deleted successfully!');
    }

    // ==========================================
    // 9. ANALYTICS
    // ==========================================
    public function analytics()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        // 🔥 Get current month and year for trends
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        $data = [
            // Stats
            'totalLeads' => Customer::where('tenant_id', $tenantId)->count(),
            'conversionRate' => $this->calculateConversionRate($tenantId),
            'avgResponseTime' => $this->calculateAvgResponseTime($tenantId),
            'aiResolutionRate' => $this->calculateAiResolutionRate($tenantId),
            
            // Trends
            'monthlyTrends' => $this->getMonthlyTrends($tenantId, $currentYear),
            'channelDistribution' => $this->getChannelDistribution($tenantId),
            
            // Additional Stats
            'totalRevenue' => Order::where('tenant_id', $tenantId)
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
            'totalOrders' => Order::where('tenant_id', $tenantId)->count(),
            'activeCustomers' => Customer::where('tenant_id', $tenantId)
                ->where('status', 'active')
                ->count(),
            'totalConversations' => Conversation::where('tenant_id', $tenantId)->count(),
            'resolvedByAI' => Conversation::where('tenant_id', $tenantId)
                ->where('resolved_by_ai', true)
                ->count(),
            'customerStages' => $this->getCustomerStages($tenantId),
        ];
        
        return view('owner.analytics', $data);
    }

    // ==========================================
    // ANALYTICS HELPERS
    // ==========================================

    private function calculateConversionRate($tenantId)
    {
        $total = Customer::where('tenant_id', $tenantId)->count();
        $converted = Customer::where('tenant_id', $tenantId)
            ->where('stage', 'closed')
            ->count();
        
        return $total > 0 ? round(($converted / $total) * 100, 2) : 0;
    }

    private function calculateAvgResponseTime($tenantId)
    {
        $avg = Conversation::where('tenant_id', $tenantId)
            ->whereNotNull('first_response_time')
            ->avg('first_response_time');
        
        return $avg ? round($avg / 60, 2) : 0;
    }

    private function calculateAiResolutionRate($tenantId)
    {
        $total = Conversation::where('tenant_id', $tenantId)->count();
        $resolved = Conversation::where('tenant_id', $tenantId)
            ->where('resolved_by_ai', true)
            ->count();
        
        return $total > 0 ? round(($resolved / $total) * 100, 2) : 0;
    }

    private function getMonthlyTrends($tenantId, $year)
    {
        // 🔥 Get last 12 months data
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthNum = $month->month;
            $yearNum = $month->year;
            
            $orders = Order::where('tenant_id', $tenantId)
                ->whereMonth('created_at', $monthNum)
                ->whereYear('created_at', $yearNum)
                ->count();
            
            $revenue = Order::where('tenant_id', $tenantId)
                ->where('payment_status', 'paid')
                ->whereMonth('created_at', $monthNum)
                ->whereYear('created_at', $yearNum)
                ->sum('total_amount');
            
            $months[] = (object) [
                'month' => $monthNum,
                'month_name' => $month->format('M'),
                'total_orders' => $orders,
                'total_revenue' => $revenue,
            ];
        }
        
        return collect($months);
    }

    private function getChannelDistribution($tenantId)
    {
        $channels = Conversation::where('tenant_id', $tenantId)
            ->select('channel', \DB::raw('COUNT(*) as total'))
            ->groupBy('channel')
            ->get();
        
        // If no data, return default empty collection
        if ($channels->isEmpty()) {
            return collect([
                (object) ['channel' => 'whatsapp', 'total' => 0],
                (object) ['channel' => 'instagram', 'total' => 0],
                (object) ['channel' => 'facebook', 'total' => 0],
                (object) ['channel' => 'web', 'total' => 0],
            ]);
        }
        
        return $channels;
    }

    private function getCustomerStages($tenantId)
    {
        return [
            'new' => Customer::where('tenant_id', $tenantId)->where('stage', 'new')->count(),
            'contacted' => Customer::where('tenant_id', $tenantId)->where('stage', 'contacted')->count(),
            'qualified' => Customer::where('tenant_id', $tenantId)->where('stage', 'qualified')->count(),
            'order_placed' => Customer::where('tenant_id', $tenantId)->where('stage', 'order_placed')->count(),
            'closed' => Customer::where('tenant_id', $tenantId)->where('stage', 'closed')->count(),
        ];
    }

    // ==========================================
    // REPORTS (Dynamic)
    // ==========================================

    public function reports()
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        
        // Date filters
        $dateFrom = request('date_from') ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = request('date_to') ?? now()->format('Y-m-d');
        $reportType = request('report_type') ?? 'orders';
        
        $data = [
            'orders' => $this->getOrderReport($tenantId, $dateFrom, $dateTo),
            'customers' => $this->getCustomerReport($tenantId, $dateFrom, $dateTo),
            'payments' => $this->getPaymentReport($tenantId, $dateFrom, $dateTo),
            'ai_usage' => $this->getAiUsageReport($tenantId, $dateFrom, $dateTo),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'report_type' => $reportType,
        ];
        
        return view('owner.reports', $data);
    }

    public function exportReport($type)
    {
        $tenantId = Auth::user()->tenant_id ?? Auth::id();
        $dateFrom = request('date_from') ?? now()->startOfMonth()->format('Y-m-d');
        $dateTo = request('date_to') ?? now()->format('Y-m-d');
        
        $data = $this->getReportData($tenantId, $type, $dateFrom, $dateTo);
        
        // Generate CSV
        $filename = $type . '_report_' . date('Y-m-d') . '.csv';
        
        return $this->generateCsv($data, $filename);
    }

    private function getOrderReport($tenantId, $dateFrom, $dateTo)
    {
        return Order::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('customer')
            ->get();
    }

    private function getCustomerReport($tenantId, $dateFrom, $dateTo)
    {
        return Customer::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
    }

    private function getPaymentReport($tenantId, $dateFrom, $dateTo)
    {
        return Payment::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
    }

    private function getAiUsageReport($tenantId, $dateFrom, $dateTo)
    {
        return AiLog::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();
    }

    private function getReportData($tenantId, $type, $dateFrom, $dateTo)
    {
        switch($type) {
            case 'orders':
                return $this->getOrderReport($tenantId, $dateFrom, $dateTo);
            case 'customers':
                return $this->getCustomerReport($tenantId, $dateFrom, $dateTo);
            case 'payments':
                return $this->getPaymentReport($tenantId, $dateFrom, $dateTo);
            case 'ai_usage':
                return $this->getAiUsageReport($tenantId, $dateFrom, $dateTo);
            default:
                return collect([]);
        }
    }

    private function generateCsv($data, $filename)
    {
        if ($data->isEmpty()) {
            return back()->with('error', 'No data to export');
        }
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, array_keys((array) $data->first()));
            
            // Data
            foreach ($data as $row) {
                fputcsv($file, (array) $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    // ==========================================
// STAFF MANAGEMENT
// ==========================================
public function staff()
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    
    $staff = User::where('tenant_id', $tenantId)
        ->whereIn('type', ['Staff', 'SalesAgent'])
        ->latest()
        ->paginate(15);
    
    $stats = [
        'total' => User::where('tenant_id', $tenantId)->whereIn('type', ['Staff', 'SalesAgent'])->count(),
        'active' => User::where('tenant_id', $tenantId)->whereIn('type', ['Staff', 'SalesAgent'])->where('status', 'active')->count(),
        'inactive' => User::where('tenant_id', $tenantId)->whereIn('type', ['Staff', 'SalesAgent'])->where('status', 'inactive')->count(),
    ];
    
    return view('owner.staff', compact('staff', 'stats'));
}

public function staffCreate()
{
    return view('owner.staff-create');
}

public function staffStore(Request $request)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'phone' => 'nullable|string|max:20',
        'type' => 'required|in:Staff,SalesAgent',
        'status' => 'required|in:active,inactive',
    ]);
    
    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'phone' => $validated['phone'] ?? null,
        'type' => $validated['type'],
        'tenant_id' => $tenantId,
        'status' => $validated['status'],
    ]);
    
    return redirect()->route('owner.staff')
        ->with('success', 'Staff member added successfully!');
}

public function staffEdit($id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $staff = User::where('tenant_id', $tenantId)
        ->whereIn('type', ['Staff', 'SalesAgent'])
        ->findOrFail($id);
    
    return view('owner.staff-edit', compact('staff'));
}

public function staffUpdate(Request $request, $id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $staff = User::where('tenant_id', $tenantId)
        ->whereIn('type', ['Staff', 'SalesAgent'])
        ->findOrFail($id);
    
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'phone' => 'nullable|string|max:20',
        'type' => 'required|in:Staff,SalesAgent',
        'status' => 'required|in:active,inactive',
    ]);
    
    $staff->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'] ?? null,
        'type' => $validated['type'],
        'status' => $validated['status'],
    ]);
    
    return redirect()->route('owner.staff')
        ->with('success', 'Staff member updated successfully!');
}

public function staffToggle($id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $staff = User::where('tenant_id', $tenantId)
        ->whereIn('type', ['Staff', 'SalesAgent'])
        ->findOrFail($id);
    
    $staff->update([
        'status' => $staff->status == 'active' ? 'inactive' : 'active'
    ]);
    
    return back()->with('success', 'Staff status toggled successfully!');
}

public function staffDelete($id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $staff = User::where('tenant_id', $tenantId)
        ->whereIn('type', ['Staff', 'SalesAgent'])
        ->findOrFail($id);
    
    $staff->delete();
    
    return redirect()->route('owner.staff')
        ->with('success', 'Staff member deleted successfully!');
}

// ==========================================
// WHATSAPP NUMBERS
// ==========================================
public function whatsappNumbers()
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    
    $numbers = WhatsAppNumber::where('tenant_id', $tenantId)
        ->latest()
        ->get();
    
    $default = WhatsAppNumber::where('tenant_id', $tenantId)
        ->where('is_default', true)
        ->first();
    
    return view('owner.whatsapp-numbers', compact('numbers', 'default'));
}

public function whatsappNumberCreate()
{
    return view('owner.whatsapp-numbers-create');
}

public function whatsappNumberStore(Request $request)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    
    $validated = $request->validate([
        'phone_number' => 'required|string|max:20|unique:whatsapp_numbers,phone_number',
        'phone_number_id' => 'nullable|string|max:255',
        'display_name' => 'nullable|string|max:255',
        'is_default' => 'nullable',
        'is_active' => 'nullable',
        'settings' => 'nullable|json',
    ]);
    
    $isDefault = $request->has('is_default') ? 1 : 0;
    $isActive = $request->has('is_active') ? 1 : 0;
    
    if ($isDefault) {
        WhatsAppNumber::where('tenant_id', $tenantId)->update(['is_default' => 0]);
    }
    
    WhatsAppNumber::create([
        'tenant_id' => $tenantId,
        'phone_number' => $validated['phone_number'],
        'phone_number_id' => $validated['phone_number_id'] ?? null,
        'display_name' => $validated['display_name'] ?? null,
        'is_default' => $isDefault,
        'is_active' => $isActive,
        'settings' => $validated['settings'] ?? null,
    ]);
    
    return redirect()->route('owner.whatsapp-numbers')
        ->with('success', 'WhatsApp number added successfully!');
}

public function whatsappNumberEdit($id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $number = WhatsAppNumber::where('tenant_id', $tenantId)->findOrFail($id);
    return view('owner.whatsapp-numbers-edit', compact('number'));
}

public function whatsappNumberUpdate(Request $request, $id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $number = WhatsAppNumber::where('tenant_id', $tenantId)->findOrFail($id);
    
    $validated = $request->validate([
        'phone_number' => 'required|string|max:20|unique:whatsapp_numbers,phone_number,' . $id,
        'phone_number_id' => 'nullable|string|max:255',
        'display_name' => 'nullable|string|max:255',
        'is_default' => 'nullable',
        'is_active' => 'nullable',
        'settings' => 'nullable|json',
    ]);
    
    $isDefault = $request->has('is_default') ? 1 : 0;
    $isActive = $request->has('is_active') ? 1 : 0;
    
    if ($isDefault) {
        WhatsAppNumber::where('tenant_id', $tenantId)->update(['is_default' => 0]);
    }
    
    $number->update([
        'phone_number' => $validated['phone_number'],
        'phone_number_id' => $validated['phone_number_id'] ?? null,
        'display_name' => $validated['display_name'] ?? null,
        'is_default' => $isDefault,
        'is_active' => $isActive,
        'settings' => $validated['settings'] ?? null,
    ]);
    
    return redirect()->route('owner.whatsapp-numbers')
        ->with('success', 'WhatsApp number updated successfully!');
}

public function whatsappNumberDelete($id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $number = WhatsAppNumber::where('tenant_id', $tenantId)->findOrFail($id);
    $number->delete();
    
    return redirect()->route('owner.whatsapp-numbers')
        ->with('success', 'WhatsApp number deleted successfully!');
}

public function whatsappNumberSetDefault($id)
{
    $tenantId = Auth::user()->tenant_id ?? Auth::id();
    $number = WhatsAppNumber::where('tenant_id', $tenantId)->findOrFail($id);
    
    WhatsAppNumber::where('tenant_id', $tenantId)->update(['is_default' => 0]);
    $number->update(['is_default' => 1]);
    
    return back()->with('success', 'Default WhatsApp number set successfully!');
}

/**
 * 🔥 Embedded Signup — 1-Click WhatsApp Connect
 */
public function whatsappNumberEmbeddedSignup(Request $request)
{
    $code = $request->input('code');
    $owner = Auth::user();
    $tenantId = $owner->tenant_id;

    if (!$code) {
        return response()->json(['success' => false, 'error' => 'No code provided']);
    }

    try {
        // 1. Exchange code for access token
        $response = \Illuminate\Support\Facades\Http::get(
            'https://graph.facebook.com/v20.0/oauth/access_token',
            [
                'client_id' => env('META_APP_ID'),
                'client_secret' => env('META_APP_SECRET'),
                'code' => $code,
            ]
        );

        if (!$response->successful()) {
            \Log::error('Token exchange failed: ' . $response->body());
            return response()->json([
                'success' => false,
                'error' => 'Token exchange failed. Check Meta credentials.'
            ]);
        }

        $accessToken = $response->json()['access_token'];

        // 2. Get WhatsApp Business Accounts
        $wabaResponse = \Illuminate\Support\Facades\Http::withToken($accessToken)
            ->get('https://graph.facebook.com/v20.0/me/whatsapp_business_accounts');

        if (!$wabaResponse->successful()) {
            \Log::error('WABA fetch failed: ' . $wabaResponse->body());
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch WhatsApp Business Accounts'
            ]);
        }

        $wabaData = $wabaResponse->json();
        $savedCount = 0;

        // 3. Loop through WABAs and Phone Numbers
        foreach ($wabaData['data'] ?? [] as $waba) {
            $numbersResponse = \Illuminate\Support\Facades\Http::withToken($accessToken)
                ->get("https://graph.facebook.com/v20.0/{$waba['id']}/phone_numbers");

            if (!$numbersResponse->successful()) continue;

            foreach ($numbersResponse->json()['data'] ?? [] as $number) {
                // 🔥 Plan limit check
                $plan = $owner->currentPlan;
                $currentCount = WhatsAppNumber::where('tenant_id', $tenantId)->count();
                
                if ($currentCount >= ($plan->channels_limit ?? 1)) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Plan limit reached (' . $plan->channels_limit . ' numbers). Please upgrade.'
                    ]);
                }

                // Save
                WhatsAppNumber::updateOrCreate(
                    ['phone_number_id' => $number['id']],
                    [
                        'tenant_id' => $tenantId,
                        'phone_number' => $number['display_phone_number'],
                        'display_name' => $number['verified_name'] ?? 'WhatsApp Business',
                        'is_default' => $currentCount == 0,
                        'is_active' => 1,
                        'settings' => [
                            'access_token' => $accessToken,
                            'waba_id' => $waba['id'],
                            'verified_at' => now()->toDateTimeString(),
                            'connected_via' => 'embedded_signup',
                        ],
                    ]
                );
                
                $savedCount++;
            }
        }

        if ($savedCount == 0) {
            return response()->json([
                'success' => false,
                'error' => 'No WhatsApp numbers found in your account'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $savedCount . ' number(s) connected!',
            'count' => $savedCount,
        ]);

    } catch (\Exception $e) {
        \Log::error('Embedded Signup Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Server error: ' . $e->getMessage()
        ]);
    }
}

}