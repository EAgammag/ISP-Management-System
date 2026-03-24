@extends('layouts.client')

@section('page-title', 'Create Support Ticket')
@section('page-description', 'Submit a new support request')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('client.tickets.index') }}" class="text-blue-600 hover:text-blue-800 inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Tickets
        </a>
    </div>

    <!-- Support Categories -->
    <div class="bg-blue-50 border-l-4 border-blue-600 p-6 mb-6 rounded">
        <h3 class="text-blue-800 font-bold mb-3">Common Issues</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-blue-900 font-medium">Connection Issues</p>
                    <p class="text-blue-700 text-sm">Slow speed, no internet</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-blue-900 font-medium">Billing Questions</p>
                    <p class="text-blue-700 text-sm">Invoices, payments</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-blue-900 font-medium">Service Upgrade</p>
                    <p class="text-blue-700 text-sm">Plan changes, add-ons</p>
                </div>
            </div>
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <p class="text-blue-900 font-medium">Technical Support</p>
                    <p class="text-blue-700 text-sm">Equipment, configuration</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ticket Form -->
    <div class="bg-white rounded-lg shadow-lg">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Submit Support Request</h2>
            <p class="text-gray-600 mt-1">Fill out the form below and we'll get back to you as soon as possible.</p>
        </div>

        <form action="{{ route('client.tickets.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Subject -->
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="subject" 
                       name="subject" 
                       value="{{ old('subject') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('subject') border-red-500 @enderror" 
                       placeholder="Brief description of your issue"
                       required>
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Priority -->
            <div>
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">
                    Priority <span class="text-red-500">*</span>
                </label>
                <select id="priority" 
                        name="priority" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('priority') border-red-500 @enderror"
                        required>
                    <option value="">Select priority level</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low - General inquiry</option>
                    <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }} selected>Normal - Standard issue</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High - Service degraded</option>
                    <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>Urgent - Service down</option>
                </select>
                @error('priority')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Category -->
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                    Category
                </label>
                <select id="category" 
                        name="category" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select category</option>
                    <option value="technical" {{ old('category') == 'technical' ? 'selected' : '' }}>Technical Support</option>
                    <option value="billing" {{ old('category') == 'billing' ? 'selected' : '' }}>Billing & Payments</option>
                    <option value="service" {{ old('category') == 'service' ? 'selected' : '' }}>Service Request</option>
                    <option value="complaint" {{ old('category') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                    <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Message <span class="text-red-500">*</span>
                </label>
                <textarea id="message" 
                          name="message" 
                          rows="8" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('message') border-red-500 @enderror" 
                          placeholder="Please provide detailed information about your issue..."
                          required>{{ old('message') }}</textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Include as many details as possible to help us resolve your issue quickly.
                </p>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium text-gray-800 mb-3">Contact Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number
                        </label>
                        <input type="tel" 
                               id="contact_phone" 
                               name="contact_phone" 
                               value="{{ old('contact_phone', Auth::user()->customer->phone ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="e.g., 09171234567">
                    </div>
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" 
                               id="contact_email" 
                               name="contact_email" 
                               value="{{ old('contact_email', Auth::user()->email) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                               placeholder="your.email@example.com">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 pt-4">
                <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Submit Ticket
                </button>
                <a href="{{ route('client.tickets.index') }}" 
                   class="px-6 py-3 border-2 border-gray-300 text-gray-700 rounded-lg font-semibold hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <!-- Response Time Info -->
    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Expected Response Times
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between items-center">
                <span class="text-gray-700">🔴 <strong>Urgent:</strong> Service down</span>
                <span class="text-green-600 font-semibold">Within 1 hour</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">🟠 <strong>High:</strong> Service degraded</span>
                <span class="text-green-600 font-semibold">Within 4 hours</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">🟡 <strong>Normal:</strong> Standard issue</span>
                <span class="text-green-600 font-semibold">Within 24 hours</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-gray-700">🔵 <strong>Low:</strong> General inquiry</span>
                <span class="text-green-600 font-semibold">Within 48 hours</span>
            </div>
        </div>
    </div>
</div>
@endsection
