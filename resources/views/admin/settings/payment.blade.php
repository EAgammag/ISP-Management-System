@extends('layouts.admin')

@section('page-title', 'Payment Settings')
@section('page-description', 'Configure GCash payment information')

@section('content')
<!-- Success Message -->
@if(session('success'))
<div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
        </svg>
        <span>{{ session('success') }}</span>
    </div>
</div>
@endif

<!-- Error Message -->
@if(session('error'))
<div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
        <span>{{ session('error') }}</span>
    </div>
</div>
@endif

<div class="bg-white rounded-lg shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-gray-800">GCash Payment Configuration</h2>
        <p class="text-gray-600 mt-1">Update GCash account information displayed to clients</p>
    </div>

    <form action="{{ route('admin.settings.payment.update') }}" method="POST" enctype="multipart/form-data" class="p-6">
        @csrf
        
        <div class="space-y-6">
            <!-- GCash Number -->
            <div>
                <label for="gcash_number" class="block text-sm font-medium text-gray-700 mb-2">
                    GCash Number <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="gcash_number" 
                       id="gcash_number"
                       value="{{ old('gcash_number', $settings['gcash_number']->value ?? '09618377290') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gcash_number') border-red-500 @enderror"
                       placeholder="09XXXXXXXXX"
                       required>
                @error('gcash_number')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Enter the GCash mobile number for receiving payments</p>
            </div>

            <!-- Account Name -->
            <div>
                <label for="gcash_account_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Account Holder Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="gcash_account_name" 
                       id="gcash_account_name"
                       value="{{ old('gcash_account_name', $settings['gcash_account_name']->value ?? 'Eddie Jr G.') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gcash_account_name') border-red-500 @enderror"
                       placeholder="John Doe"
                       required>
                @error('gcash_account_name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Name registered to the GCash account</p>
            </div>

            <!-- QR Code Upload (Optional) -->
            <div>
                <label for="gcash_qr_code" class="block text-sm font-medium text-gray-700 mb-2">
                    GCash QR Code Image (Optional)
                </label>
                
                @if(isset($settings['gcash_qr_code']) && $settings['gcash_qr_code']->value)
                <div class="mb-3">
                    <p class="text-sm text-gray-600 mb-2">Current QR Code:</p>
                    <img src="{{ asset('storage/' . $settings['gcash_qr_code']->value) }}" 
                         alt="Current QR Code" 
                         class="w-48 h-48 border border-gray-300 rounded-lg">
                </div>
                @endif
                
                <input type="file" 
                       name="gcash_qr_code" 
                       id="gcash_qr_code"
                       accept="image/*"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('gcash_qr_code') border-red-500 @enderror">
                @error('gcash_qr_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-1">Upload your actual GCash QR code image. If not provided, a QR code will be generated from the mobile number.</p>
            </div>

            <!-- Preview Section -->
            <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                <h3 class="font-semibold text-gray-800 mb-4">Client View Preview</h3>
                <div class="bg-white p-4 rounded-lg">
                    <p class="text-sm text-gray-600 mb-2">GCash Number:</p>
                    <p class="text-xl font-bold text-gray-900" id="preview_number">{{ $settings['gcash_number']->value ?? '09618377290' }}</p>
                    <p class="text-sm text-gray-600 mt-1" id="preview_name">{{ $settings['gcash_account_name']->value ?? 'Eddie Jr G.' }}</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.dashboard') }}" 
                   class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Live preview update
document.getElementById('gcash_number').addEventListener('input', function(e) {
    document.getElementById('preview_number').textContent = e.target.value;
});

document.getElementById('gcash_account_name').addEventListener('input', function(e) {
    document.getElementById('preview_name').textContent = e.target.value;
});
</script>

@endsection
