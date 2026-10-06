<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Payment;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $razorpay;

    public function __construct()
    {
        $this->razorpay = new Api(
            env('RAZORPAY_KEY'),
            env('RAZORPAY_SECRET')
        );
    }

    // ==========================================
    // 1. SHOW PLANS (Owner)
    // ==========================================
    public function plans(Request $request)
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $currentPlan = Auth::user()->currentPlan;
        $subscription = Subscription::where('tenant_id', Auth::id())
            ->where('status', 'active')
            ->latest()
            ->first();

        // 🔥 Currency detect — user location ya manual
        $currency = $request->get('currency', $this->detectCurrency());

        return view('owner.payment.plans', compact('plans', 'currentPlan', 'subscription', 'currency'));
    }

    // 🔥 Auto currency detect — India ya abroad
    protected function detectCurrency()
    {
        $ip = request()->ip();
        
        // Local/development me INR default
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            return 'INR';
        }

        try {
            $response = \Http::timeout(3)->get("http://ip-api.com/json/{$ip}");
            $data = $response->json();
            
            if (isset($data['countryCode']) && $data['countryCode'] === 'IN') {
                return 'INR';
            }
        } catch (\Exception $e) {
            Log::warning('Currency detection failed: ' . $e->getMessage());
        }

        return 'USD'; // Default abroad
    }

    // ==========================================
    // 2. SUBSCRIPTION STATUS (Owner)
    // ==========================================
    public function status()
    {
        $user = Auth::user();
        $subscription = Subscription::where('tenant_id', $user->id)
            ->whereIn('status', ['active', 'pending'])
            ->latest()
            ->first();

        $payments = Payment::where('tenant_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('owner.payment.status', compact('subscription', 'payments'));
    }

    // ==========================================
    // 3. FREE PLAN SUBSCRIBE
    // ==========================================
    public function subscribeFree($planId)
    {
        $plan = Plan::findOrFail($planId);

        if ($plan->price > 0 && $plan->price_usd > 0) {
            return redirect()->route('owner.plans')->with('error', 'This is not a free plan.');
        }

        Subscription::where('tenant_id', Auth::id())
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        $subscription = Subscription::create([
            'tenant_id' => Auth::id(),
            'plan_id' => $plan->id,
            'plan_name' => $plan->name,
            'plan_type' => $plan->billing_period,
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addMonth(),
            'amount' => 0,
            'features' => $plan->features,
            'meta' => ['plan_slug' => $plan->slug],
            'auto_renew' => true,
        ]);

        Auth::user()->update([
            'current_plan_id' => $plan->id,
            'subscription_expires_at' => now()->addMonth(),
        ]);

        return redirect()->route('owner.plans')->with('success', 'Subscribed to Free plan successfully!');
    }

    // ==========================================
    // 4. CREATE ORDER (Razorpay) — Currency Support
    // ==========================================
    public function createOrder(Request $request)
    {
        $plan = Plan::findOrFail($request->plan_id);
        $currency = $request->get('currency', 'INR'); // 🔥 INR ya USD

        try {
            // 🔥 Currency ke hisaab se price
            $amount = $currency === 'USD' 
                ? ($plan->price_usd ?? 0) 
                : $plan->price;

            // Razorpay amount in paise/cents
            $amountInSmallest = $currency === 'USD' 
                ? $amount * 100  // cents
                : $amount * 100; // paise

            $order = $this->razorpay->order->create([
                'amount' => $amountInSmallest,
                'currency' => $currency,
                'receipt' => 'order_' . time(),
                'payment_capture' => 1,
                'notes' => [
                    'tenant_id' => Auth::id(),
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'currency' => $currency,
                ]
            ]);

            return response()->json([
                'order_id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency,
                'key' => env('RAZORPAY_KEY'),
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
            ]);

        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 5. VERIFY PAYMENT
    // ==========================================
    public function verifyPayment(Request $request)
    {
        $data = $request->all();

        try {
            $attributes = [
                'razorpay_order_id' => $data['razorpay_order_id'],
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'razorpay_signature' => $data['razorpay_signature']
            ];

            $this->razorpay->utility->verifyPaymentSignature($attributes);

            $order = $this->razorpay->order->fetch($data['razorpay_order_id']);
            $plan = Plan::find($order->notes['plan_id']);
            $currency = $order->notes['currency'] ?? 'INR';

            Subscription::where('tenant_id', Auth::id())
                ->where('status', 'active')
                ->update(['status' => 'cancelled']);

            $subscription = Subscription::create([
                'tenant_id' => Auth::id(),
                'plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'plan_type' => $plan->billing_period,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'amount' => $order->amount / 100,
                'currency' => $currency, // 🔥 Currency store
                'features' => $plan->features,
                'meta' => [
                    'plan_slug' => $plan->slug,
                    'razorpay_order_id' => $data['razorpay_order_id'],
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                    'currency' => $currency,
                ],
                'razorpay_subscription_id' => $data['razorpay_payment_id'],
                'auto_renew' => true,
            ]);

            Auth::user()->update([
                'current_plan_id' => $plan->id,
                'subscription_expires_at' => now()->addMonth(),
                'razorpay_customer_id' => $data['razorpay_payment_id'],
            ]);

            Payment::create([
                'tenant_id' => Auth::id(),
                'order_id' => $subscription->id,
                'gateway' => 'razorpay',
                'amount' => $order->amount / 100,
                'currency' => $currency,
                'status' => 'completed',
                'transaction_ref' => $data['razorpay_payment_id'],
                'payment_method' => $data['payment_method'] ?? 'card',
                'paid_at' => now(),
                'metadata' => [
                    'razorpay_order_id' => $data['razorpay_order_id'],
                    'razorpay_payment_id' => $data['razorpay_payment_id'],
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'subscription_id' => $subscription->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment verification failed: ' . $e->getMessage()], 500);
        }
    }

    // ==========================================
    // 6. CANCEL SUBSCRIPTION
    // ==========================================
    public function cancel($id)
    {
        $subscription = Subscription::where('tenant_id', Auth::id())
            ->findOrFail($id);

        $subscription->update([
            'status' => 'cancelled',
            'auto_renew' => false,
        ]);

        Auth::user()->update([
            'subscription_expires_at' => null,
        ]);

        return back()->with('success', 'Subscription cancelled successfully.');
    }

    // ==========================================
    // 7. 🔥 SWITCH CURRENCY (AJAX)
    // ==========================================
    public function switchCurrency(Request $request)
    {
        $currency = $request->get('currency', 'INR');
        
        if (!in_array($currency, ['INR', 'USD'])) {
            $currency = 'INR';
        }

        session(['preferred_currency' => $currency]);

        return response()->json([
            'success' => true,
            'currency' => $currency,
        ]);
    }
}