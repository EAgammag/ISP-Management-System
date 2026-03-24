<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Payment;
use App\Models\ServicePlan;
use App\Models\Invoice;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        // Get all customers with relationships
        $allCustomers = Customer::with(['subscriptions.servicePlan', 'ipAllocation'])->get();
        
        // Group customers by their service plan
        $customersByPlan = [];
        
        // Get customers with no plan
        $customersNoPlan = $allCustomers->filter(function($customer) {
            $activeSubscription = $customer->subscriptions->where('status', 'active')
                ->where('end_date', '>=', now())
                ->first();
            return !$activeSubscription || !$activeSubscription->servicePlan;
        });
        
        if ($customersNoPlan->count() > 0) {
            $customersByPlan['No Plan'] = [
                'plan' => null,
                'customers' => $customersNoPlan
            ];
        }
        
        // Get all service plans
        $servicePlans = ServicePlan::all();
        
        foreach ($servicePlans as $plan) {
            $planCustomers = $allCustomers->filter(function($customer) use ($plan) {
                $activeSubscription = $customer->subscriptions->where('status', 'active')
                    ->where('end_date', '>=', now())
                    ->first();
                return $activeSubscription && 
                       $activeSubscription->servicePlan && 
                       $activeSubscription->servicePlan->id == $plan->id;
            });
            
            if ($planCustomers->count() > 0) {
                $customersByPlan[$plan->name] = [
                    'plan' => $plan,
                    'customers' => $planCustomers
                ];
            }
        }
        
        // Statistics
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::where('connection_status', 'active')->count();
        $suspendedCustomers = Customer::where('connection_status', 'suspended')->count();
        $newThisMonth = Customer::whereMonth('created_at', now()->month)->count();

        return view('admin.customers.index', compact(
            'customersByPlan',
            'totalCustomers',
            'activeCustomers',
            'suspendedCustomers',
            'newThisMonth'
        ));
    }

    public function create()
    {
        $servicePlans = ServicePlan::all();
        return view('admin.customers.create', compact('servicePlans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_number' => 'required|string|max:12|unique:customers',
            'server_account_name' => 'nullable|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'connection_status' => 'nullable|in:active,inactive,suspended',
            'balance' => 'nullable|numeric',
            'service_plan_id' => 'nullable|exists:service_plans,id',
            'start_date' => 'nullable|date',
        ]);

        // Set defaults if not provided
        $validated['connection_status'] = $validated['connection_status'] ?? 'inactive';
        $validated['balance'] = $validated['balance'] ?? 0;

        $customer = Customer::create($validated);

        // Create subscription if plan is selected
        if ($request->filled('service_plan_id') && $request->filled('start_date')) {
            $servicePlan = ServicePlan::find($request->service_plan_id);
            $startDate = Carbon::parse($request->start_date);
            $endDate = $startDate->copy()->addMonth();

            $subscription = $customer->subscriptions()->create([
                'service_plan_id' => $request->service_plan_id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            // Create invoice if customer is active
            if ($customer->connection_status === 'active') {
                $this->createInvoiceForCustomer($customer, $servicePlan, $subscription);
            }
        }

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Client created successfully' . 
                ($customer->connection_status === 'active' ? ' and billing schedule generated' : ''));
    }

    public function show(Customer $customer)
    {
        $customer->load(['subscriptions.servicePlan', 'invoices', 'tickets', 'ipAllocation', 'inventoryItems']);
        
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'connection_status' => 'nullable|in:active,inactive,suspended',
            'balance' => 'nullable|numeric',
        ]);

        $customer->update($validated);

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', 'Client updated successfully');
    }

    public function suspend(Customer $customer)
    {
        $customer->update(['connection_status' => 'suspended']);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Client account suspended');
    }

    public function activate(Customer $customer)
    {
        $customer->update(['connection_status' => 'active']);

        // Get the active subscription
        $activeSubscription = $customer->subscriptions()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->with('servicePlan')
            ->first();

        // Create invoice if customer has an active subscription
        if ($activeSubscription && $activeSubscription->servicePlan) {
            $this->createInvoiceForCustomer($customer, $activeSubscription->servicePlan, $activeSubscription);
            
            return redirect()->route('admin.customers.index')
                ->with('success', 'Client account activated and billing schedule created');
        }

        return redirect()->route('admin.customers.index')
            ->with('success', 'Client account activated');
    }

    /**
     * Download payment receipt as PDF
     * 
     * @param Payment $payment
     * @return \Illuminate\Http\Response
     */
    public function downloadReceipt(Payment $payment)
    {
        // Load the receipt view with payment data
        $pdf = PDF::loadView('admin.receipts.receipt-pdf', [
            'payment' => $payment->load('customer', 'invoice')
        ]);

        // Set paper size to A4
        $pdf->setPaper('A4', 'portrait');

        // Generate filename
        $receiptNumber = 'RCP-' . str_pad($payment->id, 6, '0', STR_PAD_LEFT);
        $filename = $receiptNumber . '_' . $payment->customer->name . '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    public function destroy(Customer $customer)
    {
        $customerName = $customer->name;
        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', "Client '$customerName' has been deleted successfully");
    }

    /**
     * Create an invoice for the customer based on their subscription
     * 
     * @param Customer $customer
     * @param ServicePlan $servicePlan
     * @param Subscription $subscription
     * @return Invoice
     */
    private function createInvoiceForCustomer(Customer $customer, ServicePlan $servicePlan, Subscription $subscription)
    {
        // Generate unique invoice number
        $lastInvoice = Invoice::latest('id')->first();
        $invoiceNumber = 'INV-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 6, '0', STR_PAD_LEFT);

        // Calculate due date (15 days from now by default)
        $dueDate = now()->addDays(15);

        // Get billing period
        $billingPeriod = Carbon::parse($subscription->start_date)->format('M Y') . ' - ' . 
                         Carbon::parse($subscription->end_date)->format('M Y');

        // Create the invoice
        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'subscription_id' => $subscription->id,
            'invoice_number' => $invoiceNumber,
            'amount' => $servicePlan->price,
            'status' => 'unpaid',
            'due_date' => $dueDate,
            'billing_period' => $billingPeriod,
            'description' => 'Monthly Subscription - ' . $servicePlan->name . ' (' . $billingPeriod . ')',
        ]);

        return $invoice;
    }
}
