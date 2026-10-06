<?php
// app/Models/ReferAndEarn.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferAndEarn extends Model
{
    use HasFactory;

    protected $table = 'refer_and_earn';

    protected $fillable = [
        'content'
    ];
}