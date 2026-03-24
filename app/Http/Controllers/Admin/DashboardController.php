<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\NetworkDevice;
use App\Models\Ticket;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Customer Statistics
        $totalCustomers = Customer::count();
        $newCustomersThisMonth = Customer::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $activeConnections = Customer::where('connection_status', 'active')->count();

        // Revenue Statistics
        $monthlyRevenue = Invoice::where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        $arpu = $totalCustomers > 0 ? $monthlyRevenue / $totalCustomers : 0;

        // Invoice Statistics
        $pendingInvoices = Invoice::where('status', 'unpaid')->count();
        $pendingAmount = Invoice::where('status', 'unpaid')->sum('amount');

        // Recent Clients Status
        $recentClients = Customer::with(['subscriptions.servicePlan', 'ipAllocation'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Network Devices (for alerts)
        $offlineDevices = NetworkDevice::where('status', 'offline')->count();

        // Recent Tickets
        $recentTickets = Ticket::with('customer')
            ->where('status', '!=', 'closed')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Alerts
        $delinquentAccounts = Customer::whereHas('invoices', function($query) {
            $query->where('status', 'unpaid')
                  ->where('due_date', '<', now());
        })->count();

        $lowInventory = InventoryItem::where('status', 'in_stock')
            ->where('quantity', '<', 10)
            ->count();

        return view('admin.dashboard', compact(
            'totalCustomers',
            'newCustomersThisMonth',
            'activeConnections',
            'monthlyRevenue',
            'arpu',
            'pendingInvoices',
            'pendingAmount',
            'recentClients',
            'offlineDevices',
            'recentTickets',
            'delinquentAccounts',
            'lowInventory'
        ));
    }
}
