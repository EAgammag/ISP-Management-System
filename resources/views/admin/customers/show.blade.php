@extends('layouts.admin')

@section('page-title', 'Client Details')
@section('page-description', $customer->name)

@section('content')
<!-- Customer Header -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-start">
            <div class="flex items-center">
                <div class="h-16 w-16 bg-cyan-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                    {{ substr($customer->name, 0, 1) }}
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $customer->name }}</h2>
                    <p class="text-gray-600">Client ID: #{{ $customer->id }}</p>
                    <span class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded-full 
                        {{ $customer->connection_status == 'active' ? 'bg-green-100 text-green-800' : 
                           ($customer->connection_status == 'suspended' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                        {{ ucfirst($customer->connection_status ?? 'inactive') }}
                    </span>
                </div>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.customers.edit', $customer) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Edit Client
                </a>
                @if($customer->connection_status == 'active')
                    <form action="{{ route('admin.customers.suspend', $customer) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to suspend this client?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-semibold transition">
                            Suspend Account
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.customers.activate', $customer) }}" method="POST"
                          onsubmit="return confirm('Are you sure you want to activate this client?');">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-semibold transition">
                            Activate Account
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Contact Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Contact Information</h3>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600">Email</p>
                <p class="text-gray-900 font-medium">{{ $customer->email }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Phone</p>
                <p class="text-gray-900 font-medium">{{ $customer->phone }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Address</p>
                <p class="text-gray-900">{{ $customer->address }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Member Since</p>
                <p class="text-gray-900">{{ $customer->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Account Summary -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Account Summary</h3>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-600">Account Number</p>
                <p class="text-gray-900 font-medium">{{ $customer->account_number ?? 'Not Assigned' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Server Account Name</p>
                <p class="text-gray-900 font-medium">{{ $customer->server_account_name ?? 'Not Set' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Current Balance</p>
                <p class="text-2xl font-bold {{ ($customer->balance ?? 0) < 0 ? 'text-red-600' : 'text-green-600' }}">
                    ₱{{ number_format($customer->balance ?? 0, 2) }}
                </p>
                @if(($customer->balance ?? 0) > 0)
                    <p class="text-xs text-green-600 mt-1">Credit Available</p>
                @elseif(($customer->balance ?? 0) < 0)
                    <p class="text-xs text-red-600 mt-1">Amount Owed</p>
                @endif
            </div>
            @php
                $lastPayment = $customer->payments()->orderBy('payment_date', 'desc')->first();
                $excessPayment = ($customer->balance ?? 0) > 0 ? $customer->balance : 0;
            @endphp
            @if($lastPayment)
            <div class="pt-3 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-2">Last Payment</p>
                <div class="bg-gray-50 rounded p-3 space-y-1">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Amount Paid:</span>
                        <span class="font-semibold text-gray-900">₱{{ number_format($lastPayment->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Date:</span>
                        <span class="text-gray-700">{{ $lastPayment->payment_date ? \Carbon\Carbon::parse($lastPayment->payment_date)->format('M d, Y') : 'N/A' }}</span>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('admin.payments.receipt', $lastPayment->id) }}" 
                           class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition duration-150">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download Receipt
                        </a>
                    </div>
                </div>
                @if($excessPayment > 0)
                <div class="mt-3 bg-green-50 border border-green-200 rounded p-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-green-800">Excess Payment</p>
                            <p class="text-xs text-green-600 mt-1">This credit will be applied to the next invoice</p>
                        </div>
                        <p class="text-lg font-bold text-green-700">₱{{ number_format($excessPayment, 2) }}</p>
                    </div>
                </div>
                @endif
            </div>
            @endif
            <div>
                <p class="text-sm text-gray-600">Total Invoices</p>
                <p class="text-gray-900 font-medium">{{ $customer->invoices->count() }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Open Tickets</p>
                <p class="text-gray-900 font-medium">{{ $customer->tickets->where('status', '!=', 'closed')->count() }}</p>
            </div>
        </div>
    </div>

    <!-- Current Service Plan -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Current Service Plan</h3>
        @php
            $activeSubscription = $customer->subscriptions->where('status', 'active')
                ->where('end_date', '>=', now())
                ->first();
        @endphp
        @if($activeSubscription && $activeSubscription->servicePlan)
            <div class="space-y-3">
                <div>
                    <p class="text-sm text-gray-600">Plan Name</p>
                    <p class="text-xl font-bold text-gray-900">{{ $activeSubscription->servicePlan->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Speed</p>
                    <p class="text-gray-900 font-medium">{{ $activeSubscription->servicePlan->speed }} Mbps</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Monthly Fee</p>
                    <p class="text-gray-900 font-medium">₱{{ number_format($activeSubscription->servicePlan->price, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Subscription Period</p>
                    <p class="text-gray-900">{{ $activeSubscription->start_date->format('M d, Y') }} - {{ $activeSubscription->end_date->format('M d, Y') }}</p>
                </div>
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <p class="mt-2 text-gray-500">No active subscription</p>
            </div>
        @endif
    </div>
</div>

<!-- Recent Invoices -->
<div class="bg-white rounded-lg shadow mt-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Recent Invoices</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customer->invoices->take(10) as $invoice)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $invoice->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $invoice->description }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($invoice->amount, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @php
                            $paidPayment = $invoice->payments()->where('status', 'paid')->first();
                        @endphp
                        @if($paidPayment)
                        <a href="{{ route('admin.payments.receipt', $paidPayment->id) }}" 
                           class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition duration-150"
                           title="Download Receipt">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Receipt
                        </a>
                        @else
                        <span class="text-gray-400 text-xs">No receipt</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No invoices found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Tickets -->
<div class="bg-white rounded-lg shadow mt-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Recent Support Tickets</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customer->tickets->take(10) as $ticket)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $ticket->id }}</td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $ticket->subject }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $ticket->priority == 'urgent' ? 'bg-red-100 text-red-800' : 
                               ($ticket->priority == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $ticket->status == 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($ticket->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $ticket->created_at->diffForHumans() }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No tickets found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Assigned Equipment -->
@if($customer->inventoryItems && $customer->inventoryItems->count() > 0)
<div class="bg-white rounded-lg shadow mt-6">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Assigned Equipment</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($customer->inventoryItems as $item)
            <div class="border border-gray-200 rounded-lg p-4">
                <p class="font-semibold text-gray-900">{{ $item->item_type }}</p>
                <p class="text-sm text-gray-600">{{ $item->model }}</p>
                <p class="text-xs text-gray-500 mt-1">SN: {{ $item->serial_number }}</p>
                <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                    {{ ucfirst($item->category) }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<div class="mt-6">
    <a href="{{ route('admin.customers.index') }}" 
       class="inline-flex items-center text-cyan-600 hover:text-cyan-700 font-semibold">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to Customers
    </a>
</div>

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
