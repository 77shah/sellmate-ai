<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SmsSetting;

class SmsSettingController extends Controller
{
    public function index()
    {
        $sms = SmsSetting::first();
        return view('settings.sms_setting', compact('sms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country' => 'nullable|string',
            'customer_id' => 'nullable|string',
            'email' => 'nullable|email',
            'password' => 'nullable|string',
            'key' => 'nullable|string',
            'country_code' => 'nullable|string',
            'flow_type' => 'nullable|string',
            'length' => 'nullable|integer',
            'auth_token' => 'nullable|string',
            'sent_url' => 'nullable|string',
            'verify_url' => 'nullable|string',
        ]);

        $validated['enabled'] = $request->has('enabled') ? 1 : 0;

        $sms = SmsSetting::first();
        if ($sms) {
            $sms->update($validated);
        } else {
            SmsSetting::create($validated);
        }

        return back()->with('success', 'SMS Settings updated successfully!');
    }
}
