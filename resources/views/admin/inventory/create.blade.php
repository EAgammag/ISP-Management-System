@extends('layouts.admin')

@section('page-title', 'Add Inventory Item')
@section('page-description', 'Add new equipment to inventory')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">New Inventory Item</h3>
        </div>
        
        <form action="{{ route('admin.inventory.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            
            <!-- Item Type -->
            <div>
                <label for="item_type" class="block text-sm font-medium text-gray-700 mb-2">Item Type</label>
                <input type="text" id="item_type" name="item_type" value="{{ old('item_type') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., Fiber Optic Router">
                @error('item_type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Model -->
            <div>
                <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                <input type="text" id="model" name="model" value="{{ old('model') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., TP-Link AX3000">
                @error('model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Serial Number -->
            <div>
                <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Serial Number</label>
                <input type="text" id="serial_number" name="serial_number" value="{{ old('serial_number') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., SN123456789">
                @error('serial_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="category" name="category" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Category</option>
                    <option value="router" {{ old('category') == 'router' ? 'selected' : '' }}>Router</option>
                    <option value="ont" {{ old('category') == 'ont' ? 'selected' : '' }}>ONT</option>
                    <option value="cable" {{ old('category') == 'cable' ? 'selected' : '' }}>Cable</option>
                    <option value="switch" {{ old('category') == 'switch' ? 'selected' : '' }}>Switch</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('category')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Quantity -->
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('quantity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Status</option>
                    <option value="in_stock" {{ old('status') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                    <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                    <option value="faulty" {{ old('status') == 'faulty' ? 'selected' : '' }}>Faulty</option>
                    <option value="retired" {{ old('status') == 'retired' ? 'selected' : '' }}>Retired</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Location -->
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location (Optional)</label>
                <input type="text" id="location" name="location" value="{{ old('location') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., Warehouse A, Shelf 3">
                @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea id="notes" name="notes" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Additional information about this item">{{ old('notes') }}</textarea>
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
                    Add Item
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
