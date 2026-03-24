@extends('layouts.admin')

@section('page-title', 'Edit Bandwidth Policy')
@section('page-description', 'Update bandwidth policy settings')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Edit Bandwidth Policy</h3>
        </div>
        
        <form action="{{ route('admin.bandwidth.update', $policy) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Policy Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Policy Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $policy->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="e.g., Standard Plan 50Mbps">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Download Speed -->
            <div>
                <label for="download_speed" class="block text-sm font-medium text-gray-700 mb-2">Download Speed (Mbps)</label>
                <input type="number" id="download_speed" name="download_speed" value="{{ old('download_speed', $policy->download_speed) }}" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="50">
                @error('download_speed')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Upload Speed -->
            <div>
                <label for="upload_speed" class="block text-sm font-medium text-gray-700 mb-2">Upload Speed (Mbps)</label>
                <input type="number" id="upload_speed" name="upload_speed" value="{{ old('upload_speed', $policy->upload_speed) }}" required min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="25">
                @error('upload_speed')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Data Cap -->
            <div>
                <label for="data_cap" class="block text-sm font-medium text-gray-700 mb-2">Data Cap (GB) - Optional</label>
                <input type="number" id="data_cap" name="data_cap" value="{{ old('data_cap', $policy->data_cap) }}" min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Leave empty for unlimited">
                @error('data_cap')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                <select id="priority" name="priority" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Priority</option>
                    <option value="high" {{ old('priority', $policy->priority) == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ old('priority', $policy->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ old('priority', $policy->priority) == 'low' ? 'selected' : '' }}>Low</option>
                </select>
                @error('priority')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="status" name="status" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Status</option>
                    <option value="active" {{ old('status', $policy->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $policy->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-4 pt-4 border-t border-gray-200">
                <a href="{{ route('admin.bandwidth.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                    Update Policy
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
