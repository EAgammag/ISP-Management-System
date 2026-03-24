<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Addon extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'data_amount', 
        'price', 'validity_days', 'is_active'
    ];

    protected $casts = [
        'data_amount' => 'decimal:2',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get all customers that have this addon.
     */
    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_addon')
            ->withPivot('purchased_at', 'expires_at', 'status')
            ->withTimestamps();
    }

    /**
     * Scope for active addons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for data boosters.
     */
    public function scopeDataBoosters($query)
    {
        return $query->where('type', 'data_booster');
    }
}
