<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'android_version',
        'android_url',
        'ios_version',
        'ios_url',
        'drive_app_version',
        'app_url',
    ];
}
