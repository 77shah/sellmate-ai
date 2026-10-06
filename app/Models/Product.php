<?php
// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // 🔥 REMOVE THESE LINES - ID auto increment ke liye
    // public $incrementing = false;
    // protected $keyType = 'string';

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'price',
        'stock_qty',
        'sku',
        'category_id',
        'images',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock_qty' => 'integer',
        'images' => 'array',
    ];

    // Helper method to get full image URLs
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }
        
        return array_map(function ($image) {
            return asset($image);
        }, $this->images);
    }

    // Helper method to get first image
    public function getFirstImageAttribute()
    {
        if ($this->images && count($this->images) > 0) {
            return asset($this->images[0]);
        }
        return null;
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}