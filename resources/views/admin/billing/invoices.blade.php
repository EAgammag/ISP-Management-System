@extends('layouts.admin')

@section('page-title', 'All Invoices')
@section('page-description', 'View and manage all invoices')

@section('content')
<!-- Filter Bar -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <div class="flex items-center gap-4">
        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option>All Status</option>
            <option>Paid</option>
            <option>Unpaid</option>
            <option>Overdue</option>
        </select>
        <input type="text" placeholder="Search customer..." 
            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        <button class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
            Search
        </button>
    </div>
</div>

<!-- Invoices Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        #INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $invoice->customer->name }}</div>
                        <div class="text-sm text-gray-500">{{ $invoice->customer->email }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-lg font-semibold text-gray-900">₱{{ number_format($invoice->amount, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="text-gray-900">{{ $invoice->due_date->format('M d, Y') }}</div>
                        @if($invoice->status == 'unpaid' && $invoice->due_date < now())
                        <div class="text-red-600 text-xs">Overdue by {{ $invoice->due_date->diffInDays(now()) }} days</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 
                               ($invoice->due_date < now() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                            {{ $invoice->status == 'paid' ? 'Paid' : ($invoice->due_date < now() ? 'Overdue' : 'Pending') }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                        @if($invoice->status == 'unpaid')
                        <a href="#" class="text-green-600 hover:text-green-900">Mark Paid</a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No invoices found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $invoices->links() }}
    </div>
</div>
@endsection
