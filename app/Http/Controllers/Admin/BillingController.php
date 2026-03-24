<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BillingController extends Controller
{
    public function index()
    {
        // Revenue statistics
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        $todayRevenue = Payment::where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');
        
        // Invoice statistics
        $pendingInvoices = Invoice::where('status', 'unpaid')->count();
        $paidInvoices = Invoice::where('status', 'paid')->count();
        $overdueInvoices = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->count();
        
        $pendingAmount = Invoice::where('status', 'unpaid')->sum('amount');
        $overdueAmount = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->sum('amount');
        
        // Recent payments
        $recentPayments = Payment::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        // Monthly revenue chart data (last 6 months)
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'revenue' => Payment::where('status', 'completed')
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->sum('amount')
            ];
        }
        
        return view('admin.billing.index', compact(
            'totalRevenue',
            'monthlyRevenue',
            'todayRevenue',
            'pendingInvoices',
            'paidInvoices',
            'overdueInvoices',
            'pendingAmount',
            'overdueAmount',
            'recentPayments',
            'monthlyData'
        ));
    }

    public function invoices()
    {
        $invoices = Invoice::with('customer')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.billing.invoices', compact('invoices'));
    }

    public function generateInvoices()
    {
        // Get all active subscriptions
        $subscriptions = Subscription::where('status', 'active')
            ->with('customer', 'servicePlan')
            ->get();
        
        $generatedCount = 0;
        
        foreach ($subscriptions as $subscription) {
            // Check if invoice already generated for this billing cycle
            $existingInvoice = Invoice::where('customer_id', $subscription->customer_id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->first();
            
            if (!$existingInvoice && $subscription->customer && $subscription->servicePlan) {
                $customer = $subscription->customer;
                $invoiceAmount = $subscription->servicePlan->price;
                
                // Check if customer has excess payment (positive balance)
                if ($customer->balance > 0) {
                    // Apply excess payment to reduce invoice amount
                    $creditAvailable = $customer->balance;
                    
                    if ($creditAvailable >= $invoiceAmount) {
                        // Credit covers full invoice
                        $invoiceAmount = 0;
                        $customer->balance -= $subscription->servicePlan->price;
                        $description = "Monthly subscription - {$subscription->servicePlan->name} (Paid from credit balance)";
                        $status = 'paid';
                    } else {
                        // Partial credit application
                        $invoiceAmount -= $creditAvailable;
                        $customer->balance = 0;
                        $description = "Monthly subscription - {$subscription->servicePlan->name} (₱" . number_format($creditAvailable, 2) . " credit applied)";
                        $status = 'unpaid';
                    }
                    
                    $customer->save();
                } else {
                    $description = "Monthly subscription - {$subscription->servicePlan->name}";
                    $status = 'unpaid';
                }
                
                Invoice::create([
                    'customer_id' => $subscription->customer_id,
                    'amount' => $invoiceAmount,
                    'due_date' => now()->addDays(15),
                    'status' => $status,
                    'description' => $description,
                ]);
                $generatedCount++;
            }
        }
        
        return redirect()->route('admin.billing.invoices')
            ->with('success', "Generated {$generatedCount} invoices successfully");
    }

    public function reports()
    {
        // Various financial reports
        $year = now()->year;
        
        // Annual revenue by month
        $annualRevenue = [];
        for ($month = 1; $month <= 12; $month++) {
            $annualRevenue[] = [
                'month' => Carbon::create($year, $month, 1)->format('F'),
                'revenue' => Payment::where('status', 'completed')
                    ->whereMonth('created_at', $month)
                    ->whereYear('created_at', $year)
                    ->sum('amount')
            ];
        }
        
        // Revenue by service plan
        $planRevenue = Subscription::with('servicePlan')
            ->where('status', 'active')
            ->get()
            ->groupBy('service_plan_id')
            ->map(function($subs) {
                return [
                    'plan' => $subs->first()->servicePlan->name,
                    'subscribers' => $subs->count(),
                    'revenue' => $subs->sum(fn($s) => $s->servicePlan->price)
                ];
            })->values();
        
        // Aging report - overdue invoices
        $agingReport = [
            '0-30' => Invoice::where('status', 'unpaid')
                ->whereBetween('due_date', [now()->subDays(30), now()])
                ->sum('amount'),
            '31-60' => Invoice::where('status', 'unpaid')
                ->whereBetween('due_date', [now()->subDays(60), now()->subDays(31)])
                ->sum('amount'),
            '61-90' => Invoice::where('status', 'unpaid')
                ->whereBetween('due_date', [now()->subDays(90), now()->subDays(61)])
                ->sum('amount'),
            '90+' => Invoice::where('status', 'unpaid')
                ->where('due_date', '<', now()->subDays(90))
                ->sum('amount'),
        ];
        
        return view('admin.billing.reports', compact(
            'annualRevenue',
            'planRevenue',
            'agingReport',
            'year'
        ));
    }
}
