<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdsSetting extends Model
{
    use HasFactory;

    protected $table = 'ads_settings';

    protected $fillable = [
        'package_id',
        'email',
        'admob_app_id',
        
        // Banner Ad
        'banner_ad_status',
        'banner_ad_type',
        'banner_demo_id',
        'banner_live_id',
        
        // Interstitial Ad
        'interstitial_ad_status',
        'interstitial_ad_type',
        'interstitial_demo_id',
        'interstitial_live_id',
        
        // Rewarded Ad
        'rewarded_ad_status',
        'rewarded_ad_type',
        'rewarded_demo_id',
        'rewarded_live_id',
        
        // Rewarded Interstitial Ad
        'rewarded_interstitial_ad_status',
        'rewarded_interstitial_ad_type',
        'rewarded_interstitial_demo_id',
        'rewarded_interstitial_live_id',
        
        // Native Advanced Ad
        'native_ad_status',
        'native_ad_type',
        'native_demo_id',
        'native_live_id',
        
        // App Open Ad
        'app_open_ad_status',
        'app_open_ad_type',
        'app_open_demo_id',
        'app_open_live_id',
    ];

    protected $casts = [
        'banner_ad_status' => 'string',
        'banner_ad_type' => 'string',
        'interstitial_ad_status' => 'string',
        'interstitial_ad_type' => 'string',
        'rewarded_ad_status' => 'string',
        'rewarded_ad_type' => 'string',
        'rewarded_interstitial_ad_status' => 'string',
        'rewarded_interstitial_ad_type' => 'string',
        'native_ad_status' => 'string',
        'native_ad_type' => 'string',
        'app_open_ad_status' => 'string',
        'app_open_ad_type' => 'string',
    ];

    /**
     * Status Setters - Only ON or OFF
     */
    public function setBannerAdStatusAttribute($value)
    {
        $this->attributes['banner_ad_status'] = in_array($value, ['ON', 'OFF']) ? $value : 'OFF';
    }

    public function setInterstitialAdStatusAttribute($value)
    {
        $this->attributes['interstitial_ad_status'] = in_array($value, ['ON', 'OFF']) ? $value : 'OFF';
    }

    public function setRewardedAdStatusAttribute($value)
    {
        $this->attributes['rewarded_ad_status'] = in_array($value, ['ON', 'OFF']) ? $value : 'OFF';
    }

    public function setRewardedInterstitialAdStatusAttribute($value)
    {
        $this->attributes['rewarded_interstitial_ad_status'] = in_array($value, ['ON', 'OFF']) ? $value : 'OFF';
    }

    public function setNativeAdStatusAttribute($value)
    {
        $this->attributes['native_ad_status'] = in_array($value, ['ON', 'OFF']) ? $value : 'OFF';
    }

    public function setAppOpenAdStatusAttribute($value)
    {
        $this->attributes['app_open_ad_status'] = in_array($value, ['ON', 'OFF']) ? $value : 'OFF';
    }

    /**
     * Type Setters - Only Demo or Live
     */
    public function setBannerAdTypeAttribute($value)
    {
        $this->attributes['banner_ad_type'] = in_array($value, ['Demo', 'Live']) ? $value : 'Demo';
    }

    public function setInterstitialAdTypeAttribute($value)
    {
        $this->attributes['interstitial_ad_type'] = in_array($value, ['Demo', 'Live']) ? $value : 'Demo';
    }

    public function setRewardedAdTypeAttribute($value)
    {
        $this->attributes['rewarded_ad_type'] = in_array($value, ['Demo', 'Live']) ? $value : 'Demo';
    }

    public function setRewardedInterstitialAdTypeAttribute($value)
    {
        $this->attributes['rewarded_interstitial_ad_type'] = in_array($value, ['Demo', 'Live']) ? $value : 'Demo';
    }

    public function setNativeAdTypeAttribute($value)
    {
        $this->attributes['native_ad_type'] = in_array($value, ['Demo', 'Live']) ? $value : 'Demo';
    }

    public function setAppOpenAdTypeAttribute($value)
    {
        $this->attributes['app_open_ad_type'] = in_array($value, ['Demo', 'Live']) ? $value : 'Demo';
    }

    /**
     * Get Active Ad IDs
     */
    public function getActiveBannerAdIdAttribute()
    {
        if ($this->banner_ad_status !== 'ON') {
            return null;
        }
        return $this->banner_ad_type === 'Demo' ? $this->banner_demo_id : $this->banner_live_id;
    }

    public function getActiveInterstitialAdIdAttribute()
    {
        if ($this->interstitial_ad_status !== 'ON') {
            return null;
        }
        return $this->interstitial_ad_type === 'Demo' ? $this->interstitial_demo_id : $this->interstitial_live_id;
    }

    public function getActiveRewardedAdIdAttribute()
    {
        if ($this->rewarded_ad_status !== 'ON') {
            return null;
        }
        return $this->rewarded_ad_type === 'Demo' ? $this->rewarded_demo_id : $this->rewarded_live_id;
    }

    public function getActiveRewardedInterstitialAdIdAttribute()
    {
        if ($this->rewarded_interstitial_ad_status !== 'ON') {
            return null;
        }
        return $this->rewarded_interstitial_ad_type === 'Demo' ? $this->rewarded_interstitial_demo_id : $this->rewarded_interstitial_live_id;
    }

    public function getActiveNativeAdIdAttribute()
    {
        if ($this->native_ad_status !== 'ON') {
            return null;
        }
        return $this->native_ad_type === 'Demo' ? $this->native_demo_id : $this->native_live_id;
    }

    public function getActiveAppOpenAdIdAttribute()
    {
        if ($this->app_open_ad_status !== 'ON') {
            return null;
        }
        return $this->app_open_ad_type === 'Demo' ? $this->app_open_demo_id : $this->app_open_live_id;
    }

    /**
     * Check if Ads are Active
     */
    public function isBannerActive(): bool
    {
        return $this->banner_ad_status === 'ON';
    }

    public function isInterstitialActive(): bool
    {
        return $this->interstitial_ad_status === 'ON';
    }

    public function isRewardedActive(): bool
    {
        return $this->rewarded_ad_status === 'ON';
    }

    public function isRewardedInterstitialActive(): bool
    {
        return $this->rewarded_interstitial_ad_status === 'ON';
    }

    public function isNativeActive(): bool
    {
        return $this->native_ad_status === 'ON';
    }

    public function isAppOpenActive(): bool
    {
        return $this->app_open_ad_status === 'ON';
    }

    /**
     * Get all active ads
     */
    public function getActiveAdsAttribute()
    {
        $activeAds = [];
        
        if ($this->isBannerActive()) $activeAds['banner'] = $this->active_banner_ad_id;
        if ($this->isInterstitialActive()) $activeAds['interstitial'] = $this->active_interstitial_ad_id;
        if ($this->isRewardedActive()) $activeAds['rewarded'] = $this->active_rewarded_ad_id;
        if ($this->isRewardedInterstitialActive()) $activeAds['rewarded_interstitial'] = $this->active_rewarded_interstitial_ad_id;
        if ($this->isNativeActive()) $activeAds['native'] = $this->active_native_ad_id;
        if ($this->isAppOpenActive()) $activeAds['app_open'] = $this->active_app_open_ad_id;
        
        return $activeAds;
    }
}