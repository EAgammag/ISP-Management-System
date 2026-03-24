<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Customer extends Model
{
    protected $fillable = [
        'user_id', 'account_number', 'server_account_name', 'name', 'email', 'phone', 'address', 
        'connection_status', 'balance',
        'email_notifications', 'sms_notifications', 
        'billing_reminders', 'promotional_offers'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'billing_reminders' => 'boolean',
        'promotional_offers' => 'boolean',
    ];

    /**
     * Get the user associated with the customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all subscriptions for the customer.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the active subscription for the customer.
     */
    public function activeSubscription()
    {
        return $this->subscriptions()
            ->with('servicePlan')
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->first();
    }

    /**
     * Get all invoices for the customer.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get all payments for the customer.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'user_id', 'user_id');
    }

    /**
     * Get all tickets for the customer.
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get all data usage records for the customer.
     */
    public function dataUsages(): HasMany
    {
        return $this->hasMany(DataUsage::class);
    }

    /**
     * Get all addons for the customer.
     */
    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(Addon::class, 'customer_addon')
            ->withPivot('purchased_at', 'expires_at', 'status')
            ->withTimestamps();
    }

    /**
     * Get current data usage for the month.
     */
    public function getCurrentMonthUsage()
    {
        return $this->dataUsages()
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->sum('data_used');
    }

    /**
     * Get IP allocation for the customer.
     */
    public function ipAllocation()
    {
        return $this->hasOne(IpAllocation::class);
    }

    /**
     * Get all inventory items assigned to the customer.
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }
}
