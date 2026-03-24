@extends('layouts.admin')

@section('content')
<div class="px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-4xl font-bold text-gray-900 mb-2">Billing Schedules</h2>
        <p class="text-lg text-gray-800">
            Track and manage payment schedules for active clients
        </p>
        <p class="text-sm text-gray-600 mt-1">
            Showing invoices from <span class="font-semibold">{{ \Carbon\Carbon::parse(request('start_date', now()->subMonths(3)->startOfMonth()))->format('M d, Y') }}</span> to <span class="font-semibold">{{ \Carbon\Carbon::parse(request('end_date', now()->addMonths(3)->endOfMonth()))->format('M d, Y') }}</span>
        </p>
    </div>

    <!-- Monitoring Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Paid Clients -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-400 text-sm font-medium mb-2">Total Number of Paid Clients</p>
                    <p class="text-4xl font-bold text-green-400">{{ $statistics['paid_clients'] }}</p>
                    <p class="text-gray-500 text-xs mt-2">Clients with paid invoices</p>
                </div>
                <div class="bg-green-500 bg-opacity-20 rounded-full p-4">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Unpaid Clients -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-400 text-sm font-medium mb-2">Total Number of Unpaid Clients</p>
                    <p class="text-4xl font-bold text-yellow-400">{{ $statistics['unpaid_clients'] }}</p>
                    <p class="text-gray-500 text-xs mt-2">Clients with pending payments</p>
                </div>
                <div class="bg-yellow-500 bg-opacity-20 rounded-full p-4">
                    <svg class="w-10 h-10 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Overdue Clients -->
        <div class="bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-400 text-sm font-medium mb-2">Total Number of Over Due Clients</p>
                    <p class="text-4xl font-bold text-red-400">{{ $statistics['overdue_clients'] }}</p>
                    <p class="text-gray-500 text-xs mt-2">Clients with overdue invoices</p>
                </div>
                <div class="bg-red-500 bg-opacity-20 rounded-full p-4">
                    <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-gray-800 rounded-lg shadow-lg p-6 mb-6 border border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-white">Filters & Export</h3>
            <div class="flex gap-2">
                <!-- Export Summary Button -->
                <button onclick="openExportModal()" 
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg font-medium transition duration-200 flex items-center gap-2"
                    title="Export summary to CSV">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
                <!-- Export Calendar Button -->
                <a href="{{ route('admin.billing-schedules.export-calendar', request()->all()) }}" 
                    class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg font-medium transition duration-200 flex items-center gap-2"
                    title="Export due dates to calendar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Export Calendar
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.billing-schedules.index') }}" class="flex flex-wrap items-end gap-4">
            <!-- Date Range -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-300 mb-2">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date', now()->subMonths(3)->startOfMonth()->format('Y-m-d')) }}" 
                    class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500">
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-300 mb-2">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date', now()->addMonths(3)->endOfMonth()->format('Y-m-d')) }}" 
                    class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500">
            </div>

            <!-- Status Filter -->
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-300 mb-2">Status</label>
                <select name="status" class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500">
                    <option value="all" {{ request('status', 'all') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2">
                <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    Apply Filters
                </button>
                <a href="{{ route('admin.billing-schedules.index') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition duration-200">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Invoices Table -->
    <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700 bg-gray-800">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-700 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-cyan-400">#{{ $invoice->invoice_number }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-white font-medium">{{ $invoice->customer->name }}</div>
                            <div class="text-xs text-gray-400">{{ $invoice->customer->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-white">₱{{ number_format($invoice->amount, 2) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-300">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</div>
                            @if($invoice->isOverdue())
                                <div class="text-xs text-red-400 mt-1">{{ \Carbon\Carbon::parse($invoice->due_date)->diffForHumans() }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($invoice->status === 'paid')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-500 bg-opacity-20 text-green-400">
                                    Paid
                                </span>
                            @elseif($invoice->isOverdue())
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-500 bg-opacity-20 text-red-400">
                                    Overdue
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-500 bg-opacity-20 text-yellow-400">
                                    Unpaid
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2 items-center">
                                <!-- View Button -->
                                <a href="{{ route('admin.billing-schedules.show', $invoice->id) }}" 
                                    class="px-3 py-1.5 bg-cyan-500 hover:bg-cyan-600 text-white text-xs font-semibold rounded transition duration-150 flex items-center gap-1"
                                    title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View
                                </a>

                                <!-- Mark Paid/Unpaid Button -->
                                @if($invoice->status !== 'paid')
                                    <button onclick="markAsPaid({{ $invoice->id }})" 
                                        class="px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded transition duration-150 flex items-center gap-1" 
                                        title="Mark as Paid">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Mark Paid
                                    </button>
                                @endif
                                
                                <!-- Icon Actions (smaller buttons) -->
                                @if($invoice->status === 'paid')
                                    <button onclick="markAsUnpaid({{ $invoice->id }})" 
                                        class="text-yellow-400 hover:text-yellow-300 transition duration-150" 
                                        title="Mark as Unpaid">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                @endif
                                
                                <button onclick="openDueDateModal({{ $invoice->id }}, '{{ $invoice->due_date }}')" 
                                    class="text-cyan-400 hover:text-cyan-300 transition duration-150" 
                                    title="Update Due Date">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                                
                                <button onclick="sendInvoiceEmail({{ $invoice->id }}, '{{ $invoice->customer->name }}')"
                                    class="text-blue-400 hover:text-blue-300 transition duration-150"
                                    title="Send Email Notification">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                                
                                <a href="{{ route('admin.billing-schedules.download-pdf', $invoice->id) }}"
                                    class="text-red-400 hover:text-red-300 transition duration-150"
                                    title="Download PDF"
                                    target="_blank">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </a>

                                @if($invoice->status === 'paid')
                                    @php
                                        $paidPayment = $invoice->payments()->where('status', 'paid')->first();
                                    @endphp
                                    @if($paidPayment)
                                    <a href="{{ route('admin.payments.receipt', $paidPayment->id) }}"
                                        class="text-purple-400 hover:text-purple-300 transition duration-150"
                                        title="Download Receipt"
                                        target="_blank">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-gray-400 text-lg font-medium">No invoices found</p>
                                <p class="text-gray-500 text-sm mt-2">Try adjusting your filters</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($invoices->hasPages())
        <div class="bg-gray-900 px-6 py-4 border-t border-gray-700">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Export Options Modal -->
<div id="exportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-2xl border border-gray-700">
        <h3 class="text-xl font-semibold text-white mb-4">Export Options - CSV Summary</h3>
        
        <!-- Current Filters Display -->
        <div class="bg-gray-900 rounded-lg p-4 mb-4">
            <h4 class="text-sm font-semibold text-gray-400 mb-2">Current Filters:</h4>
            <div class="grid grid-cols-2 gap-2 text-sm text-gray-300">
                <div><span class="text-gray-500">Date Range:</span> {{ request('start_date', now()->subMonths(3)->startOfMonth()->format('M d, Y')) }} to {{ request('end_date', now()->addMonths(3)->endOfMonth()->format('M d, Y')) }}</div>
                <div><span class="text-gray-500">Status:</span> {{ ucfirst(request('status', 'all')) }}</div>
                <div><span class="text-gray-500">Total Invoices:</span> {{ $invoices->total() }}</div>
                <div><span class="text-gray-500">Filter:</span> Active Clients Only</div>
            </div>
        </div>

        <form id="exportForm" method="GET" action="{{ route('admin.billing-schedules.export-summary') }}">
            <!-- Include current filters -->
            <input type="hidden" name="start_date" value="{{ request('start_date', now()->subMonths(3)->startOfMonth()->format('Y-m-d')) }}">
            <input type="hidden" name="end_date" value="{{ request('end_date', now()->addMonths(3)->endOfMonth()->format('Y-m-d')) }}">
            <input type="hidden" name="status" value="{{ request('status', 'all') }}">
            
            <!-- Export Options -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-300 mb-3">Include in Summary:</label>
                <div class="space-y-2">
                    <label class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded cursor-pointer">
                        <input type="checkbox" name="include_statistics" value="1" checked class="mr-3 w-4 h-4 text-cyan-500 bg-gray-900 border-gray-600 rounded focus:ring-cyan-500">
                        <span class="text-sm">Summary Statistics (Paid, Unpaid, Overdue totals)</span>
                    </label>
                    <label class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded cursor-pointer">
                        <input type="checkbox" name="include_details" value="1" checked class="mr-3 w-4 h-4 text-cyan-500 bg-gray-900 border-gray-600 rounded focus:ring-cyan-500">
                        <span class="text-sm">Detailed Invoice List</span>
                    </label>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-300 mb-3">Filter by Status (Override current filter):</label>
                <div class="space-y-2">
                    <label class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded cursor-pointer">
                        <input type="checkbox" name="export_paid" value="1" {{ request('status') == 'all' || request('status') == 'paid' ? 'checked' : '' }} class="mr-3 w-4 h-4 text-green-500 bg-gray-900 border-gray-600 rounded focus:ring-green-500">
                        <span class="text-sm">Include Paid Invoices</span>
                    </label>
                    <label class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded cursor-pointer">
                        <input type="checkbox" name="export_unpaid" value="1" {{ request('status') == 'all' || request('status') == 'unpaid' ? 'checked' : '' }} class="mr-3 w-4 h-4 text-yellow-500 bg-gray-900 border-gray-600 rounded focus:ring-yellow-500">
                        <span class="text-sm">Include Unpaid Invoices</span>
                    </label>
                    <label class="flex items-center text-gray-300 hover:bg-gray-700 p-2 rounded cursor-pointer">
                        <input type="checkbox" name="export_overdue" value="1" {{ request('status') == 'all' || request('status') == 'overdue' ? 'checked' : '' }} class="mr-3 w-4 h-4 text-red-500 bg-gray-900 border-gray-600 rounded focus:ring-red-500">
                        <span class="text-sm">Include Overdue Invoices</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeExportModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition duration-200">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-lg transition duration-200 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export Now
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Due Date Modal -->
<div id="dueDateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-md border border-gray-700">
        <h3 class="text-xl font-semibold text-white mb-4">Update Due Date</h3>
        <form id="dueDateForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-300 mb-2">New Due Date</label>
                <input type="date" id="newDueDate" name="due_date" required
                    class="w-full bg-gray-900 border border-gray-700 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDueDateModal()" 
                    class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition duration-200">
                    Cancel
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition duration-200">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Get CSRF token from meta tag
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    // Open export modal
    function openExportModal() {
        const modal = document.getElementById('exportModal');
        modal.classList.remove('hidden');
    }

    // Close export modal
    function closeExportModal() {
        const modal = document.getElementById('exportModal');
        modal.classList.add('hidden');
    }

    // Mark invoice as paid
    function markAsPaid(invoiceId) {
        if (confirm('Mark this invoice as paid?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/billing-schedules/${invoiceId}/status`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = getCsrfToken();
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'paid';
            
            form.appendChild(csrfToken);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Mark invoice as unpaid
    function markAsUnpaid(invoiceId) {
        if (confirm('Mark this invoice as unpaid?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/billing-schedules/${invoiceId}/status`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = getCsrfToken();
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = 'unpaid';
            
            form.appendChild(csrfToken);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Send invoice email notification
    function sendInvoiceEmail(invoiceId, customerName) {
        if (confirm(`Send invoice notification email to ${customerName}?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/billing-schedules/${invoiceId}/send-email`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = getCsrfToken();
            
            form.appendChild(csrfToken);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Open due date modal
    function openDueDateModal(invoiceId, currentDueDate) {
        const modal = document.getElementById('dueDateModal');
        const form = document.getElementById('dueDateForm');
        const dateInput = document.getElementById('newDueDate');
        
        form.action = `/admin/billing-schedules/${invoiceId}/due-date`;
        dateInput.value = currentDueDate;
        
        modal.classList.remove('hidden');
    }

    // Close due date modal
    function closeDueDateModal() {
        const modal = document.getElementById('dueDateModal');
        modal.classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('dueDateModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDueDateModal();
        }
    });

    // Close export modal when clicking outside
    document.getElementById('exportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeExportModal();
        }
    });
</script>
@endsection
