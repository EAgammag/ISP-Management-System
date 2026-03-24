@extends('layouts.admin')

@section('page-title', 'Network Device Details')
@section('page-description', 'View device information and connected customers')

@section('content')
<!-- Device Info Card -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="w-3 h-3 rounded-full mr-3 {{ $device->status == 'online' ? 'bg-green-500' : ($device->status == 'offline' ? 'bg-red-500' : 'bg-yellow-500') }}"></div>
                <h3 class="text-xl font-semibold text-gray-800">{{ $device->name }}</h3>
            </div>
            <span class="px-3 py-1 text-sm font-semibold rounded-full
                {{ $device->status == 'online' ? 'bg-green-100 text-green-800' : 
                   ($device->status == 'offline' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                {{ ucfirst($device->status) }}
            </span>
        </div>
    </div>
    
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div>
                <p class="text-sm text-gray-600">Device Type</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ ucfirst($device->type) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">IP Address</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $device->ip_address }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">MAC Address</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $device->mac_address ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Location</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $device->location ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Connected Clients</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $deviceLoad }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Last Seen</p>
                <p class="text-lg font-semibold text-gray-900 mt-1">{{ $device->last_seen ? $device->last_seen->diffForHumans() : 'Never' }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Device Performance -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-600 mb-2">CPU Usage</p>
        <div class="flex items-end">
            <p class="text-3xl font-bold text-gray-900">{{ $device->cpu_usage ?? 0 }}%</p>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $device->cpu_usage ?? 0 }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-600 mb-2">Memory Usage</p>
        <div class="flex items-end">
            <p class="text-3xl font-bold text-gray-900">{{ $device->memory_usage ?? 0 }}%</p>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2 mt-3">
            <div class="bg-green-600 h-2 rounded-full" style="width: {{ $device->memory_usage ?? 0 }}%"></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <p class="text-sm text-gray-600 mb-2">Uptime</p>
        <div class="flex items-end">
            <p class="text-3xl font-bold text-gray-900">{{ number_format($device->uptime ?? 0) }}</p>
            <p class="text-sm text-gray-600 ml-2 mb-1">hours</p>
        </div>
    </div>
</div>

<!-- Connected Customers -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Connected Customers</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($connectedCustomers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $customer->email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ $customer->phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-blue-600 hover:text-blue-900">
                            View Details
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        No customers connected to this device.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($device->notes)
<!-- Notes -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <h4 class="text-sm font-semibold text-blue-900 mb-2">Notes</h4>
    <p class="text-sm text-blue-800">{{ $device->notes }}</p>
</div>
@endif

<!-- Back Button -->
<div class="mt-6">
    <a href="{{ route('admin.network.index') }}" class="text-blue-600 hover:text-blue-900">
        ← Back to Network Devices
    </a>
</div>
@endsection
