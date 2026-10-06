<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppUpdate;

class AppUpdateController extends Controller
{
    public function index()
    {
        $appUpdate = AppUpdate::first();
        return view('settings.app_update', compact('appUpdate'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'android_version' => 'nullable|string|max:255',
            'android_url' => 'nullable|url|max:500',
            'ios_version' => 'nullable|string|max:255',
            'ios_url' => 'nullable|url|max:500',
            'drive_app_version' => 'nullable|string|max:255',
            'app_url' => 'nullable|url|max:500',
        ]);

        $data = $request->only(['android_version', 'android_url', 'ios_version', 'ios_url', 'drive_app_version', 'app_url']);

        $appUpdate = AppUpdate::first();

        if ($appUpdate) {
            $appUpdate->update($data);
        } else {
            AppUpdate::create($data);
        }

        return redirect()->back()->with('success', 'Application Update saved successfully.');
    }
}
