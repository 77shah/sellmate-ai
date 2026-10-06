<?php
// app/Models/ComplainType.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComplainType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    protected $casts = [
        'status' => 'string'
    ];

     /**
     * Get the complains for this type.
     */
    public function complains()
    {
        return $this->hasMany(Complain::class);
    }
}