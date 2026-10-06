<?php
// app/Models/UnansweredQuery.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnansweredQuery extends Model
{
    use HasFactory;

    protected $table = 'unanswered_queries';

    protected $fillable = [
        'tenant_id',
        'customer_number',
        'customer_name',
        'question',
        'ai_reply',
        'status',
        'resolved_by',
        'owner_reply',
        'owner_reply_images',   // 🔥 JSON array of image paths (public/owner-replies/...)
        'owner_reply_url',      // 🔥 URL string
        'reply_sent_at',        // 🔥 Timestamp
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'reply_sent_at' => 'datetime',
        'owner_reply_images' => 'array',    // 🔥 JSON → Array
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================
    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id', 'tenant_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    // ==========================================
    // SCOPES
    // ==========================================
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeResolved($query)
    {
        return $query->where('status', 'resolved');
    }

    public function scopeIgnored($query)
    {
        return $query->where('status', 'ignored');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // ==========================================
    // 🔥 HELPERS
    // ==========================================

    /**
     * Get public URLs for images (from public/ folder)
     */
    public function getImageUrls()
    {
        if (empty($this->owner_reply_images)) {
            return [];
        }

        return array_map(function ($path) {
            // 🔥 Path: owner-replies/tenant_XXX/file.png
            // 🔥 URL: http://domain.com/owner-replies/tenant_XXX/file.png
            return asset($path);
        }, $this->owner_reply_images);
    }

    /**
     * Get first image URL
     */
    public function getFirstImageUrl()
    {
        if (empty($this->owner_reply_images)) {
            return null;
        }

        return asset($this->owner_reply_images[0]);
    }

    /**
     * Check if has images
     */
    public function hasImages()
    {
        return !empty($this->owner_reply_images) && count($this->owner_reply_images) > 0;
    }

    /**
     * Check if has URL
     */
    public function hasUrl()
    {
        return !empty($this->owner_reply_url);
    }

    /**
     * Image count attribute
     */
    public function getImageCountAttribute()
    {
        return $this->owner_reply_images ? count($this->owner_reply_images) : 0;
    }

    /**
     * Status badge color
     */
    public function getStatusColorAttribute()
    {
        return match ($this->status) {
            'pending' => 'warning',
            'resolved' => 'success',
            'ignored' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Status label
     */
    public function getStatusLabelAttribute()
    {
        return match ($this->status) {
            'pending' => '⏳ Pending',
            'resolved' => '✅ Resolved',
            'ignored' => '⏸️ Ignored',
            default => 'Unknown',
        };
    }

    /**
     * Display name for customer
     */
    public function getCustomerDisplayNameAttribute()
    {
        return $this->customer_name ?? $this->customer_number;
    }

    /**
     * Check if image files exist in public folder
     */
    public function imagesExist()
    {
        if (empty($this->owner_reply_images)) {
            return false;
        }

        foreach ($this->owner_reply_images as $path) {
            if (!file_exists(public_path($path))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Delete image files when record is deleted
     */
    protected static function booted()
    {
        static::deleting(function ($query) {
            if (!empty($query->owner_reply_images)) {
                foreach ($query->owner_reply_images as $path) {
                    $fullPath = public_path($path);
                    if (file_exists($fullPath)) {
                        @unlink($fullPath);
                    }
                }
            }
        });
    }
}