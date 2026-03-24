@extends('layouts.client')

@section('page-title', 'Billing & Payments')
@section('page-description', 'Manage your invoices and payment history')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

<!-- Account Balance -->
<div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg p-6 shadow-lg mb-6">
    <div class="flex justify-between items-center">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-green-400 rounded-full animate-pulse mr-3"></div>
            <div>
                <h2 class="text-white text-xl font-bold">Account Balance</h2>
                <p class="text-blue-100 text-sm">Current balance available</p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-white text-3xl font-bold">₱{{ number_format($balance ?? 0, 2) }}</p>
            <p class="text-blue-100 text-sm">Account Balance</p>
        </div>
    </div>
</div>

<!-- Payment Methods with Tabs -->
<div class="bg-white rounded-lg shadow mb-6">
    <div class="p-6">
        <h2 class="text-gray-800 font-bold text-xl mb-4">Payment Methods</h2>
        
        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Payment Methods">
                <button onclick="switchTab('invoice')" id="tab-invoice" class="payment-tab border-b-2 border-blue-600 text-blue-600 py-4 px-1 text-sm font-medium">
                    View Invoices
                </button>
                <button onclick="switchTab('gcash')" id="tab-gcash" class="payment-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                    GCash QR Code
                </button>
                <button onclick="switchTab('history')" id="tab-history" class="payment-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-4 px-1 text-sm font-medium">
                    Payment History
                </button>
            </nav>
        </div>

        <!-- Tab Content: View Invoices (First/Default) -->
        <div id="content-invoice" class="tab-content">
            <div class="mb-4">
                <div class="flex justify-between items-center">
                    <h3 class="text-gray-800 font-bold text-lg">Your Invoices</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('client.billing.index', ['status' => 'all']) }}" 
                            class="px-4 py-2 rounded hover:bg-blue-200 transition text-sm {{ $statusFilter === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            All
                        </a>
                        <a href="{{ route('client.billing.index', ['status' => 'paid']) }}" 
                            class="px-4 py-2 rounded hover:bg-green-200 transition text-sm {{ $statusFilter === 'paid' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Paid
                        </a>
                        <a href="{{ route('client.billing.index', ['status' => 'unpaid']) }}" 
                            class="px-4 py-2 rounded hover:bg-yellow-200 transition text-sm {{ $statusFilter === 'unpaid' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                            Unpaid
                        </a>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice #</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $invoice->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $invoice->description ?? 'Monthly Subscription' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-lg font-semibold text-gray-900">₱{{ number_format($invoice->amount, 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $invoice->due_date ? $invoice->due_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $invoice->status == 'paid' ? 'bg-green-100 text-green-800' : 
                                       ($invoice->due_date && $invoice->due_date < now() ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $invoice->status == 'paid' ? 'Paid' : ($invoice->due_date && $invoice->due_date < now() ? 'Overdue' : 'Pending') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($invoice->status != 'paid')
                                    <button onclick="switchTab('gcash')" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                        Pay Now
                                    </button>
                                @else
                                    <button class="text-blue-600 hover:text-blue-800">View Receipt</button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No invoices found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="py-4">
                {{ $invoices->links() }}
            </div>
        </div>

        <!-- Tab Content: GCash (Second) -->
        <div id="content-gcash" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- GCash QR Code Section -->
                <div class="text-center">
                    <div class="bg-blue-50 p-6 rounded-lg">
                        <div class="flex items-center justify-center mb-4">
                            <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-bold text-lg mb-2">Scan GCash QR Code</h3>
                        <div class="bg-white p-4 rounded-lg inline-block mb-4 shadow-sm">
                            @if($gcashQrCode)
                                <!-- Use uploaded QR code image -->
                                <img src="{{ asset('storage/' . $gcashQrCode) }}" 
                                     alt="GCash QR Code" 
                                     class="w-48 h-48 object-contain">
                            @else
                                <!-- Generate QR Code from number -->
                                <div id="qrcode" class="flex items-center justify-center"></div>
                            @endif
                        </div>
                        <div class="bg-white p-4 rounded-lg">
                            <p class="text-sm text-gray-600 mb-2">GCash Number:</p>
                            <p class="text-xl font-bold text-gray-900">{{ $gcashNumber }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $gcashAccountName }}</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Instructions -->
                <div>
                    <h3 class="text-gray-800 font-bold text-lg mb-4">Payment Instructions</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">
                                1
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Open GCash App</h4>
                                <p class="text-sm text-gray-600">Launch your GCash mobile application</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">
                                2
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Scan QR Code</h4>
                                <p class="text-sm text-gray-600">Use the QR scanner to scan the code above or send to: {{ $gcashNumber }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">
                                3
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Enter Amount</h4>
                                <p class="text-sm text-gray-600">Input the payment amount based on your invoice</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">
                                4
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Submit Payment</h4>
                                <p class="text-sm text-gray-600">Complete the transaction and save the reference number</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold mr-3">
                                5
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Notify Us</h4>
                                <p class="text-sm text-gray-600">Send us the reference number via support ticket or email</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Payment Form -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold text-gray-800 mb-3">After Payment, Submit Reference:</h4>
                        <form action="{{ route('client.billing.payment.submit') }}" method="POST" class="space-y-3" id="gcash-payment-form">
                            @csrf
                            <div>
                                <input type="text" 
                                       name="reference_number" 
                                       id="reference_number"
                                       placeholder="GCash Reference Number" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('reference_number') border-red-500 @enderror"
                                       required
                                       minlength="10"
                                       maxlength="50"
                                       value="{{ old('reference_number') }}">
                                @error('reference_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <input type="number" 
                                       name="amount" 
                                       id="amount"
                                       placeholder="Amount Paid" 
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('amount') border-red-500 @enderror"
                                       required
                                       min="1"
                                       step="0.01"
                                       value="{{ old('amount') }}">
                                @error('amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                <p class="text-xs text-yellow-800">
                                    <strong>Important:</strong> Please ensure your reference number is correct. Payments will be verified by our team within 24 hours.
                                </p>
                            </div>
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-semibold transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    id="submit-payment-btn">
                                Submit Payment Confirmation
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab Content: Payment History (Third) -->
        <div id="content-history" class="tab-content hidden">
            @if($payments->count() > 0)
            <div class="space-y-3">
                @foreach($payments as $payment)
                <div class="flex justify-between items-center py-4 px-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $payment->payment_method ? ucfirst(str_replace('_', ' ', $payment->payment_method)) : 'Payment' }}</p>
                            <p class="text-sm text-gray-600">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y h:i A') : $payment->created_at->format('M d, Y h:i A') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Ref: {{ $payment->reference_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-green-600">₱{{ number_format($payment->amount, 2) }}</p>
                        <span class="inline-block px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full mt-1">
                            Completed
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">No payment history found.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active styles from all tabs
    document.querySelectorAll('.payment-tab').forEach(tab => {
        tab.classList.remove('border-blue-600', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active styles to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-blue-600', 'text-blue-600');
}

// Form validation and submission handling
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('gcash-payment-form');
    const submitBtn = document.getElementById('submit-payment-btn');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            const refNumber = document.getElementById('reference_number').value.trim();
            const amount = document.getElementById('amount').value;
            
            // Client-side validation
            if (refNumber.length < 10) {
                e.preventDefault();
                alert('Reference number must be at least 10 characters long');
                return false;
            }
            
            if (amount <= 0) {
                e.preventDefault();
                alert('Please enter a valid amount');
                return false;
            }
            
            // Disable button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.textContent = 'Submitting...';
        });
    }

    // Generate QR Code for GCash (only if no uploaded image)
    @if(!$gcashQrCode)
    if (document.getElementById('qrcode')) {
        // Using QRCode.js library via CDN
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js';
        script.onload = function() {
            // GCash payment format - using standard payment QR format
            const gcashNumber = '{{ $gcashNumber }}';
            const receiverName = '{{ $gcashAccountName }}';
            
            // Format: GCash payment string (can be scanned by GCash app)
            const qrData = gcashNumber;
            
            new QRCode(document.getElementById('qrcode'), {
                text: qrData,
                width: 200,
                height: 200,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
            
            // Add instruction below QR code
            const instruction = document.createElement('p');
            instruction.className = 'text-xs text-gray-600 mt-2';
            instruction.innerHTML = 'Scan with <strong>GCash App</strong> or send to:<br><strong class="text-blue-600">' + gcashNumber + '</strong>';
            document.getElementById('qrcode').parentElement.appendChild(instruction);
        };
        document.head.appendChild(script);
    }
    @endif
});
</script>

@endsection
