<?php
// app/Models/SubCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'category_id',
        'image',
        'description',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function innerCategories()
    {
        return $this->hasMany(InnerCategory::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($subCategory) {
            $subCategory->slug = \Illuminate\Support\Str::slug($subCategory->name);
        });
        
        static::updating(function ($subCategory) {
            $subCategory->slug = \Illuminate\Support\Str::slug($subCategory->name);
        });
    }
}