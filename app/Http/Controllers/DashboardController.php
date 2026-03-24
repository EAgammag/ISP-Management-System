<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // If admin, show admin view, otherwise show client view
        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }
        
        return $this->clientDashboard();
    }
    
    /**
     * Show admin dashboard.
     */
    private function adminDashboard()
    {
        // Redirect admin users to admin dashboard
        return redirect()->route('admin.dashboard');
    }
    
    /**
     * Show client dashboard.
     */
    private function clientDashboard()
    {
        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            return redirect()->route('login')->with('error', 'No customer profile found');
        }

        // Get current subscription
        $subscription = $customer->activeSubscription();
        
        // Get usage data
        $monthlyUsage = $customer->getCurrentMonthUsage();
        $todayUsage = $customer->dataUsages()->today()->sum('data_used');
        $dataLimit = ($subscription && $subscription->servicePlan) ? $subscription->servicePlan->data_limit : 1024; // Default to 1TB if no subscription
        
        // Get balance and connection status
        $balance = $customer->balance ?? 0;
        $connectionStatus = $customer->connection_status ?? 'Active';
        
        // Get recent invoices and tickets
        $recentInvoices = $customer->invoices()->latest()->take(5)->get();
        $recentTickets = $customer->tickets()->latest()->take(5)->get();
        
        // Count unpaid invoices and open tickets
        $unpaidInvoices = $customer->invoices()->where('status', 'unpaid')->count();
        $openTickets = $customer->tickets()->where('status', 'open')->count();
        
        return view('client.dashboard', compact(
            'customer', 
            'subscription', 
            'monthlyUsage', 
            'todayUsage',
            'dataLimit',
            'balance',
            'connectionStatus',
            'recentInvoices',
            'recentTickets',
            'unpaidInvoices',
            'openTickets'
        ));
    }
}
