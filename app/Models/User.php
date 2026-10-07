<?php
// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_no',
        'whatsapp_no',
        'status',
        'type',
        'tenant_id',
        'uuid',
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

    // 🔥 Kuch bhi nahi karo boot me — uuid optional hai
    // Database me nullable hai, so chill

    // ===== RELATIONSHIPS =====
    public function customers()
    {
        return $this->hasMany(Customer::class, 'tenant_id', 'tenant_id');
    }

    public function assignedCustomers()
    {
        return $this->hasMany(Customer::class, 'assigned_to', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'tenant_id', 'tenant_id');
    }

    public function assignedOrders()
    {
        return $this->hasMany(Order::class, 'assigned_to', 'id');
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class, 'tenant_id', 'tenant_id');
    }

    public function assignedConversations()
    {
        return $this->hasMany(Conversation::class, 'assigned_to', 'id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'tenant_id', 'tenant_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'tenant_id');
    }

    public function businesses()
    {
        return $this->hasMany(Business::class, 'tenant_id', 'tenant_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'tenant_id', 'tenant_id');
    }

    public function currentPlan()
    {
        return $this->belongsTo(Plan::class, 'current_plan_id');
    }

    // ===== ROLE CHECK =====
    public function isAdmin() { return $this->type === 'Admin'; }
    public function isOwner() { return $this->type === 'Owner'; }
    public function isStaff() { return in_array($this->type, ['Staff', 'SalesAgent']); }
    public function isSuperAdmin() { return $this->type === 'SuperAdmin'; }

    // ===== MENU ACCESS =====
    public function hasMenuAccess($menu)
    {
        return in_array($menu, $this->getRoleMenus());
    }

    public function getRoleMenus()
    {
        return match($this->type) {
            'SuperAdmin' => ['dashboard', 'businesses', 'subscriptions', 'ai-usage', 'cloud-resources', 'billing', 'logs', 'api-gateway', 'system-health', 'deployments', 'feature-flags', 'security'],
            'Admin' => ['dashboard', 'location', 'categories', 'settings'],
            'Owner' => ['dashboard', 'inbox', 'crm', 'orders', 'payments', 'ai-settings', 'knowledge', 'workflows', 'analytics'],
            'Staff' => ['dashboard', 'inbox', 'orders'],
            'SalesAgent' => ['dashboard', 'inbox', 'crm'],
            default => ['dashboard'],
        };
    }
}