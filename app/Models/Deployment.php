<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Deployment extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'version', 'branch', 'commit_hash', 'message',
        'status', 'deployed_by', 'duration', 'details'
    ];

    protected $casts = [
        'details' => 'array',
        'duration' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function deployer()
    {
        return $this->belongsTo(User::class, 'deployed_by');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'running' => 'info',
            'success' => 'success',
            'failed' => 'danger',
            'rollback' => 'secondary',
        ];
        $color = $colors[$this->status] ?? 'secondary';
        return "<span class='badge bg-{$color}'>{$this->status}</span>";
    }
}