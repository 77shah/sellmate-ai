<?php
// app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    // 🔥 ID auto-increment INT hai, isliye ye hatao
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'image',
        'description',
        'status'
    ];

    // Default value set karein
    protected $attributes = [
        'status' => 'active',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            // 🔥 ID ab auto-increment hai, isliye manual UUID generate nahi karna
            $category->slug = Str::slug($category->name);
        });
        
        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // ===== RELATIONSHIPS =====
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

      public function isActive()
    {
        return $this->status === 'active';
    }

    public function isInactive()
    {
        return $this->status === 'inactive';
    }

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
}