<?php
// app/Http/Controllers/GatewaySettingController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GatewaySetting;

class GatewaySettingController extends Controller
{
    public function index()
    {
        $gateway = GatewaySetting::first();
        return view('settings.gateway', compact('gateway'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'mobile' => 'nullable|string|max:20',
            'merchant_id' => 'nullable|string|max:255',
            'merchant_key' => 'nullable|string',
            'status' => 'nullable|boolean',
        ]);

        $gateway = GatewaySetting::first();
        if ($gateway) {
            $gateway->update($validated);
        } else {
            GatewaySetting::create($validated);
        }

        return back()->with('success', 'Gateway settings updated successfully!');
    }
}