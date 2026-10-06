<?php
// app/Models/GatewaySetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GatewaySetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_company',
        'email',
        'mobile',
        'merchant_id',
        'merchant_key',
        'status'
    ];
}