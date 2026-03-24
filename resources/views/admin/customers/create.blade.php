@extends('layouts.admin')

@section('page-title', 'Add New Client')
@section('page-description', 'Create a new client account')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.customers.store') }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Account Number -->
                <div>
                    <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number (Alphanumeric - 12char) *</label>
                    <input type="text" 
                           id="account_number" 
                           name="account_number" 
                           value="{{ old('account_number') }}" 
                           maxlength="12"
                           required
                           pattern="[A-Za-z0-9]{12}"
                           placeholder="e.g., ACC123456789"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('account_number') border-red-500 @enderror">
                    @error('account_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">12 characters: letters and numbers only</p>
                </div>

                <!-- Server Account Name -->
                <div>
                    <label for="server_account_name" class="block text-sm font-medium text-gray-700 mb-2">Server Account Name</label>
                    <input type="text" 
                           id="server_account_name" 
                           name="server_account_name" 
                           value="{{ old('server_account_name') }}" 
                           placeholder="e.g., user123"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('server_account_name') border-red-500 @enderror">
                    @error('server_account_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="date" 
                           id="start_date" 
                           name="start_date" 
                           value="{{ old('start_date', now()->format('Y-m-d')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Plan -->
                <div class="md:col-span-2">
                    <label for="service_plan_id" class="block text-sm font-medium text-gray-700 mb-2">Plan</label>
                    <select id="service_plan_id" 
                            name="service_plan_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('service_plan_id') border-red-500 @enderror">
                        <option value="">-- Select Plan (Optional) --</option>
                        @foreach($servicePlans as $plan)
                            <option value="{{ $plan->id }}" {{ old('service_plan_id') == $plan->id ? 'selected' : '' }}>
                                {{ $plan->name }} - {{ $plan->speed }}Mbps - ₱{{ number_format((float)$plan->price, 2) }}/month
                            </option>
                        @endforeach
                    </select>
                    @error('service_plan_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Select a plan to create an active subscription for this customer</p>
                </div>
                
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address *</label>
                    <textarea id="address" 
                              name="address" 
                              rows="3" 
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Connection Status -->
                <div>
                    <label for="connection_status" class="block text-sm font-medium text-gray-700 mb-2">Connection Status</label>
                    <select id="connection_status" 
                            name="connection_status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                        <option value="inactive" {{ old('connection_status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="active" {{ old('connection_status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('connection_status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                
                <!-- Balance -->
                <div>
                    <label for="balance" class="block text-sm font-medium text-gray-700 mb-2">Initial Balance</label>
                    <input type="number" 
                           id="balance" 
                           name="balance" 
                           value="{{ old('balance', 0) }}" 
                           step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Leave at 0 or enter a negative value for existing debt</p>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.customers.index') }}" 
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-semibold transition">
                    Create Client
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
