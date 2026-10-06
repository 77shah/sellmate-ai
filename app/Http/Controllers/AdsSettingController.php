<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdsSetting;
use Illuminate\Support\Facades\DB;

class AdsSettingController extends Controller
{
    public function index()
    {
        $ads = AdsSetting::first();
        return view('settings.ads', compact('ads'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // Basic fields
            'package_id' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'admob_app_id' => 'nullable|string|max:255',
            
            // Banner Ad
            'banner_ad_type' => 'nullable|in:Demo,Live',
            'banner_demo_id' => 'nullable|string|max:255',
            'banner_live_id' => 'nullable|string|max:255',
            
            // Interstitial Ad
            'interstitial_ad_type' => 'nullable|in:Demo,Live',
            'interstitial_demo_id' => 'nullable|string|max:255',
            'interstitial_live_id' => 'nullable|string|max:255',
            
            // Rewarded Ad
            'rewarded_ad_type' => 'nullable|in:Demo,Live',
            'rewarded_demo_id' => 'nullable|string|max:255',
            'rewarded_live_id' => 'nullable|string|max:255',
            
            // Rewarded Interstitial Ad
            'rewarded_interstitial_ad_type' => 'nullable|in:Demo,Live',
            'rewarded_interstitial_demo_id' => 'nullable|string|max:255',
            'rewarded_interstitial_live_id' => 'nullable|string|max:255',
            
            // Native Advanced Ad
            'native_ad_type' => 'nullable|in:Demo,Live',
            'native_demo_id' => 'nullable|string|max:255',
            'native_live_id' => 'nullable|string|max:255',
            
            // App Open Ad
            'app_open_ad_type' => 'nullable|in:Demo,Live',
            'app_open_demo_id' => 'nullable|string|max:255',
            'app_open_live_id' => 'nullable|string|max:255',
        ]);

        // Conditional validation for each ad type
        $this->validateAdFields($request, 'banner');
        $this->validateAdFields($request, 'interstitial');
        $this->validateAdFields($request, 'rewarded');
        $this->validateAdFields($request, 'rewarded_interstitial');
        $this->validateAdFields($request, 'native');
        $this->validateAdFields($request, 'app_open');

        DB::beginTransaction();
        
        try {
            $ads = AdsSetting::first();
            
            // Prepare data
            $data = array_filter([
                'package_id' => $request->package_id,
                'email' => $request->email,
                'admob_app_id' => $request->admob_app_id,
                
                // Banner Ad
                'banner_ad_status' => $request->banner_ad_status,
                'banner_ad_type' => $request->banner_ad_type,
                'banner_demo_id' => $request->banner_demo_id,
                'banner_live_id' => $request->banner_live_id,
                
                // Interstitial Ad
                'interstitial_ad_status' => $request->interstitial_ad_status,
                'interstitial_ad_type' => $request->interstitial_ad_type,
                'interstitial_demo_id' => $request->interstitial_demo_id,
                'interstitial_live_id' => $request->interstitial_live_id,
                
                // Rewarded Ad
                'rewarded_ad_status' => $request->rewarded_ad_status,
                'rewarded_ad_type' => $request->rewarded_ad_type,
                'rewarded_demo_id' => $request->rewarded_demo_id,
                'rewarded_live_id' => $request->rewarded_live_id,
                
                // Rewarded Interstitial Ad
                'rewarded_interstitial_ad_status' => $request->rewarded_interstitial_ad_status,
                'rewarded_interstitial_ad_type' => $request->rewarded_interstitial_ad_type,
                'rewarded_interstitial_demo_id' => $request->rewarded_interstitial_demo_id,
                'rewarded_interstitial_live_id' => $request->rewarded_interstitial_live_id,
                
                // Native Advanced Ad
                'native_ad_status' => $request->native_ad_status,
                'native_ad_type' => $request->native_ad_type,
                'native_demo_id' => $request->native_demo_id,
                'native_live_id' => $request->native_live_id,
                
                // App Open Ad
                'app_open_ad_status' => $request->app_open_ad_status,
                'app_open_ad_type' => $request->app_open_ad_type,
                'app_open_demo_id' => $request->app_open_demo_id,
                'app_open_live_id' => $request->app_open_live_id,
                
            ], function($value) {
                return !is_null($value) && $value !== '';
            });
            
            if ($ads) {
                $ads->update($data);
                $message = 'Ads settings updated successfully!';
            } else {
                AdsSetting::create($data);
                $message = 'Ads settings created successfully!';
            }
            
            DB::commit();
            
            return back()->with('success', $message);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->with('error', 'Failed to save ads settings: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Validate ad fields based on status and type
     */
    private function validateAdFields(Request $request, $adType)
    {
        $statusField = $adType . '_ad_status';
        $typeField = $adType . '_ad_type';
        $demoIdField = $adType . '_demo_id';
        $liveIdField = $adType . '_live_id';
        
        if ($request->has($statusField) && $request->$statusField === 'ON') {
            if ($request->$typeField === 'Demo' && empty($request->$demoIdField)) {
                $request->validate([
                    $demoIdField => 'required|string|max:255'
                ], [
                    $demoIdField . '.required' => ucfirst(str_replace('_', ' ', $adType)) . ' Demo ID is required when status is ON and type is Demo'
                ]);
            }
            if ($request->$typeField === 'Live' && empty($request->$liveIdField)) {
                $request->validate([
                    $liveIdField => 'required|string|max:255'
                ], [
                    $liveIdField . '.required' => ucfirst(str_replace('_', ' ', $adType)) . ' Live ID is required when status is ON and type is Live'
                ]);
            }
        }
    }
}