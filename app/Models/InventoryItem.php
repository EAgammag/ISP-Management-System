<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'name', 'type', 'model', 'serial_number', 'mac_address',
        'status', 'customer_id', 'quantity', 'purchase_price',
        'purchase_date', 'supplier', 'location', 'warranty_expires', 'notes'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'purchase_date' => 'date',
        'warranty_expires' => 'date',
        'quantity' => 'integer',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function scopeInStock($query)
    {
        return $query->where('status', 'in_stock');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeLowStock($query)
    {
        return $query->where('status', 'in_stock')
            ->where('quantity', '<', 10);
    }
}
