@extends('layouts.admin')

@section('page-title', 'Assign Inventory Item')
@section('page-description', 'Assign equipment to customer')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Item Info -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Item Details</h3>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Item Type</p>
                <p class="font-semibold text-gray-900">{{ $item->item_type }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Model</p>
                <p class="font-semibold text-gray-900">{{ $item->model }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Serial Number</p>
                <p class="font-semibold text-gray-900">{{ $item->serial_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Available Quantity</p>
                <p class="font-semibold text-gray-900">{{ $item->quantity }}</p>
            </div>
        </div>
    </div>

    <!-- Assignment Form -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Assign to Customer</h3>
        </div>
        
        <form action="{{ route('admin.inventory.process-assignment', $item) }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Customer Selection -->
            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-2">Select Customer</label>
                <select id="customer_id" name="customer_id" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Choose a customer...</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->name }} - {{ $customer->email }}
                    </option>
                    @endforeach
                </select>
                @error('customer_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity to Assign</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" 
                    required min="1" max="{{ $item->quantity }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Installation notes, location, etc.">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.inventory.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Assign Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
