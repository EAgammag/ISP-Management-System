<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NetworkDevice extends Model
{
    protected $fillable = [
        'name', 'type', 'ip_address', 'mac_address', 'location',
        'status', 'uptime', 'cpu_usage', 'memory_usage',
        'connected_clients', 'last_seen', 'notes'
    ];

    protected $casts = [
        'uptime' => 'integer',
        'cpu_usage' => 'decimal:2',
        'memory_usage' => 'decimal:2',
        'connected_clients' => 'integer',
        'last_seen' => 'datetime',
    ];

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'network_device_id');
    }
}
