<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppNumber extends Model
{
    use HasFactory;

    // 🔥 FIX: Specify exact table name
    protected $table = 'whatsapp_numbers';

    protected $fillable = [
        'tenant_id',
        'phone_number',
        'phone_number_id',
        'display_name',
        'is_default',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function isDefault()
    {
        return $this->is_default;
    }

    public function isActive()
    {
        return $this->is_active;
    }
}