@extends('layouts.admin')

@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your ISP operations')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Customers -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Total Customers</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalCustomers }}</p>
                <p class="text-green-600 text-sm mt-1">
                    <span class="font-semibold">+{{ $newCustomersThisMonth }}</span> this month
                </p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Connections -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Active Connections</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $activeConnections }}</p>
                <p class="text-gray-600 text-sm mt-1">
                    {{ number_format(($activeConnections / max($totalCustomers, 1)) * 100, 1) }}% uptime
                </p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Monthly Revenue -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Monthly Revenue</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">₱{{ number_format($monthlyRevenue, 2) }}</p>
                <p class="text-purple-600 text-sm mt-1">
                    ARPU: ₱{{ number_format($arpu, 2) }}
                </p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Invoices -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 text-sm font-medium">Pending Invoices</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingInvoices }}</p>
                <p class="text-red-600 text-sm mt-1">
                    ₱{{ number_format($pendingAmount, 2) }}
                </p>
            </div>
            <div class="bg-red-100 rounded-full p-3">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Client Status & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Client Status -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Client Status</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @foreach($recentClients as $client)
                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                    <div class="flex items-center">
                        <div class="w-2 h-2 rounded-full mr-3 {{ $client->connection_status == 'active' ? 'bg-green-500' : ($client->connection_status == 'suspended' ? 'bg-red-500' : 'bg-gray-500') }}"></div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $client->name }}</p>
                            <p class="text-sm text-gray-600">
                                @php
                                    $activeSubscription = $client->subscriptions->where('status', 'active')
                                        ->where('end_date', '>=', now())
                                        ->first();
                                @endphp
                                @if($activeSubscription && $activeSubscription->servicePlan)
                                    {{ $activeSubscription->servicePlan->name }} - {{ $activeSubscription->servicePlan->speed }} Mbps
                                @else
                                    No Active Plan
                                @endif
                                @if($client->ipAllocation)
                                    | {{ $client->ipAllocation->ip_address }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold {{ $client->connection_status == 'active' ? 'text-green-600' : ($client->connection_status == 'suspended' ? 'text-red-600' : 'text-gray-600') }}">
                            {{ ucfirst($client->connection_status) }}
                        </p>
                        @if($client->balance != 0)
                            <p class="text-xs {{ $client->balance < 0 ? 'text-red-500' : 'text-green-500' }}">
                                ₱{{ number_format(abs($client->balance), 2) }} {{ $client->balance < 0 ? 'overdue' : 'credit' }}
                            </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('admin.customers.index') }}" class="block text-center text-cyan-600 hover:text-cyan-700 font-semibold mt-4">
                View All Clients →
            </a>
        </div>
    </div>

    <!-- Recent Tickets -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Recent Support Tickets</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($recentTickets as $ticket)
                <div class="flex items-start justify-between py-3 border-b border-gray-100">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $ticket->subject }}</p>
                        <p class="text-sm text-gray-600">{{ $ticket->customer->name }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs rounded-full {{ $ticket->priority == 'urgent' ? 'bg-red-100 text-red-800' : ($ticket->priority == 'high' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No recent tickets</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions & Alerts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h3>
        <div class="space-y-3">
            <button onclick="openAddCustomerModal()" class="block w-full bg-cyan-600 hover:bg-cyan-700 text-white text-center py-3 rounded-lg font-semibold transition">
                + Add New Client
            </button>
            <button onclick="openGenerateInvoicesModal()" class="block w-full bg-purple-600 hover:bg-purple-700 text-white text-center py-3 rounded-lg font-semibold transition">
                Generate Invoices
            </button>
            <button onclick="openMonitorNetworkModal()" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 rounded-lg font-semibold transition">
                Monitor Network
            </button>
        </div>
    </div>

    <!-- System Alerts -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">System Alerts</h3>
        <div class="space-y-3">
            @if($offlineDevices > 0)
            <div class="bg-red-50 border-l-4 border-red-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">
                            <span class="font-semibold">{{ $offlineDevices }} network device(s)</span> are offline
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if($delinquentAccounts > 0)
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-800">
                            <span class="font-semibold">{{ $delinquentAccounts }} customer(s)</span> have overdue payments
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if($lowInventory > 0)
            <div class="bg-orange-50 border-l-4 border-orange-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-orange-800">
                            <span class="font-semibold">{{ $lowInventory }} inventory item(s)</span> are running low
                        </p>
                    </div>
                </div>
            </div>
            @endif

            @if($offlineDevices == 0 && $delinquentAccounts == 0 && $lowInventory == 0)
            <div class="bg-green-50 border-l-4 border-green-500 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-800">
                            All systems operating normally
                        </p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Add New Client Modal -->
<div id="addCustomerModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Client</h2>
            <button class="close-btn" onclick="closeAddCustomerModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="addCustomerForm" action="{{ route('admin.customers.store') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label for="account_number">Account Number (Alphanumeric - 12char) *</label>
                    <input type="text" id="account_number" name="account_number" maxlength="12" pattern="[A-Za-z0-9]{12}" placeholder="e.g., ACC123456789" required>
                    <small class="text-gray-500">12 characters: letters and numbers only</small>
                </div>

                <div class="form-group">
                    <label for="server_account_name">Server Account Name</label>
                    <input type="text" id="server_account_name" name="server_account_name" placeholder="e.g., user123">
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ now()->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="service_plan_id">Plan</label>
                    <select id="service_plan_id" name="service_plan_id">
                        <option value="">-- Select Plan (Optional) --</option>
                        @php
                            $servicePlans = \App\Models\ServicePlan::all();
                        @endphp
                        @foreach($servicePlans as $plan)
                            <option value="{{ $plan->id }}">{{ $plan->name }} - {{ $plan->speed }}Mbps - ₱{{ number_format($plan->price, 2) }}/mo</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="customer_name">Full Name *</label>
                    <input type="text" id="customer_name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_email">Email Address *</label>
                    <input type="email" id="customer_email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_phone">Phone Number *</label>
                    <input type="tel" id="customer_phone" name="phone" required>
                </div>
                
                <div class="form-group">
                    <label for="customer_address">Address *</label>
                    <textarea id="customer_address" name="address" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="connection_status">Connection Status</label>
                    <select id="connection_status" name="connection_status">
                        <option value="active">Active</option>
                        <option value="inactive" selected>Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeAddCustomerModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Create Client</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Generate Invoices Modal -->
<div id="generateInvoicesModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Generate Invoices</h2>
            <button class="close-btn" onclick="closeGenerateInvoicesModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="generateInvoicesForm" action="{{ route('admin.billing.invoices.generate') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="font-semibold text-gray-800 mb-3 block">Select Invoice Generation Type</label>
                    
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="generation_type" value="all" checked>
                            <span class="radio-label">
                                <strong>All Active Customers</strong>
                                <small>Generate invoices for all customers with active subscriptions</small>
                            </span>
                        </label>
                        
                        <label class="radio-option">
                            <input type="radio" name="generation_type" value="unpaid">
                            <span class="radio-label">
                                <strong>Customers with Unpaid Invoices</strong>
                                <small>Generate reminder invoices for customers with outstanding payments</small>
                            </span>
                        </label>
                        
                        <label class="radio-option">
                            <input type="radio" name="generation_type" value="specific_plan">
                            <span class="radio-label">
                                <strong>Specific Service Plan</strong>
                                <small>Generate invoices for customers on a particular service plan</small>
                            </span>
                        </label>
                        
                        <label class="radio-option">
                            <input type="radio" name="generation_type" value="overdue">
                            <span class="radio-label">
                                <strong>Overdue Accounts Only</strong>
                                <small>Target customers with overdue payments (past due date)</small>
                            </span>
                        </label>
                    </div>
                </div>
                
                <div id="planSelectGroup" class="form-group" style="display: none;">
                    <label for="service_plan_id">Select Service Plan</label>
                    <select id="service_plan_id" name="service_plan_id">
                        <option value="">Choose a plan...</option>
                        <!-- Plans will be populated via JavaScript or server-side -->
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="billing_month">Billing Month</label>
                    <input type="month" id="billing_month" name="billing_month" value="{{ date('Y-m') }}">
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeGenerateInvoicesModal()">Cancel</button>
                    <button type="submit" class="btn-primary">Generate Invoices</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Monitor Network Modal -->
<div id="monitorNetworkModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Network Monitoring</h2>
            <button class="close-btn" onclick="closeMonitorNetworkModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="network-stats">
                <div class="stat-item">
                    <div class="stat-label">Network Health</div>
                    <div class="progress-container">
                        <div class="progress-bar" id="networkHealthBar">
                            <div class="progress-fill bg-green-500" style="width: 0%"></div>
                        </div>
                        <span class="progress-text" id="networkHealthText">0%</span>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-label">Bandwidth Usage</div>
                    <div class="progress-container">
                        <div class="progress-bar" id="bandwidthBar">
                            <div class="progress-fill bg-blue-500" style="width: 0%"></div>
                        </div>
                        <span class="progress-text" id="bandwidthText">0%</span>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-label">Device Uptime</div>
                    <div class="progress-container">
                        <div class="progress-bar" id="uptimeBar">
                            <div class="progress-fill bg-cyan-500" style="width: 0%"></div>
                        </div>
                        <span class="progress-text" id="uptimeText">0%</span>
                    </div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-label">Active Connections</div>
                    <div class="progress-container">
                        <div class="progress-bar" id="connectionsBar">
                            <div class="progress-fill bg-purple-500" style="width: 0%"></div>
                        </div>
                        <span class="progress-text" id="connectionsText">0%</span>
                    </div>
                </div>
            </div>
            
            <div class="network-devices-list">
                <h4 class="font-semibold text-gray-800 mb-3 mt-6">Network Devices Status</h4>
                <div id="devicesList" class="space-y-2">
                    <p class="text-gray-500 text-center py-4">Loading devices...</p>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeMonitorNetworkModal()">Close</button>
                <a href="{{ route('admin.network.index') }}" class="btn-primary">View Full Network</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: #FFFFFF;
        margin: 3% auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s;
        max-height: 90vh;
        overflow-y: auto;
    }

    @keyframes slideDown {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #0891b2, #06b6d4);
        padding: 1.5rem 2rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        color: #FFFFFF;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    .close-btn {
        color: #FFFFFF;
        font-size: 2rem;
        font-weight: bold;
        cursor: pointer;
        background: none;
        border: none;
        transition: transform 0.2s;
        line-height: 1;
    }

    .close-btn:hover {
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="tel"],
    .form-group input[type="month"],
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #0891b2;
        box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.1);
    }

    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .radio-option {
        display: flex;
        align-items: flex-start;
        padding: 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .radio-option:hover {
        border-color: #0891b2;
        background-color: #f0fdff;
    }

    .radio-option input[type="radio"] {
        margin-top: 0.25rem;
        margin-right: 0.75rem;
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .radio-option input[type="radio"]:checked {
        accent-color: #0891b2;
    }

    .radio-option input[type="radio"]:checked ~ .radio-label {
        color: #0891b2;
    }

    .radio-label {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .radio-label strong {
        font-weight: 600;
        color: #1f2937;
    }

    .radio-label small {
        color: #6b7280;
        font-size: 0.85rem;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }

    .btn-primary, .btn-secondary {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.95rem;
    }

    .btn-primary {
        background: #0891b2;
        color: white;
    }

    .btn-primary:hover {
        background: #0e7490;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.3);
    }

    .btn-secondary {
        background: #e5e7eb;
        color: #374151;
    }

    .btn-secondary:hover {
        background: #d1d5db;
    }

    /* Network Monitor Styles */
    .network-stats {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .stat-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .stat-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.95rem;
    }

    .progress-container {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .progress-bar {
        flex: 1;
        height: 24px;
        background: #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .progress-fill {
        height: 100%;
        transition: width 1s ease-out;
        border-radius: 12px;
        position: relative;
    }

    .progress-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .progress-text {
        font-weight: 600;
        color: #374151;
        min-width: 50px;
        text-align: right;
    }

    .bg-green-500 { background-color: #10b981; }
    .bg-blue-500 { background-color: #3b82f6; }
    .bg-cyan-500 { background-color: #06b6d4; }
    .bg-purple-500 { background-color: #8b5cf6; }

    .network-devices-list {
        margin-top: 1.5rem;
    }

    .device-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f9fafb;
        border-radius: 8px;
        border-left: 4px solid #10b981;
    }

    .device-item.offline {
        border-left-color: #ef4444;
    }

    .device-name {
        font-weight: 600;
        color: #1f2937;
    }

    .device-status {
        font-size: 0.85rem;
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-weight: 600;
    }

    .device-status.online {
        background: #d1fae5;
        color: #065f46;
    }

    .device-status.offline {
        background: #fee2e2;
        color: #991b1b;
    }
</style>

<script>
    // Add Customer Modal
    function openAddCustomerModal() {
        document.getElementById('addCustomerModal').style.display = 'block';
    }

    function closeAddCustomerModal() {
        document.getElementById('addCustomerModal').style.display = 'none';
        document.getElementById('addCustomerForm').reset();
    }

    // Generate Invoices Modal
    function openGenerateInvoicesModal() {
        document.getElementById('generateInvoicesModal').style.display = 'block';
    }

    function closeGenerateInvoicesModal() {
        document.getElementById('generateInvoicesModal').style.display = 'none';
        document.getElementById('generateInvoicesForm').reset();
    }

    // Monitor Network Modal
    function openMonitorNetworkModal() {
        document.getElementById('monitorNetworkModal').style.display = 'block';
        loadNetworkMonitoring();
    }

    function closeMonitorNetworkModal() {
        document.getElementById('monitorNetworkModal').style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const modals = ['addCustomerModal', 'generateInvoicesModal', 'monitorNetworkModal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // Handle radio button change for invoice generation
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[name="generation_type"]');
        const planSelectGroup = document.getElementById('planSelectGroup');
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'specific_plan') {
                    planSelectGroup.style.display = 'block';
                } else {
                    planSelectGroup.style.display = 'none';
                }
            });
        });
    });

    // Load Network Monitoring Data
    function loadNetworkMonitoring() {
        // Simulate loading network data
        setTimeout(() => {
            // Animate progress bars
            animateProgress('networkHealthBar', 'networkHealthText', 95);
            animateProgress('bandwidthBar', 'bandwidthText', 68);
            animateProgress('uptimeBar', 'uptimeText', 99);
            animateProgress('connectionsBar', 'connectionsText', {{ $activeConnections > 0 ? round(($activeConnections / max($totalCustomers, 1)) * 100) : 0 }});
            
            // Load devices list (using data from controller)
            const devicesList = document.getElementById('devicesList');
            devicesList.innerHTML = `
                <div class="space-y-2">
                    @php
                        $devices = \App\Models\NetworkDevice::orderBy('status', 'desc')->take(5)->get();
                    @endphp
                    @foreach($devices as $device)
                    <div class="device-item {{ $device->status == 'offline' ? 'offline' : '' }}">
                        <span class="device-name">{{ $device->name }}</span>
                        <span class="device-status {{ $device->status }}">{{ ucfirst($device->status) }}</span>
                    </div>
                    @endforeach
                </div>
            `;
        }, 300);
    }

    function animateProgress(barId, textId, targetPercent) {
        const progressBar = document.querySelector(`#${barId} .progress-fill`);
        const progressText = document.getElementById(textId);
        let currentPercent = 0;
        
        const interval = setInterval(() => {
            if (currentPercent >= targetPercent) {
                clearInterval(interval);
                return;
            }
            currentPercent += 1;
            progressBar.style.width = currentPercent + '%';
            progressText.textContent = currentPercent + '%';
        }, 10);
    }
</script>

@endsection
