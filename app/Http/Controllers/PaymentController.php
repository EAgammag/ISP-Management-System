<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    // TRACK & MONITOR: View all payments with filters
    public function index(Request $request)
    {
        $query = Payment::with('user');

        // Monitor by Status (e.g., show only Overdue)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by Client Name
        if ($request->has('search') && $request->search != '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        $payments = $query->latest()->paginate(20);

        return view('payments.index', compact('payments'));
    }

    // EDIT PAYMENTS: Show the edit form or return JSON for AJAX
    public function edit(Payment $payment)
    {
        if (request()->ajax()) {
            return response()->json([
                'id' => $payment->id,
                'amount' => $payment->amount,
                'status' => $payment->status,
                'payment_date' => $payment->payment_date,
                'notes' => $payment->notes,
                'client_name' => $payment->user->name
            ]);
        }
        
        return view('payments.edit', compact('payment'));
    }

    // UPDATE PAYMENTS: Handle the logic
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,failed,overdue',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500'
        ]);

        $payment->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment updated successfully.',
                'payment' => [
                    'id' => $payment->id,
                    'amount' => number_format($payment->amount, 2),
                    'status' => ucfirst($payment->status),
                    'payment_date' => $payment->payment_date ? date('M d, Y', strtotime($payment->payment_date)) : 'N/A'
                ]
            ]);
        }

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully.');
    }
    
    /**
     * Note: DELETE functionality is intentionally NOT implemented
     * This module is designed with Limited Access - users can only
     * view and edit payment statuses, not delete records.
     */
}
