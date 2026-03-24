<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NetworkDevice;
use App\Models\Customer;
use App\Models\DataUsage;
use Illuminate\Http\Request;

class NetworkController extends Controller
{
    public function index()
    {
        $devices = NetworkDevice::orderBy('status', 'desc')->paginate(20);
        
        $totalDevices = NetworkDevice::count();
        $onlineDevices = NetworkDevice::where('status', 'online')->count();
        $offlineDevices = NetworkDevice::where('status', 'offline')->count();
        $maintenanceDevices = NetworkDevice::where('status', 'maintenance')->count();
        
        // Network bandwidth usage stats
        $totalBandwidthUsage = DataUsage::whereDate('date', today())->sum('data_used');
        $activeSessions = Customer::where('connection_status', 'active')->count();
        
        return view('admin.network.index', compact(
            'devices',
            'totalDevices',
            'onlineDevices',
            'offlineDevices',
            'maintenanceDevices',
            'totalBandwidthUsage',
            'activeSessions'
        ));
    }

    public function show(NetworkDevice $device)
    {
        $device->load('customers');
        
        // Get connected customers
        $connectedCustomers = $device->customers()
            ->where('connection_status', 'active')
            ->get();
        
        // Calculate device load
        $deviceLoad = $connectedCustomers->count();
        
        return view('admin.network.show', compact('device', 'connectedCustomers', 'deviceLoad'));
    }

    public function scan()
    {
        // Simulate network scan - in production this would connect to actual network monitoring tools
        $devices = NetworkDevice::all();
        
        foreach ($devices as $device) {
            // Simulate status check - in reality would ping device
            $device->last_seen = now();
            $device->save();
        }
        
        return redirect()->route('admin.network.index')
            ->with('success', 'Network scan completed successfully');
    }
}
