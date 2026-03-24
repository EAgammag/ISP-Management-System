<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BandwidthPolicy;
use App\Models\Customer;
use App\Models\DataUsage;
use Illuminate\Http\Request;

class BandwidthController extends Controller
{
    public function index()
    {
        $policies = BandwidthPolicy::paginate(20);
        
        $totalPolicies = BandwidthPolicy::count();
        $activePolicies = BandwidthPolicy::where('status', 'active')->count();
        
        // Today's bandwidth usage
        $todayUsage = DataUsage::whereDate('created_at', today())->sum('total_usage');
        
        // Top bandwidth users
        $topUsers = Customer::with('dataUsages')
            ->whereHas('dataUsages', function($query) {
                $query->whereDate('created_at', today());
            })
            ->get()
            ->map(function($customer) {
                $customer->today_usage = $customer->dataUsages()
                    ->whereDate('created_at', today())
                    ->sum('total_usage');
                return $customer;
            })
            ->sortByDesc('today_usage')
            ->take(10);
        
        return view('admin.bandwidth.index', compact(
            'policies',
            'totalPolicies',
            'activePolicies',
            'todayUsage',
            'topUsers'
        ));
    }

    public function create()
    {
        return view('admin.bandwidth.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'download_speed' => 'required|integer|min:1',
            'upload_speed' => 'required|integer|min:1',
            'data_cap' => 'nullable|integer|min:1',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:active,inactive',
        ]);

        $policy = BandwidthPolicy::create($validated);

        return redirect()->route('admin.bandwidth.index')
            ->with('success', 'Bandwidth policy created successfully');
    }

    public function edit(BandwidthPolicy $policy)
    {
        return view('admin.bandwidth.edit', compact('policy'));
    }

    public function update(Request $request, BandwidthPolicy $policy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'download_speed' => 'required|integer|min:1',
            'upload_speed' => 'required|integer|min:1',
            'data_cap' => 'nullable|integer|min:1',
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:active,inactive',
        ]);

        $policy->update($validated);

        return redirect()->route('admin.bandwidth.index')
            ->with('success', 'Bandwidth policy updated successfully');
    }
}
