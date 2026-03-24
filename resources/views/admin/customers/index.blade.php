@extends('layouts.admin')

@section('page-title', 'Client Management')
@section('page-description', 'Manage subscribers, accounts, and IP allocations')

@section('content')
<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-600 text-sm">Total Clients</p>
        <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-600 text-sm">Active</p>
        <p class="text-2xl font-bold text-green-600">{{ $activeCustomers }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-600 text-sm">Suspended</p>
        <p class="text-2xl font-bold text-red-600">{{ $suspendedCustomers }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-4">
        <p class="text-gray-600 text-sm">New This Month</p>
        <p class="text-2xl font-bold text-blue-600">{{ $newThisMonth }}</p>
    </div>
</div>

<!-- Actions & Search -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex-1 w-full md:w-auto">
            <input type="text" id="searchInput" placeholder="Search clients..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.customers.create') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                + Add Customer
            </a>
            <button onclick="exportCustomers()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                Export
            </button>
        </div>
    </div>
</div>

<!-- Customers Grouped by Plan -->
@forelse($customersByPlan as $planName => $planData)
<div class="bg-white rounded-lg shadow overflow-hidden mb-6">
    <!-- Plan Header -->
    <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 px-6 py-4">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold text-white">{{ $planName }}</h3>
                @if($planData['plan'])
                <p class="text-cyan-100 text-sm mt-1">
                    {{ $planData['plan']->speed }} Mbps | ₱{{ number_format($planData['plan']->price, 2) }}/month
                </p>
                @endif
            </div>
            <div class="text-white">
                <span class="bg-white bg-opacity-20 px-4 py-2 rounded-full font-semibold">
                    {{ $planData['customers']->count() }} Clients
                </span>
            </div>
        </div>
    </div>
    
    <!-- Customers Table -->
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($planData['customers'] as $customer)
            <tr class="hover:bg-gray-50 customer-row">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 bg-cyan-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ substr($customer->name, 0, 1) }}
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                            <div class="text-sm text-gray-500">ID: #{{ $customer->id }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">{{ $customer->email }}</div>
                    <div class="text-sm text-gray-500">{{ $customer->phone }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if($customer->ipAllocation)
                        <div class="text-sm text-gray-900">{{ $customer->ipAllocation->ip_address }}</div>
                        <span class="px-2 py-1 text-xs rounded-full {{ $customer->ipAllocation->type == 'static' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($customer->ipAllocation->type) }}
                        </span>
                    @else
                        <span class="text-sm text-gray-500">Not Assigned</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        {{ $customer->connection_status == 'active' ? 'bg-green-100 text-green-800' : 
                           ($customer->connection_status == 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($customer->connection_status ?? 'inactive') }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="{{ $customer->balance < 0 ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                        ₱{{ number_format($customer->balance ?? 0, 2) }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.customers.show', $customer) }}" 
                           class="text-cyan-600 hover:text-cyan-900 transition" 
                           title="View Details">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('admin.customers.edit', $customer) }}" 
                           class="text-blue-600 hover:text-blue-900 transition" 
                           title="Edit Customer">
                            <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @if($customer->connection_status == 'active')
                            <form action="{{ route('admin.customers.suspend', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to suspend this client?');">
                                @csrf
                                <button type="submit" 
                                        class="text-orange-600 hover:text-orange-900 transition" 
                                        title="Suspend Account">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.customers.activate', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to activate this client?');">
                                @csrf
                                <button type="submit" 
                                        class="text-green-600 hover:text-green-900 transition" 
                                        title="Activate Account">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this client? This action cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-red-600 hover:text-red-900 transition" 
                                    title="Delete Customer">
                                <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@empty
<div class="bg-white rounded-lg shadow p-12 text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    <h3 class="mt-2 text-lg font-medium text-gray-900">No clients found</h3>
    <p class="mt-1 text-sm text-gray-500">Get started by adding your first customer.</p>
    <div class="mt-6">
        <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-cyan-600 hover:bg-cyan-700">
            + Add Customer
        </a>
    </div>
</div>
@endforelse

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('.customer-row');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Export functionality
    function exportCustomers() {
        // Get all visible customer data
        const rows = document.querySelectorAll('.customer-row');
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Name,Email,Phone,Plan,IP Address,Status,Balance\n";
        
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const cells = row.querySelectorAll('td');
                const name = cells[0].querySelector('.font-medium').textContent;
                const email = cells[1].querySelector('.text-gray-900').textContent;
                const phone = cells[1].querySelector('.text-gray-500').textContent;
                const plan = row.closest('.bg-white').previousElementSibling?.querySelector('h3')?.textContent || 'N/A';
                const ip = cells[2].textContent.trim();
                const status = cells[3].textContent.trim();
                const balance = cells[4].textContent.trim();
                
                csvContent += `"${name}","${email}","${phone}","${plan}","${ip}","${status}","${balance}"\n`;
            }
        });
        
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", `customers_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

@if(session('success'))
<div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50" id="success-alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
<script>
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }
    }, 3000);
</script>
@endif
@endsection
