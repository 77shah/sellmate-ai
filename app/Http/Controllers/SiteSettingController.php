<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteSetting;

class SiteSettingController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::first();
        return view('settings.site-setting', compact('setting'));
    }

    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'copyright' => 'nullable|string|max:255',
        ]);

        $setting = SiteSetting::first();
        $data = [];

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = time() . '_' . $logo->getClientOriginalName();
            $logo->move(public_path('admin_uploads'), $logoName);
            $data['logo'] = $logoName;
        }

        // Handle favicon upload
        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
            $faviconName = time() . '_' . $favicon->getClientOriginalName();
            $favicon->move(public_path('admin_uploads'), $faviconName);
            $data['favicon'] = $faviconName;
        }

        $data['copyright'] = $request->copyright;

        if ($setting) {
            // Update existing record
            $setting->update($data);
        } else {
            // Create new record
            SiteSetting::create($data);
        }

        return redirect()->back()->with('success', 'Site Settings Saved Successfully!');
    }
}
