<?php
// app/Models/Conversation.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conversation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'customer_id',
        'channel',
        'status',
        'assigned_to',
        'first_response_time',
        'resolved_by_ai',
        'last_message_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'first_response_time' => 'integer',
        'resolved_by_ai' => 'boolean',
        'last_message_at' => 'datetime',
    ];

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function aiLogs()
    {
        return $this->hasMany(AiLog::class);
    }
}