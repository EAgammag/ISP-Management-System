@extends('layouts.admin')

@section('page-title', 'Edit Client')
@section('page-description', 'Update client information')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form action="{{ route('admin.customers.update', $customer) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $customer->name) }}" 
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
                           value="{{ old('email', $customer->email) }}" 
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
                           value="{{ old('phone', $customer->phone) }}" 
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
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent @error('address') border-red-500 @enderror">{{ old('address', $customer->address) }}</textarea>
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
                        <option value="inactive" {{ old('connection_status', $customer->connection_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="active" {{ old('connection_status', $customer->connection_status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="suspended" {{ old('connection_status', $customer->connection_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                
                <!-- Balance -->
                <div>
                    <label for="balance" class="block text-sm font-medium text-gray-700 mb-2">Balance</label>
                    <input type="number" 
                           id="balance" 
                           name="balance" 
                           value="{{ old('balance', $customer->balance ?? 0) }}" 
                           step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-transparent">
                    <p class="mt-1 text-xs text-gray-500">Negative values indicate customer debt</p>
                </div>
            </div>
            
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.customers.show', $customer) }}" 
                   class="text-cyan-600 hover:text-cyan-700 font-semibold">
                    ← Back to Customer Details
                </a>
                <div class="flex gap-4">
                    <a href="{{ route('admin.customers.index') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg font-semibold transition">
                        Update Customer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
