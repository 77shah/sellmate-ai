<?php
// app/Http/Controllers/Admin/PlanController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plans,name',
            'price' => 'required|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'currency' => 'required|in:INR,USD',
            'billing_period' => 'required|in:monthly,yearly,month,year',
            'ai_messages_limit' => 'required|integer|min:0',
            'channels_limit' => 'required|integer|min:1',
            'team_seats_limit' => 'required|integer|min:1',
            'storage_limit' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $isActive = $request->has('is_active') && $request->is_active == 1 ? true : false;

        $plan = Plan::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price' => $validated['price'],
            'price_usd' => $validated['price_usd'] ?? 0,
            'currency' => $validated['currency'],
            'currency_usd' => 'USD',
            'billing_period' => $validated['billing_period'],
            'ai_messages_limit' => $validated['ai_messages_limit'],
            'channels_limit' => $validated['channels_limit'],
            'team_seats_limit' => $validated['team_seats_limit'],
            'storage_limit' => $validated['storage_limit'],
            'features' => [],
            'is_active' => $isActive,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan "' . $plan->name . '" created successfully!');
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plans,name,' . $plan->id,
            'price' => 'required|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'currency' => 'required|in:INR,USD',
            'billing_period' => 'required|in:monthly,yearly,month,year',
            'ai_messages_limit' => 'required|integer|min:0',
            'channels_limit' => 'required|integer|min:1',
            'team_seats_limit' => 'required|integer|min:1',
            'storage_limit' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $isActive = $request->has('is_active') && $request->is_active == 1 ? true : false;

        $plan->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'price' => $validated['price'],
            'price_usd' => $validated['price_usd'] ?? 0,
            'currency' => $validated['currency'],
            'billing_period' => $validated['billing_period'],
            'ai_messages_limit' => $validated['ai_messages_limit'],
            'channels_limit' => $validated['channels_limit'],
            'team_seats_limit' => $validated['team_seats_limit'],
            'storage_limit' => $validated['storage_limit'],
            'is_active' => $isActive,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan "' . $plan->name . '" updated successfully!');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->where('status', 'active')->count() > 0) {
            return redirect()->route('admin.plans.index')
                ->with('error', 'Cannot delete plan "' . $plan->name . '" because it has active subscribers.');
        }

        $planName = $plan->name;
        $plan->delete();

        return redirect()->route('admin.plans.index')
            ->with('success', 'Plan "' . $planName . '" deleted successfully!');
    }

    public function toggleStatus(Plan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $plan->is_active,
            'message' => 'Plan status updated successfully!'
        ]);
    }
}