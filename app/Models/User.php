<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // 🔥 ID auto-increment hai
    // public $incrementing = false;  // ← HATAO
    // protected $keyType = 'string'; // ← HATAO

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_no',
        'whatsapp_no',
        'status',
        'type',
        'tenant_id',
        'last_login_at',
        'profile_image',
        'current_plan_id',
        'subscription_expires_at',
        'razorpay_customer_id',
        'stripe_customer_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    // 🔥 Boot method - UUID generate mat karo
    protected static function boot()
    {
        parent::boot();
        // UUID column nahi hai, isliye kuch mat karo
    }

    // ===== RELATIONSHIPS =====
    public function customers()
    {
        return $this->hasMany(Customer::class, 'tenant_id', 'id');
    }

    public function assignedCustomers()
    {
        return $this->hasMany(Customer::class, 'assigned_to', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'tenant_id', 'id');
    }

    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'assigned_to', 'id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'tenant_id', 'id');
    }

    public function assignedConversations()
    {
        return $this->hasMany(Conversation::class, 'assigned_to', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'tenant_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'id');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'tenant_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'tenant_id', 'id');
    }

    public function currentPlan()
    {
        return $this->belongsTo(Plan::class, 'current_plan_id');
    }

    // ===== ROLE CHECK HELPER METHODS =====
    public function isAdmin()
    {
        return $this->type === 'Admin';
    }

    public function isOwner()
    {
        return $this->type === 'Owner';
    }

    public function isStaff()
    {
        return in_array($this->type, ['Staff', 'SalesAgent']);
    }

    public function isSuperAdmin()
    {
        return $this->type === 'SuperAdmin';
    }

    // ===== MENU ACCESS =====
    public function hasMenuAccess($menu)
    {
        $roleMenus = $this->getRoleMenus();
        return in_array($menu, $roleMenus);
    }

    public function getRoleMenus()
    {
        switch($this->type) {
            case 'SuperAdmin':
                return [
                    'dashboard', 'businesses', 'subscriptions', 'ai-usage',
                    'cloud-resources', 'billing', 'logs', 'api-gateway',
                    'system-health', 'deployments', 'feature-flags', 'security'
                ];
            case 'Admin':
                return [
                    'dashboard', 'location', 'categories', 'settings'
                ];
            case 'Owner':
                return [
                    'dashboard', 'inbox', 'crm', 'orders', 'payments',
                    'ai-settings', 'knowledge', 'workflows', 'analytics'
                ];
            case 'Staff':
                return [
                    'dashboard', 'inbox', 'orders'
                ];
            case 'SalesAgent':
                return [
                    'dashboard', 'inbox', 'crm'
                ];
            default:
                return ['dashboard'];
        }
    }
}