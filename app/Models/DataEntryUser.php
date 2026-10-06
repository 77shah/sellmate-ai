<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class DataEntryUser extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'mobile',
        'password',
        'vendors', // Add this
    ];

    protected $hidden = [
        'password',
    ];

    // Cast vendors to array automatically
    protected $casts = [
        'vendors' => 'array',
    ];

    // Relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    // Get selected vendors names
    public function getSelectedVendorsAttribute()
    {
        if (!$this->vendors) {
            return [];
        }
        
        return Vendor::whereIn('id', $this->vendors)
                     ->pluck('name', 'id')
                     ->toArray();
    }

    // Automatically hash password
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // Convert array to JSON when saving
    public function setVendorsAttribute($value)
    {
        $this->attributes['vendors'] = json_encode($value);
    }

    // Convert JSON to array when retrieving
    public function getVendorsAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}