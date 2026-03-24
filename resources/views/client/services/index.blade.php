@extends('layouts.client')

@section('page-title', 'My Services')
@section('page-description', 'Manage your subscription plans and add-ons')

@section('content')
<!-- Success/Error Messages -->
@if(session('success'))
<div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

<!-- Current Plan -->
<div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg p-6 shadow-lg mb-6">
    <h2 class="text-white text-2xl font-bold mb-4">Current Plan</h2>
    @if($currentPlan)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <p class="text-blue-100 text-sm">Plan Name</p>
            <p class="text-white text-xl font-bold">{{ $currentPlan->name }}</p>
        </div>
        <div>
            <p class="text-blue-100 text-sm">Speed</p>
            <p class="text-white text-xl font-bold">{{ $currentPlan->speed }} Mbps</p>
        </div>
        <div>
            <p class="text-blue-100 text-sm">Monthly Fee</p>
            <p class="text-white text-xl font-bold">₱{{ number_format($currentPlan->price, 2) }}</p>
        </div>
    </div>
    @else
    <p class="text-white">No active plan. Please select a plan below.</p>
    @endif
</div>

<!-- Available Plans -->
<div class="mb-6">
    <h2 class="text-gray-800 font-bold text-2xl mb-4">Available Plans</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($availablePlans as $plan)
        <div class="bg-white border-2 rounded-lg p-6 hover:border-blue-500 transition {{ $currentPlan && $currentPlan->id == $plan->id ? 'ring-2 ring-blue-500 border-blue-500' : 'border-gray-200' }}">
            @if($currentPlan && $currentPlan->id == $plan->id)
                <span class="bg-blue-500 text-white text-xs px-3 py-1 rounded-full font-semibold mb-2 inline-block">Current Plan</span>
            @endif
            <h3 class="text-gray-900 text-2xl font-bold mb-2">{{ $plan->name }}</h3>
            <p class="text-blue-600 text-4xl font-bold mb-4">{{ $plan->speed }} <span class="text-lg">Mbps</span></p>
            <p class="text-gray-600 mb-4">{{ $plan->description }}</p>
            <div class="space-y-2 mb-6">
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ $plan->data_limit ?? 'Unlimited' }} GB Data</span>
                </div>
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <span>24/7 Support</span>
                </div>
            </div>
            <div class="border-t border-gray-200 pt-4">
                <p class="text-3xl font-bold text-gray-900 mb-2">₱{{ number_format($plan->price, 2)}} <span class="text-lg text-gray-600">/month</span></p>
                @if(!$currentPlan || $currentPlan->id != $plan->id)
                    <button type="button" onclick="openUpgradeModal({{ $plan->id }}, '{{ $plan->name }}', {{ $plan->price }})" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        {{ $currentPlan ? 'Switch to this Plan' : 'Subscribe Now' }}
                    </button>
                @else
                    <button disabled class="w-full bg-gray-300 text-gray-600 py-2 rounded-lg cursor-not-allowed">
                        Current Plan
                    </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Active Add-ons -->
@if($activeAddons->count() > 0)
<div class="mb-6">
    <h2 class="text-gray-800 font-bold text-2xl mb-4">Active Add-ons</h2>
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Add-on</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($activeAddons as $addon)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $addon->name }}</div>
                        <div class="text-sm text-gray-500">{{ $addon->description }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-gray-900">₱{{ number_format($addon->price, 2) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                        {{ \Carbon\Carbon::parse($addon->pivot->expires_at)->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Available Add-ons -->
<div>
    <h2 class="text-gray-800 font-bold text-2xl mb-4">Available Add-ons</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($addons as $addon)
        <div class="bg-white border border-gray-200 rounded-lg p-6">
            <h3 class="text-gray-900 text-xl font-bold mb-2">{{ $addon->name }}</h3>
            <p class="text-gray-600 mb-4">{{ $addon->description }}</p>
            <p class="text-2xl font-bold text-gray-900 mb-4">₱{{ number_format($addon->price, 2) }}</p>
            <form action="{{ route('client.services.purchase-addon', $addon) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                    Purchase Add-on
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>

<!-- Upgrade Request Modal -->
<div id="upgradeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900">Plan Change Request</h3>
                <button onclick="closeUpgradeModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form id="upgradeForm" method="POST">
                @csrf
                <div class="space-y-4">
                    <!-- Error Display -->
                    <div id="modalError" class="hidden bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p id="modalErrorText" class="text-sm text-red-700 font-medium"></p>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Selected Plan</label>
                        <p id="modalPlanName" class="text-xl font-bold text-blue-600"></p>
                        <p id="modalPlanPrice" class="text-lg text-gray-600"></p>
                    </div>
                    @if($currentPlan)
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm text-gray-600 mb-1">Current Plan:</p>
                        <p class="font-semibold text-gray-900">{{ $currentPlan->name }} - ₱{{ number_format($currentPlan->price, 2) }}/month</p>
                    </div>
                    @endif
                    <div>
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for Change (Optional)</label>
                        <textarea id="reason" name="reason" rows="3" maxlength="500" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Tell us why you want to change your plan..."></textarea>
                        <p class="text-xs text-gray-500 mt-1"><span id="charCount">0</span>/500 characters</p>
                    </div>
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">Your request will be sent to the admin for approval. You will be notified once it's processed.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeUpgradeModal()" id="cancelBtn" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" id="submitBtn" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="submitBtnText">Submit Request</span>
                        <span id="submitBtnLoading" class="hidden">
                            <svg class="inline animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Submitting...
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUpgradeModal(planId, planName, planPrice) {
    document.getElementById('modalPlanName').textContent = planName;
    document.getElementById('modalPlanPrice').textContent = '₱' + planPrice.toLocaleString('en-PH', {minimumFractionDigits: 2}) + '/month';
    document.getElementById('upgradeForm').action = '/client/services/upgrade/' + planId;
    document.getElementById('modalError').classList.add('hidden');
    document.getElementById('upgradeModal').classList.remove('hidden');
}

function closeUpgradeModal() {
    document.getElementById('upgradeModal').classList.add('hidden');
    document.getElementById('reason').value = '';
    document.getElementById('charCount').textContent = '0';
    document.getElementById('modalError').classList.add('hidden');
    resetSubmitButton();
}

function resetSubmitButton() {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    
    submitBtn.disabled = false;
    submitBtnText.classList.remove('hidden');
    submitBtnLoading.classList.add('hidden');
    document.getElementById('cancelBtn').disabled = false;
}

// Character counter
const reasonTextarea = document.getElementById('reason');
const charCount = document.getElementById('charCount');

reasonTextarea.addEventListener('input', function() {
    const length = this.value.length;
    charCount.textContent = length;
    
    if (length > 500) {
        this.value = this.value.substring(0, 500);
        charCount.textContent = '500';
    }
});

// Form submission with loading state
document.getElementById('upgradeForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const submitBtnLoading = document.getElementById('submitBtnLoading');
    const cancelBtn = document.getElementById('cancelBtn');
    
    // Show loading state
    submitBtn.disabled = true;
    cancelBtn.disabled = true;
    submitBtnText.classList.add('hidden');
    submitBtnLoading.classList.remove('hidden');
});

// Close modal when clicking outside
document.getElementById('upgradeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeUpgradeModal();
    }
});

// Auto-hide success message after 5 seconds
@if(session('success') || session('error'))
setTimeout(function() {
    const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(function() {
            alert.remove();
        }, 500);
    });
}, 5000);
@endif
</script>
@endsection
