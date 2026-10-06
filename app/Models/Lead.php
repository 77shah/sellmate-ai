<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pan',
        'aadhar_number',
        'account_number',
        'bank_name',
        'transactions',
        'vendor_id',
        'branch_id',
        'data_entry_user_id',
        'status'
    ];

    protected $casts = [
        'transactions' => 'array',
        'status' => 'integer'
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function dataEntryUser()
    {
        return $this->belongsTo(DataEntryUser::class, 'data_entry_user_id');
    }

    // Status accessor
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected',
            default => 'Unknown'
        };
    }
}