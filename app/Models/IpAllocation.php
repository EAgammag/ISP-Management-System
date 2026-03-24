<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IpAllocation extends Model
{
    protected $fillable = [
        'customer_id', 'ip_address', 'type', 'subnet_mask',
        'gateway', 'dns_primary', 'dns_secondary', 'status',
        'allocated_at', 'released_at'
    ];

    protected $casts = [
        'allocated_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeStatic($query)
    {
        return $query->where('type', 'static');
    }

    public function scopeDynamic($query)
    {
        return $query->where('type', 'dynamic');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
