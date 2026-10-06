<?php
// app/Models/Subscription.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    // 🔥 ID auto-increment handle karega
    // public $incrementing = false;  // ← HATAO
    // protected $keyType = 'string'; // ← HATAO

    protected $fillable = [
        'tenant_id', 'plan_id', 'plan_name', 'plan_type',
        'status', 'start_date', 'end_date', 'amount',
        'features', 'meta', 'razorpay_subscription_id',
        'stripe_subscription_id', 'auto_renew'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'amount' => 'decimal:2',
        'features' => 'array',
        'meta' => 'array',
        'auto_renew' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

    public function isActive()
    {
        return $this->status === 'active' && ($this->end_date === null || $this->end_date > now());
    }

    public function isExpired()
    {
        return $this->status === 'expired' || ($this->end_date && $this->end_date <= now());
    }
}