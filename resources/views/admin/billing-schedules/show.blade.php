@extends('layouts.admin')

@section('title', 'Invoice Details - ' . $invoice->invoice_number)

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Invoice Details</h1>
            <p class="text-gray-900">Invoice #{{ $invoice->invoice_number }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.billing-schedules.index') }}" 
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg transition duration-200 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to List
            </a>
            <a href="{{ route('admin.billing-schedules.download-pdf', $invoice->id) }}" 
                class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition duration-200 flex items-center gap-2"
                target="_blank">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Invoice Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Invoice Status Card -->
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-white mb-2">Invoice Information</h2>
                        <p class="text-sm text-gray-400">Created {{ $invoice->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        @if($invoice->status === 'paid')
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg bg-green-500 bg-opacity-20 text-green-400">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Paid
                            </span>
                        @elseif($invoice->isOverdue())
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg bg-red-500 bg-opacity-20 text-red-400">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                Overdue
                            </span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-lg bg-yellow-500 bg-opacity-20 text-yellow-400">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                </svg>
                                Unpaid
                            </span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Invoice Number</p>
                        <p class="text-lg font-semibold text-white">#{{ $invoice->invoice_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Amount</p>
                        <p class="text-2xl font-bold text-white">₱{{ number_format($invoice->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Due Date</p>
                        <p class="text-lg font-semibold text-white">{{ \Carbon\Carbon::parse($invoice->due_date)->format('M d, Y') }}</p>
                        @if($invoice->isOverdue() && $invoice->status !== 'paid')
                            <p class="text-xs text-red-400 mt-1">{{ \Carbon\Carbon::parse($invoice->due_date)->diffForHumans() }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-400 mb-1">Service Period</p>
                        <p class="text-lg font-semibold text-white">{{ $invoice->billing_period ?? 'N/A' }}</p>
                    </div>
                </div>

                @if($invoice->subscription && $invoice->subscription->servicePlan)
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <p class="text-sm text-gray-400 mb-2">Service Plan</p>
                    <p class="text-lg font-semibold text-white">{{ $invoice->subscription->servicePlan->name }}</p>
                    <p class="text-sm text-gray-400 mt-1">{{ $invoice->subscription->servicePlan->speed }} • {{ $invoice->subscription->servicePlan->data_limit }}</p>
                </div>
                @endif
            </div>

            <!-- Payment History -->
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Payment History</h2>
                
                @if($invoice->payments->count() > 0)
                    <div class="space-y-3">
                        @foreach($invoice->payments as $payment)
                        <div class="flex justify-between items-center p-4 bg-gray-900 rounded-lg border border-gray-700">
                            <div class="flex items-center gap-4">
                                <div class="p-2 bg-{{ $payment->status === 'paid' ? 'green' : 'gray' }}-500 bg-opacity-20 rounded-lg">
                                    <svg class="w-6 h-6 text-{{ $payment->status === 'paid' ? 'green' : 'gray' }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-white">₱{{ number_format($payment->amount, 2) }}</p>
                                    <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y h:i A') }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ ucfirst($payment->payment_method) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full bg-{{ $payment->status === 'paid' ? 'green' : 'yellow' }}-500 bg-opacity-20 text-{{ $payment->status === 'paid' ? 'green' : 'yellow' }}-400">
                                    {{ ucfirst($payment->status) }}
                                </span>
                                @if($payment->status === 'paid')
                                <a href="{{ route('admin.payments.receipt', $payment->id) }}" 
                                    class="ml-2 text-purple-400 hover:text-purple-300 text-xs underline"
                                    target="_blank">
                                    Download Receipt
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-400">No payments recorded yet</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Customer Information & Actions -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Customer Information</h2>
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-cyan-500 bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Customer Name</p>
                            <p class="font-semibold text-white">{{ $invoice->customer->name }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-blue-500 bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Email</p>
                            <p class="font-semibold text-white text-sm">{{ $invoice->customer->email }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-500 bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Phone</p>
                            <p class="font-semibold text-white">{{ $invoice->customer->phone ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-500 bg-opacity-20 rounded-lg">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-400">Account Number</p>
                            <p class="font-semibold text-white">{{ $invoice->customer->account_number }}</p>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-gray-700">
                        <a href="{{ route('admin.customers.show', $invoice->customer->id) }}" 
                            class="block w-full px-4 py-2 bg-cyan-500 hover:bg-cyan-600 text-white text-center rounded-lg transition duration-200">
                            View Customer Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-800 rounded-lg shadow-lg border border-gray-700 p-6">
                <h2 class="text-xl font-semibold text-white mb-4">Quick Actions</h2>
                <div class="space-y-3">
                    @if($invoice->status !== 'paid')
                    <button onclick="markAsPaid({{ $invoice->id }})" 
                        class="w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-lg transition duration-200 flex items-center justify-center gap-2 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mark as Paid
                    </button>
                    @else
                    <button onclick="markAsUnpaid({{ $invoice->id }})" 
                        class="w-full px-4 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition duration-200 flex items-center justify-center gap-2 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mark as Unpaid
                    </button>
                    @endif

                    <button onclick="openDueDateModal({{ $invoice->id }}, '{{ $invoice->due_date }}')" 
                        class="w-full px-4 py-3 bg-cyan-500 hover:bg-cyan-600 text-white rounded-lg transition duration-200 flex items-center justify-center gap-2 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        Update Due Date
                    </button>

                    <button onclick="sendInvoiceEmail({{ $invoice->id }}, '{{ $invoice->customer->name }}')" 
                        class="w-full px-4 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition duration-200 flex items-center justify-center gap-2 font-semibold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Send Email Notification
                    </button>
                </div>
            </div>
        </div>
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

    // Mark invoice as paid
    function markAsPaid(invoiceId) {
        if (confirm('Mark this invoice as paid? This will create a payment record.')) {
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
</script>
@endsection
