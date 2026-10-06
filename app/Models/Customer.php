<?php
// app/Models/Customer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'tenant_id',
        'name',
        'phone',
        'email',
        'address',
        'lead_score',
        'stage',
        'status',
        'tags',
        'assigned_to',
        'source',
        'notes',
        'last_contacted_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'lead_score' => 'integer',
        'last_contacted_at' => 'datetime',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    // Scopes
    public function scopeHighScore($query)
    {
        return $query->where('lead_score', '>=', 70);
    }

    public function scopeByStage($query, $stage)
    {
        return $query->where('stage', $stage);
    }
}