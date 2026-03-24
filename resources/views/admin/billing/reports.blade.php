@extends('layouts.admin')

@section('page-title', 'Financial Reports')
@section('page-description', 'Detailed financial reports and analytics')

@section('content')
<!-- Annual Revenue Chart -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Annual Revenue - {{ $year }}</h3>
    </div>
    <div class="p-6">
        <div class="flex items-end justify-between space-x-2" style="height: 300px;">
            @foreach($annualRevenue as $data)
            <div class="flex-1 flex flex-col items-center">
                <div class="w-full bg-blue-500 rounded-t hover:bg-blue-600 transition relative group" 
                     style="height: {{ $data['revenue'] > 0 ? max(($data['revenue'] / max(array_column($annualRevenue, 'revenue'), 1)) * 100, 5) : 5 }}%">
                    <div class="absolute bottom-full mb-2 hidden group-hover:block bg-gray-800 text-white text-xs rounded py-1 px-2 whitespace-nowrap">
                        ₱{{ number_format($data['revenue'], 2) }}
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-2 transform -rotate-45 origin-top-left">{{ $data['month'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Revenue by Plan -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Revenue by Service Plan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subscribers</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monthly Revenue</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Share</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php $totalRevenue = $planRevenue->sum('revenue'); @endphp
                @foreach($planRevenue as $plan)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $plan['plan'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $plan['subscribers'] }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-lg font-semibold text-gray-900">₱{{ number_format($plan['revenue'], 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $totalRevenue > 0 ? ($plan['revenue'] / $totalRevenue * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600">{{ $totalRevenue > 0 ? number_format($plan['revenue'] / $totalRevenue * 100, 1) : 0 }}%</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Aging Report -->
<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Accounts Receivable Aging</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200">
                <p class="text-sm text-gray-600 mb-2">0-30 Days</p>
                <p class="text-2xl font-bold text-yellow-700">₱{{ number_format($agingReport['0-30'], 2) }}</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 border border-orange-200">
                <p class="text-sm text-gray-600 mb-2">31-60 Days</p>
                <p class="text-2xl font-bold text-orange-700">₱{{ number_format($agingReport['31-60'], 2) }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                <p class="text-sm text-gray-600 mb-2">61-90 Days</p>
                <p class="text-2xl font-bold text-red-700">₱{{ number_format($agingReport['61-90'], 2) }}</p>
            </div>
            <div class="bg-red-100 rounded-lg p-4 border border-red-300">
                <p class="text-sm text-gray-600 mb-2">90+ Days</p>
                <p class="text-2xl font-bold text-red-800">₱{{ number_format($agingReport['90+'], 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
