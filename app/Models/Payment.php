<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'course_id',
        'amount',
        'currency',
        'payment_method',
        'stripe_payment_intent',
        'status',
        'metadata'
    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'float'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship with Course
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function cachePayment()
    {
        Cache::put(
            "payment:{$this->id}", 
            $this->toArray(), 
            now()->addHours(24)
        );
    }

    public static function getCachedPayment($paymentId)
    {
        return Cache::remember(
            "payment:{$paymentId}", 
            now()->addHours(24), 
            function () use ($paymentId) {
                return self::findOrFail($paymentId);
            }
        );
    }

    public function clearCache()
    {
        Cache::forget("payment:{$this->id}");
    }
}