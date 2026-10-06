<?php
// app/Models/Plan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property float $price_usd
 * @property string $currency
 * @property string $currency_usd
 * @property string $billing_period
 * @property array $features
 * @property bool $is_active
 * @property bool $is_popular
 * @property int $sort_order
 */

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',           // 🔥 NEW — UI ke liye
        'price',                 // INR price
        'price_usd',             // 🔥 NEW — USD price
        'currency',              // INR
        'currency_usd',          // 🔥 NEW — USD
        'billing_period',
        'ai_messages_limit',
        'channels_limit',
        'team_seats_limit',
        'storage_limit',
        'features',
        'is_active',
        'is_popular',            // 🔥 NEW — "MOST POPULAR" badge
        'badge',                 // 🔥 NEW — custom badge text
        'sort_order',
        'razorpay_plan_id',
        'stripe_price_id',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'is_active' => 'boolean',
        'is_popular' => 'boolean',
        'sort_order' => 'integer',
    ];

    // 🔥 DEFAULT VALUES
    protected $attributes = [
        'features' => '[]',
        'is_active' => true,
        'is_popular' => false,
        'currency' => 'INR',
        'currency_usd' => 'USD',
        'price_usd' => 0,
        'billing_period' => 'monthly',
        'ai_messages_limit' => 0,
        'channels_limit' => 1,
        'team_seats_limit' => 1,
        'storage_limit' => 100,
        'sort_order' => 0,
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'current_plan_id');
    }

    // ==========================================
    // HELPERS
    // ==========================================
    public function isFree()
    {
        return $this->price == 0 && ($this->price_usd ?? 0) == 0;
    }

    /**
     * 🔥 Currency ke hisaab se price do
     */
    public function getPriceForCurrency($currency = 'INR')
    {
        if (strtoupper($currency) === 'USD') {
            return (float) ($this->price_usd ?? 0);
        }
        return (float) $this->price;
    }

    /**
     * 🔥 Currency symbol do
     */
    public static function getCurrencySymbol($currency = 'INR')
    {
        return strtoupper($currency) === 'USD' ? '$' : '₹';
    }

    /**
     * 🔥 Formatted price with symbol
     */
    public function getFormattedPrice($currency = 'INR')
    {
        $price = $this->getPriceForCurrency($currency);
        $symbol = self::getCurrencySymbol($currency);
        
        if ($price == 0) {
            return 'FREE';
        }
        
        return $symbol . number_format($price, 0);
    }

    /**
     * Existing attribute accessor (INR default)
     */
    public function getFormattedPriceAttribute()
    {
        return $this->getFormattedPrice('INR');
    }

    /**
     * Limits array
     */
    public function getLimitsAttribute()
    {
        return [
            'ai_messages' => $this->ai_messages_limit,
            'channels' => $this->channels_limit,
            'team_seats' => $this->team_seats_limit,
            'storage' => $this->storage_limit,
        ];
    }

    /**
     * 🔥 Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('price');
    }
}