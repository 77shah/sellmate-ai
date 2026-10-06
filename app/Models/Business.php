<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'phone',
        'address',
        'business_type',
        'website',
        'logo',
        'status',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id', 'tenant_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'tenant_id', 'tenant_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'tenant_id', 'tenant_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'tenant_id');
    }
}