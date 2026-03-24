<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BandwidthPolicy extends Model
{
    protected $fillable = [
        'name', 'service_plan_id', 'download_speed', 'upload_speed',
        'burst_speed', 'priority', 'contention_ratio', 'data_cap',
        'throttle_after_cap', 'throttled_speed', 'time_based_rules',
        'is_active', 'description'
    ];

    protected $casts = [
        'download_speed' => 'integer',
        'upload_speed' => 'integer',
        'burst_speed' => 'integer',
        'priority' => 'integer',
        'data_cap' => 'integer',
        'throttled_speed' => 'integer',
        'time_based_rules' => 'array',
        'is_active' => 'boolean',
    ];

    public function servicePlan()
    {
        return $this->belongsTo(ServicePlan::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
