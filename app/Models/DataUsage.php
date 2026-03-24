<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataUsage extends Model
{
    protected $fillable = [
        'customer_id', 'date', 'data_used', 'data_uploaded', 
        'data_downloaded', 'session_duration', 'is_active'
    ];

    protected $casts = [
        'date' => 'date',
        'data_used' => 'decimal:2',
        'data_uploaded' => 'decimal:2',
        'data_downloaded' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the customer that owns the data usage.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Scope for today's usage.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope for current month's usage.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
            ->whereYear('date', now()->year);
    }
}
