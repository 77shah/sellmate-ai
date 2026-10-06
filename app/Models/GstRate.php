<?php
// app/Models/GstRate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GstRate extends Model
{
    use HasFactory;

    protected $table = 'gst_rates';

    protected $fillable = [
        'rate',
        'status'
    ];
}