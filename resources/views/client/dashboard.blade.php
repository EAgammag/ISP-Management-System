@extends('layouts.client')

@section('page-title', 'Dashboard')
@section('page-description', 'Welcome to your customer portal')

@section('content')
<!-- Connection Status Banner -->
<div class="mb-6 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg p-6 shadow-lg">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-green-400 rounded-full animate-pulse mr-3"></div>
            <div>
                <h3 class="text-white text-xl font-bold">Connection Status: 
                    <span class="text-green-200">{{ $connectionStatus ?? 'Active' }}</span>
                </h3>
                <p class="text-blue-100 text-sm">Last updated: {{ now()->format('h:i A') }}</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-white text-2xl font-bold">₱{{ number_format($balance ?? 0, 2) }}</p>
            <p class="text-blue-100 text-sm">Account Balance</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Client Information -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-700 font-bold text-lg">Client Information</h3>
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
        </div>
        <div class="space-y-3">
            <div class="space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Full Name:</span>
                    <span class="text-gray-900 font-semibold">{{ auth()->user()->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Email:</span>
                    <span class="text-gray-900">{{ auth()->user()->email }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Account ID:</span>
                    <span class="text-gray-900">#{{ str_pad(auth()->user()->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-200">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Member Since:</span>
                    <span class="text-gray-900">{{ auth()->user()->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm mt-1">
                    <span class="text-gray-600">Account Status:</span>
                    <span class="text-green-600 font-semibold">Active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Plan -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-700 font-bold text-lg">Current Plan</h3>
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <div class="space-y-3">
            @if($subscription && $subscription->servicePlan)
            <div>
                <h4 class="text-gray-900 text-xl font-bold">{{ $subscription->servicePlan->name }}</h4>
                <p class="text-blue-600 text-2xl font-bold mt-1">{{ $subscription->servicePlan->speed ?? 'N/A' }} Mbps</p>
            </div>
            <div class="pt-3 border-t border-gray-200 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Monthly Fee:</span>
                    <span class="text-gray-900 font-semibold">₱{{ number_format($subscription->servicePlan->price, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Renewal Date:</span>
                    <span class="text-gray-900">{{ $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'N/A' }}</span>
                </div>
            </div>
            @else
            <p class="text-gray-500">No active subscription</p>
            @endif
        </div>
    </div>

    <!-- Payment Status -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-700 font-bold text-lg">Payment Status</h3>
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
            </svg>
        </div>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600 text-sm">Status:</span>
                    <span class="text-lg font-bold {{ $unpaidInvoices > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $unpaidInvoices > 0 ? 'On Due' : 'Paid' }}
                    </span>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-200 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Next Due Date:</span>
                    <span class="text-gray-900 font-semibold">
                        {{ $subscription && $subscription->end_date ? $subscription->end_date->format('M d, Y') : 'N/A' }}
                    </span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Amount Due:</span>
                    <span class="text-gray-900 font-bold text-lg">
                        ₱{{ $subscription && $subscription->servicePlan ? number_format($subscription->servicePlan->price, 2) : '0.00' }}
                    </span>
                </div>
                @if($unpaidInvoices > 0)
                <div class="pt-2">
                    <a href="{{ route('client.billing.index') }}" 
                        class="block w-full bg-red-600 hover:bg-red-700 text-white text-center py-2 px-4 rounded-lg font-medium transition duration-200">
                        Pay Now ({{ $unpaidInvoices }} {{ $unpaidInvoices > 1 ? 'Invoices' : 'Invoice' }})
                    </a>
                </div>
                @else
                <div class="pt-2">
                    <a href="{{ route('client.billing.index') }}" 
                        class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-4 rounded-lg font-medium transition duration-200">
                        View Payment History
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Invoices -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Invoices</h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Invoice #</th>
                            <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700">Date</th>
                            <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Amount</th>
                            <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $invoice)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800">#INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="py-3 px-4 text-sm text-gray-600">{{ $invoice->created_at->format('M d, Y') }}</td>
                            <td class="py-3 px-4 text-sm font-bold text-gray-900 text-right">₱{{ number_format($invoice->amount, 2) }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-xs px-3 py-1 rounded-full font-semibold
                                    {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->status == 'pending' ? 'bg-orange-100 text-orange-800' : 'bg-red-100 text-red-800') }}">
                                    {{ $invoice->status == 'paid' ? 'Paid' : ($invoice->status == 'pending' ? 'On Due' : 'Overdue') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-gray-500 text-center py-4">No invoices found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                <a href="{{ route('client.billing.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All Invoices →
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Tickets -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Support Tickets</h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @forelse($recentTickets as $ticket)
                <a href="{{ route('client.tickets.show', $ticket) }}" class="flex justify-between items-center py-2 border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 rounded px-2 -mx-2">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $ticket->subject }}</p>
                        <p class="text-sm text-gray-600">{{ $ticket->created_at->format('M d, Y') }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full 
                        {{ $ticket->status == 'resolved' ? 'bg-green-100 text-green-800' : 
                           ($ticket->status == 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($ticket->status) }}
                    </span>
                </a>
                @empty
                <p class="text-gray-500 text-center py-4">No tickets found</p>
                @endforelse
            </div>
            <div class="mt-4">
                <a href="{{ route('client.tickets.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    View All Tickets →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
