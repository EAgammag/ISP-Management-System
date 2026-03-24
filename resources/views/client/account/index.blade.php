@extends('layouts.client')

@section('page-title', 'Account Settings')
@section('page-description', 'Manage your account information and preferences')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Profile Information</h2>
            <p class="text-gray-600 mt-1">Update your account details and contact information</p>
        </div>

        <form action="{{ route('client.account.update') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        First Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name', Auth::user()->customer->first_name ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('first_name') border-red-500 @enderror" 
                           required>
                    @error('first_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Last Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name', Auth::user()->customer->last_name ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('last_name') border-red-500 @enderror" 
                           required>
                    @error('last_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', Auth::user()->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-500 @enderror" 
                           required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', Auth::user()->customer->phone ?? '') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('phone') border-red-500 @enderror" 
                           required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Address <span class="text-red-500">*</span>
                </label>
                <textarea id="address" 
                          name="address" 
                          rows="3" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror" 
                          required>{{ old('address', Auth::user()->customer->address ?? '') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Change Password</h2>
            <p class="text-gray-600 mt-1">Update your password to keep your account secure</p>
        </div>

        <form action="{{ route('client.account.password') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Password -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Current Password <span class="text-red-500">*</span>
                </label>
                <input type="password" 
                       id="current_password" 
                       name="current_password" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('current_password') border-red-500 @enderror" 
                       required>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror" 
                           required>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Update Password
                </button>
            </div>
        </form>
    </div>

    <!-- Account Details -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Account Details</h2>
        </div>

        <div class="p-6 space-y-4">
            <div class="flex justify-between py-3 border-b border-gray-100">
                <span class="text-gray-600">Customer ID</span>
                <span class="font-semibold text-gray-800">#CUST-{{ str_pad(Auth::user()->customer->id ?? 0, 6, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="flex justify-between py-3 border-b border-gray-100">
                <span class="text-gray-600">Account Status</span>
                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    {{ ucfirst(Auth::user()->customer->status ?? 'active') }}
                </span>
            </div>

            <div class="flex justify-between py-3 border-b border-gray-100">
                <span class="text-gray-600">Member Since</span>
                <span class="font-semibold text-gray-800">{{ Auth::user()->created_at->format('F d, Y') }}</span>
            </div>

            <div class="flex justify-between py-3 border-b border-gray-100">
                <span class="text-gray-600">Current Plan</span>
                <span class="font-semibold text-gray-800">
                    @if(Auth::user()->customer && Auth::user()->customer->activeSubscription())
                        {{ Auth::user()->customer->activeSubscription()->servicePlan->name ?? 'N/A' }}
                    @else
                        No active plan
                    @endif
                </span>
            </div>

            <div class="flex justify-between py-3">
                <span class="text-gray-600">Installation Address</span>
                <span class="font-semibold text-gray-800 text-right max-w-md">
                    {{ Auth::user()->customer->installation_address ?? Auth::user()->customer->address ?? 'N/A' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Notification Preferences -->
    <div class="bg-white rounded-lg shadow mt-6">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-800">Notification Preferences</h2>
            <p class="text-gray-600 mt-1">Choose how you want to be notified</p>
        </div>

        <form action="{{ route('client.account.notifications') }}" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <!-- Contact Information Display -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <p class="text-sm text-blue-800 font-medium mb-2">📧 Notifications will be sent to:</p>
                <div class="space-y-1">
                    <p class="text-sm text-blue-700">
                        <span class="font-semibold">Email:</span> {{ $customer->email }}
                    </p>
                    @if($customer->phone)
                        <p class="text-sm text-blue-700">
                            <span class="font-semibold">Phone:</span> {{ $customer->phone }}
                        </p>
                    @else
                        <p class="text-sm text-orange-600">
                            <span class="font-semibold">Phone:</span> Not set - Please update your phone number to receive SMS notifications
                        </p>
                    @endif
                </div>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-medium text-gray-800">Email Notifications</p>
                    <p class="text-sm text-gray-600">Receive email updates about your account</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="email_notifications" class="sr-only peer" {{ $customer->email_notifications ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-medium text-gray-800">SMS Notifications</p>
                    <p class="text-sm text-gray-600">Receive SMS alerts for important updates</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="sms_notifications" class="sr-only peer" {{ $customer->sms_notifications ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between py-3 border-b border-gray-100">
                <div>
                    <p class="font-medium text-gray-800">Billing Reminders</p>
                    <p class="text-sm text-gray-600">Get notified before payment due dates</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="billing_reminders" class="sr-only peer" {{ $customer->billing_reminders ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <div class="flex items-center justify-between py-3">
                <div>
                    <p class="font-medium text-gray-800">Promotional Offers</p>
                    <p class="text-sm text-gray-600">Receive special offers and promotions</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="promotional_offers" class="sr-only peer" {{ $customer->promotional_offers ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end pt-4">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    Save Preferences
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
