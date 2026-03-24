<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingController extends Controller
{
    /**
     * Display billing and payment information
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $customer = $user->customer;
        
        if (!$customer) {
            return redirect()->route('client.dashboard')
                ->with('error', 'Customer profile not found. Please contact administrator.');
        }
        
        $balance = $customer->balance ?? 0;
        
        // Get status filter from request
        $statusFilter = $request->input('status', 'all');
        
        // Build invoices query
        $query = $customer->invoices()->orderBy('created_at', 'desc');
        
        // Apply status filter
        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }
        
        $invoices = $query->paginate(10)->appends(['status' => $statusFilter]);
        $payments = $customer->payments()->orderBy('payment_date', 'desc')->take(10)->get();
        
        // Get payment settings
        $gcashNumber = PaymentSetting::get('gcash_number', '09618377290');
        $gcashAccountName = PaymentSetting::get('gcash_account_name', 'Eddie Jr G.');
        $gcashQrCode = PaymentSetting::get('gcash_qr_code');
        
        return view('client.billing.index', compact('balance', 'invoices', 'payments', 'gcashNumber', 'gcashAccountName', 'gcashQrCode', 'statusFilter'));
    }

    /**
     * Process payment for an invoice
     */
    public function pay($invoiceId)
    {
        // Payment processing logic will be implemented here
        // This would integrate with payment gateways
        return redirect()->route('client.billing.index')
            ->with('success', 'Payment initiated successfully');
    }

    /**
     * Submit GCash payment reference
     */
    public function submitPayment(Request $request)
    {
        $validated = $request->validate([
            'reference_number' => 'required|string|min:10|max:50',
            'amount' => 'required|numeric|min:1|max:999999',
            'invoice_id' => 'nullable|exists:invoices,id'
        ], [
            'reference_number.required' => 'GCash reference number is required',
            'reference_number.min' => 'Reference number must be at least 10 characters',
            'amount.required' => 'Payment amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'amount.min' => 'Amount must be greater than 0'
        ]);

        $user = Auth::user();
        $customer = $user->customer;

        if (!$customer) {
            return redirect()->route('client.billing.index')
                ->with('error', 'Customer profile not found. Please contact administrator.');
        }

        try {
            // Create pending payment record
            $payment = $customer->payments()->create([
                'user_id' => $user->id,
                'customer_id' => $customer->id,
                'amount' => $validated['amount'],
                'payment_method' => 'gcash',
                'reference_number' => $validated['reference_number'],
                'payment_date' => now(),
                'status' => 'pending',
                'method' => 'GCash',
                'invoice_id' => $validated['invoice_id'] ?? null
            ]);

            // Send payment submission notification
            $notificationService = app(NotificationService::class);
            $notificationService->sendPaymentConfirmation(
                $customer, 
                $validated['amount'], 
                $validated['reference_number']
            );

            return redirect()->route('client.billing.index')
                ->with('success', 'Payment confirmation submitted successfully! Your payment is under review and will be verified shortly.');
        } catch (\Exception $e) {
            return redirect()->route('client.billing.index')
                ->with('error', 'Failed to submit payment. Please try again or contact support.');
        }
    }
}
