<?php
// app/Models/AiSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSetting extends Model
{
    use HasFactory;
    public $incrementing = true;
    protected $keyType = 'int';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id',
        'personality',
        'language',
        'business_hours',
        'auto_reply_enabled',
        'escalation_rules',
        'custom_responses',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'auto_reply_enabled' => 'boolean',
        'custom_responses' => 'array',
        'business_hours' => 'string',
        'escalation_rules' => 'string',
    ];

    /**
     * Get the tenant (business owner) that owns this setting.
     */
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id', 'id');
    }

    /**
     * Get default settings for new tenant.
     */
    public static function getDefaults()
    {
        return (object) [
            'personality' => 'friendly',
            'language' => 'english',
            'business_hours' => '9:00 AM - 6:00 PM',
            'auto_reply_enabled' => false,
            'escalation_rules' => 'Escalate to human when customer is frustrated or asks complex questions.',
            'custom_responses' => null,
        ];
    }

    /**
     * Get settings for a tenant, or create default if not exists.
     */
    public static function getForTenant($tenantId)
    {
        $settings = self::where('tenant_id', $tenantId)->first();
        
        if (!$settings) {
            // Create default settings
            $settings = self::create([
                'tenant_id' => $tenantId,
                'personality' => 'friendly',
                'language' => 'english',
                'business_hours' => '9:00 AM - 6:00 PM',
                'auto_reply_enabled' => false,
                'escalation_rules' => 'Escalate to human when customer is frustrated or asks complex questions.',
            ]);
        }
        
        return $settings;
    }

    /**
     * Check if auto-reply is enabled.
     */
    public function isAutoReplyEnabled()
    {
        return $this->auto_reply_enabled == true;
    }

    /**
     * Get personality options.
     */
    public static function getPersonalityOptions()
    {
        return [
            'formal' => 'Formal',
            'friendly' => 'Friendly',
            'sales' => 'Sales Focused',
        ];
    }

    /**
     * Get language options.
     */
    public static function getLanguageOptions()
    {
        return [
            'english' => 'English',
            'hindi' => 'Hindi',
            'hinglish' => 'Hinglish',
        ];
    }
}