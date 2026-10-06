<?php
// app/Models/InnerCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InnerCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sub_category_id',
        'image',
        'description',
        'status'
    ];

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($innerCategory) {
            $innerCategory->slug = \Illuminate\Support\Str::slug($innerCategory->name);
        });
        
        static::updating(function ($innerCategory) {
            $innerCategory->slug = \Illuminate\Support\Str::slug($innerCategory->name);
        });
    }
}