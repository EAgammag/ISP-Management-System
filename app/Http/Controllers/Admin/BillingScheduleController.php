<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class BillingScheduleController extends Controller
{
    public function index(Request $request)
    {
        // Date filtering - default to wide range (3 months back to 3 months forward)
        $startDate = $request->input('start_date', now()->subMonths(3)->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->addMonths(3)->endOfMonth()->format('Y-m-d'));
        $statusFilter = $request->input('status', 'all');
        
        // Build query for invoices with customer relationship
        // Only show invoices for active customers
        $query = Invoice::with(['customer', 'payments'])
            ->whereHas('customer', function($q) {
                $q->where('connection_status', 'active');
            })
            ->whereBetween('due_date', [$startDate, $endDate]);
        
        // Apply status filter
        if ($statusFilter !== 'all') {
            if ($statusFilter === 'overdue') {
                $query->where('status', 'unpaid')
                      ->where('due_date', '<', now());
            } else {
                $query->where('status', $statusFilter);
            }
        }
        
        $invoices = $query->orderBy('due_date', 'desc')->paginate(20);
        
        // Calculate statistics - Count unique clients by status (active customers only)
        $paidClients = Invoice::where('status', 'paid')
            ->whereHas('customer', function($q) {
                $q->where('connection_status', 'active');
            })
            ->whereBetween('due_date', [$startDate, $endDate])
            ->distinct('customer_id')
            ->count('customer_id');
            
        $unpaidClients = Invoice::where('status', 'unpaid')
            ->whereHas('customer', function($q) {
                $q->where('connection_status', 'active');
            })
            ->whereBetween('due_date', [$startDate, $endDate])
            ->distinct('customer_id')
            ->count('customer_id');
            
        $overdueClients = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->whereHas('customer', function($q) {
                $q->where('connection_status', 'active');
            })
            ->whereBetween('due_date', [$startDate, $endDate])
            ->distinct('customer_id')
            ->count('customer_id');
        
        $statistics = [
            'paid_clients' => $paidClients,
            'unpaid_clients' => $unpaidClients,
            'overdue_clients' => $overdueClients,
        ];
        
        return view('admin.billing-schedules.index', compact(
            'invoices',
            'statistics'
        ));
    }
    
    public function show(Invoice $invoice)
    {
        // Load necessary relationships
        $invoice->load(['customer', 'payments', 'subscription.servicePlan']);
        
        return view('admin.billing-schedules.show', compact('invoice'));
    }
    
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,unpaid',
            'payment_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500'
        ]);
        
        $invoice->status = $validated['status'];
        $invoice->save();
        
        // If marking as paid, create a payment record
        if ($validated['status'] === 'paid' && !$invoice->payments()->where('status', 'paid')->exists()) {
            Payment::create([
                'user_id' => $invoice->customer->user_id ?? auth()->id(),
                'customer_id' => $invoice->customer_id,
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'payment_date' => $validated['payment_date'] ?? now(),
                'payment_method' => 'manual',
                'status' => 'paid',
                'notes' => $validated['notes'] ?? 'Marked as paid by administrator'
            ]);
            
            // Update customer balance
            $invoice->customer->decrement('balance', $invoice->amount);
        }
        
        return redirect()->route('admin.billing-schedules.index')
            ->with('success', 'Invoice status updated successfully');
    }
    
    public function updateDueDate(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'due_date' => 'required|date'
        ]);
        
        $invoice->due_date = $validated['due_date'];
        $invoice->save();
        
        return redirect()->route('admin.billing-schedules.index')
            ->with('success', 'Due date updated successfully');
    }
    
    public function sendEmail(Invoice $invoice, NotificationService $notificationService)
    {
        // Load the customer relationship
        $invoice->load(['customer', 'payments', 'subscription.servicePlan']);
        
        if (!$invoice->customer) {
            return redirect()->route('admin.billing-schedules.index')
                ->with('error', 'Customer not found for this invoice');
        }
        
        // Check if customer has an email
        if (!$invoice->customer->email) {
            return redirect()->route('admin.billing-schedules.index')
                ->with('error', 'Customer does not have an email address');
        }
        
        // Compose email subject and message
        $subject = "Important: Your ISP Management System Bill for " . Carbon::parse($invoice->due_date)->format('F Y') . " is Now Available";
        
        $dueDate = Carbon::parse($invoice->due_date)->format('F j, Y');
        $invoiceDate = Carbon::parse($invoice->created_at)->format('F j, Y');
        $amount = number_format($invoice->amount, 2);
        
        // Get service plan details if available
        $planName = $invoice->subscription->servicePlan->name ?? 'N/A';
        $planAmount = $invoice->subscription->servicePlan->price ?? $invoice->amount;
        
        $message = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; color: #333;'>
            <h2 style='color: #2c3e50; border-bottom: 3px solid #3498db; padding-bottom: 10px;'>
                Important: Your ISP Management System Bill for " . Carbon::parse($invoice->due_date)->format('F Y') . " is Now Available
            </h2>
            
            <p style='font-size: 14px; line-height: 1.6;'>Dear <strong>{$invoice->customer->name}</strong>,</p>
            
            <p style='font-size: 14px; line-height: 1.6;'>
                Your Internet bill for the period ending <strong>" . Carbon::parse($invoice->due_date)->format('F j, Y') . "</strong> is now available.
            </p>
            
            <div style='background-color: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                <table style='width: 100%; border-collapse: collapse;'>
                    <tr>
                        <td style='padding: 8px 0; font-size: 14px;'><strong>Account Number:</strong></td>
                        <td style='padding: 8px 0; font-size: 14px; text-align: right;'>{$invoice->customer->account_number}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-size: 14px;'><strong>Invoice Number:</strong></td>
                        <td style='padding: 8px 0; font-size: 14px; text-align: right;'>{$invoice->invoice_number}</td>
                    </tr>
                    <tr style='border-top: 2px solid #dee2e6;'>
                        <td style='padding: 12px 0; font-size: 16px; color: #e74c3c;'><strong>Total Amount Due:</strong></td>
                        <td style='padding: 12px 0; font-size: 18px; font-weight: bold; color: #e74c3c; text-align: right;'>PHP {$amount}</td>
                    </tr>
                    <tr>
                        <td style='padding: 8px 0; font-size: 14px;'><strong>Payment Due Date:</strong></td>
                        <td style='padding: 8px 0; font-size: 14px; text-align: right; color: #e74c3c;'><strong>{$dueDate}</strong></td>
                    </tr>
                </table>
            </div>
            
            <h3 style='color: #2c3e50; margin-top: 30px; font-size: 16px;'>Summary of Charges:</h3>
            <table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>
                <tr style='border-bottom: 1px solid #dee2e6;'>
                    <td style='padding: 10px 0; font-size: 14px;'>Monthly Plan: {$planName}</td>
                    <td style='padding: 10px 0; font-size: 14px; text-align: right;'>PHP " . number_format($planAmount, 2) . "</td>
                </tr>
                <tr style='border-top: 2px solid #2c3e50;'>
                    <td style='padding: 12px 0; font-size: 15px;'><strong>Total:</strong></td>
                    <td style='padding: 12px 0; font-size: 15px; text-align: right;'><strong>PHP {$amount}</strong></td>
                </tr>
            </table>
            
            <div style='background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0;'>
                <p style='margin: 0; font-size: 14px; color: #856404;'>
                    <strong>⚠ Important:</strong> Please settle your payment on or before the due date to avoid service interruption and late fees.
                </p>
            </div>
            
            <p style='font-size: 14px; line-height: 1.6; margin-top: 20px;'>
                <strong>A detailed invoice is attached to this email for your reference.</strong>
            </p>
            
            <div style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6;'>
                <p style='font-size: 13px; color: #6c757d; line-height: 1.6;'>
                    Thank you for choosing <strong>ISP Management System</strong>.<br>
                    For any inquiries, please contact our support team.
                </p>
            </div>
        </div>
        ";
        
        // Generate PDF invoice
        try {
            $pdf = PDF::loadView('admin.billing-schedules.invoice-pdf', compact('invoice'));
            $pdf->setPaper('a4', 'portrait');
            
            // Generate PDF as string (binary data)
            $pdfOutput = $pdf->output();
            
            // Prepare attachment
            $filename = 'Invoice_' . $invoice->invoice_number . '.pdf';
            $attachments = [
                [
                    'data' => $pdfOutput,
                    'name' => $filename,
                    'mime' => 'application/pdf'
                ]
            ];
            
            // Send email with PDF attachment using NotificationService
            $sent = $notificationService->sendEmailNotification(
                $invoice->customer,
                $subject,
                $message,
                'billing',
                $attachments
            );
            
            if ($sent) {
                return redirect()->route('admin.billing-schedules.index')
                    ->with('success', 'Invoice notification with PDF attachment sent successfully to ' . $invoice->customer->email);
            } else {
                return redirect()->route('admin.billing-schedules.index')
                    ->with('error', 'Failed to send email notification. Please check customer email preferences.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to generate or send invoice PDF', [
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.billing-schedules.index')
                ->with('error', 'Failed to generate or send invoice. Error: ' . $e->getMessage());
        }
    }
    
    public function downloadPdf(Invoice $invoice)
    {
        // Load necessary relationships
        $invoice->load(['customer', 'payments', 'subscription.servicePlan']);
        
        // Generate PDF from the invoice template
        $pdf = PDF::loadView('admin.billing-schedules.invoice-pdf', compact('invoice'));
        
        // Set PDF options
        $pdf->setPaper('a4', 'portrait');
        
        // Generate filename with invoice number and status
        $filename = 'Invoice_' . $invoice->invoice_number . '_' . ucfirst($invoice->status) . '.pdf';
        
        // Download the PDF
        return $pdf->download($filename);
    }

    public function exportSummary(Request $request)
    {
        // Date filtering - same as index
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        // Get export options
        $includeStatistics = $request->input('include_statistics', '1') === '1';
        $includeDetails = $request->input('include_details', '1') === '1';
        $exportPaid = $request->input('export_paid', '1') === '1';
        $exportUnpaid = $request->input('export_unpaid', '1') === '1';
        $exportOverdue = $request->input('export_overdue', '1') === '1';
        
        // Build query for invoices with customer relationship
        $query = Invoice::with(['customer', 'payments', 'subscription.servicePlan'])
            ->whereBetween('due_date', [$startDate, $endDate]);
        
        // Apply custom status filters from export options
        if (!$exportPaid || !$exportUnpaid || !$exportOverdue) {
            $query->where(function($q) use ($exportPaid, $exportUnpaid, $exportOverdue) {
                if ($exportPaid) {
                    $q->orWhere('status', 'paid');
                }
                if ($exportUnpaid || $exportOverdue) {
                    $q->orWhere(function($subQuery) use ($exportUnpaid, $exportOverdue) {
                        $subQuery->where('status', 'unpaid');
                        if ($exportOverdue && !$exportUnpaid) {
                            // Only overdue
                            $subQuery->where('due_date', '<', now());
                        } elseif ($exportUnpaid && !$exportOverdue) {
                            // Only unpaid but not overdue
                            $subQuery->where('due_date', '>=', now());
                        }
                        // If both are selected, include all unpaid
                    });
                }
            });
        }
        
        $invoices = $query->orderBy('due_date', 'asc')->get();
        
        // Calculate statistics
        $paidCount = $invoices->where('status', 'paid')->count();
        $unpaidCount = $invoices->where('status', 'unpaid')->count();
        $overdueCount = $invoices->where('status', 'unpaid')
            ->filter(function($invoice) {
                return Carbon::parse($invoice->due_date)->isPast();
            })->count();
        
        $paidAmount = $invoices->where('status', 'paid')->sum('amount');
        $unpaidAmount = $invoices->where('status', 'unpaid')->sum('amount');
        $overdueAmount = $invoices->where('status', 'unpaid')
            ->filter(function($invoice) {
                return Carbon::parse($invoice->due_date)->isPast();
            })->sum('amount');
        
        // Generate CSV content
        $csv = "Billing Schedules Summary Report\n";
        $csv .= "Generated: " . now()->format('F d, Y H:i:s') . "\n";
        $csv .= "Period: " . Carbon::parse($startDate)->format('F d, Y') . " - " . Carbon::parse($endDate)->format('F d, Y') . "\n";
        
        // Add filter information
        $filters = [];
        if ($exportPaid) $filters[] = 'Paid';
        if ($exportUnpaid) $filters[] = 'Unpaid';
        if ($exportOverdue) $filters[] = 'Overdue';
        $csv .= "Status Filter: " . implode(', ', $filters) . "\n";
        $csv .= "\n";
        
        // Summary Statistics (if included)
        if ($includeStatistics) {
            $csv .= "SUMMARY STATISTICS\n";
            $csv .= "Status,Count,Total Amount\n";
            if ($exportPaid) {
                $csv .= "Paid Invoices," . $paidCount . ",₱" . number_format($paidAmount, 2) . "\n";
            }
            if ($exportUnpaid) {
                $csv .= "Unpaid Invoices," . $unpaidCount . ",₱" . number_format($unpaidAmount, 2) . "\n";
            }
            if ($exportOverdue) {
                $csv .= "Overdue Invoices," . $overdueCount . ",₱" . number_format($overdueAmount, 2) . "\n";
            }
            $csv .= "Total Invoices," . $invoices->count() . ",₱" . number_format($invoices->sum('amount'), 2) . "\n";
            $csv .= "\n";
        }
        
        // Detailed Invoice List (if included)
        if ($includeDetails) {
            $csv .= "DETAILED INVOICE LIST\n";
            $csv .= "Invoice Number,Customer Name,Customer Email,Account Number,Amount,Due Date,Status,Service Plan,Payment Date,Days Overdue\n";
            
            foreach ($invoices as $invoice) {
                $status = $invoice->status;
                $isOverdue = false;
                $daysOverdue = 0;
                
                if ($status === 'unpaid' && Carbon::parse($invoice->due_date)->isPast()) {
                    $status = 'Overdue';
                    $isOverdue = true;
                    $daysOverdue = Carbon::parse($invoice->due_date)->diffInDays(now());
                } else {
                    $status = ucfirst($status);
                }
                
                $paymentDate = '';
                if ($invoice->status === 'paid') {
                    $payment = $invoice->payments()->where('status', 'paid')->first();
                    $paymentDate = $payment ? Carbon::parse($payment->payment_date)->format('Y-m-d') : '';
                }
                
                $servicePlan = $invoice->subscription && $invoice->subscription->servicePlan 
                    ? $invoice->subscription->servicePlan->name 
                    : 'N/A';
                
                $csv .= '"' . $invoice->invoice_number . '",';
                $csv .= '"' . $invoice->customer->name . '",';
                $csv .= '"' . $invoice->customer->email . '",';
                $csv .= '"' . $invoice->customer->account_number . '",';
                $csv .= '"₱' . number_format($invoice->amount, 2) . '",';
                $csv .= '"' . Carbon::parse($invoice->due_date)->format('Y-m-d') . '",';
                $csv .= '"' . $status . '",';
                $csv .= '"' . $servicePlan . '",';
                $csv .= '"' . $paymentDate . '",';
                $csv .= $isOverdue ? $daysOverdue : '0';
                $csv .= "\n";
            }
        }
        
        // Return CSV file
        $filterSuffix = implode('_', array_map('strtolower', $filters));
        $filename = 'billing_schedules_' . $filterSuffix . '_' . Carbon::parse($startDate)->format('Y-m-d') . '_to_' . Carbon::parse($endDate)->format('Y-m-d') . '.csv';
        
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function exportCalendar(Request $request)
    {
        // Date filtering
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $statusFilter = $request->input('status', 'all');
        
        // Build query for unpaid invoices (paid invoices don't need calendar reminders)
        $query = Invoice::with(['customer', 'subscription.servicePlan'])
            ->where('status', 'unpaid')
            ->whereBetween('due_date', [$startDate, $endDate]);
        
        if ($statusFilter === 'overdue') {
            $query->where('due_date', '<', now());
        }
        
        $invoices = $query->orderBy('due_date', 'asc')->get();
        
        // Generate ICS (iCalendar) content
        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//ISP Management System//Billing Schedules//EN\r\n";
        $ics .= "CALSCALE:GREGORIAN\r\n";
        $ics .= "METHOD:PUBLISH\r\n";
        $ics .= "X-WR-CALNAME:ISP Billing Due Dates\r\n";
        $ics .= "X-WR-TIMEZONE:Asia/Manila\r\n";
        
        foreach ($invoices as $invoice) {
            $dueDate = Carbon::parse($invoice->due_date);
            $isOverdue = $dueDate->isPast();
            
            $status = $isOverdue ? 'OVERDUE' : 'UNPAID';
            $servicePlan = $invoice->subscription && $invoice->subscription->servicePlan 
                ? $invoice->subscription->servicePlan->name 
                : 'N/A';
            
            // Create a unique ID for this event
            $uid = 'invoice-' . $invoice->id . '-' . time() . '@isp-management-system';
            
            // Event dates (all-day event format)
            $dueDateFormatted = $dueDate->format('Ymd');
            
            $ics .= "BEGIN:VEVENT\r\n";
            $ics .= "UID:" . $uid . "\r\n";
            $ics .= "DTSTAMP:" . now()->format('Ymd\THis\Z') . "\r\n";
            $ics .= "DTSTART;VALUE=DATE:" . $dueDateFormatted . "\r\n";
            $ics .= "SUMMARY:" . $status . " - Invoice #" . $invoice->invoice_number . " - " . $invoice->customer->name . "\r\n";
            $ics .= "DESCRIPTION:Payment Due for " . $invoice->customer->name . "\\n";
            $ics .= "Invoice: " . $invoice->invoice_number . "\\n";
            $ics .= "Amount: ₱" . number_format($invoice->amount, 2) . "\\n";
            $ics .= "Service Plan: " . $servicePlan . "\\n";
            $ics .= "Status: " . $status . "\\n";
            $ics .= "Customer Email: " . $invoice->customer->email . "\r\n";
            $ics .= "LOCATION:ISP Management System\r\n";
            $ics .= "STATUS:CONFIRMED\r\n";
            
            // Set priority based on status
            if ($isOverdue) {
                $ics .= "PRIORITY:1\r\n"; // High priority for overdue
                $ics .= "X-MICROSOFT-CDO-IMPORTANCE:2\r\n";
            } else {
                $ics .= "PRIORITY:5\r\n"; // Normal priority
            }
            
            // Add alarm/reminder 1 day before due date
            $ics .= "BEGIN:VALARM\r\n";
            $ics .= "TRIGGER:-P1D\r\n";
            $ics .= "ACTION:DISPLAY\r\n";
            $ics .= "DESCRIPTION:Payment due tomorrow for " . $invoice->customer->name . " - ₱" . number_format($invoice->amount, 2) . "\r\n";
            $ics .= "END:VALARM\r\n";
            
            $ics .= "END:VEVENT\r\n";
        }
        
        $ics .= "END:VCALENDAR\r\n";
        
        // Return ICS file
        $filename = 'billing_due_dates_' . Carbon::parse($startDate)->format('Y-m-d') . '_to_' . Carbon::parse($endDate)->format('Y-m-d') . '.ics';
        
        return response($ics)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
