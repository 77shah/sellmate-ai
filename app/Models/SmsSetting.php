<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'enabled', 'country', 'customer_id', 'email', 'password', 'key',
        'country_code', 'flow_type', 'length', 'auth_token', 'sent_url', 'verify_url'
    ];
}
