<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlockedIp extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'ip_address', 'reason', 'blocked_until', 'permanent'
    ];

    protected $casts = [
        'blocked_until' => 'datetime',
        'permanent' => 'boolean',
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

    public function isActive()
    {
        if ($this->permanent) {
            return true;
        }
        return $this->blocked_until && $this->blocked_until->isFuture();
    }
}