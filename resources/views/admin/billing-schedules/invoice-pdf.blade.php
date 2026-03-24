<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            color: #000;
        }
        .info-section {
            margin-bottom: 20px;
            width: 48%;
            float: left;
        }
        .info-section.right {
            float: right;
        }
        .clear {
            clear: both;
        }
        .info-label {
            font-weight: bold;
            color: #000;
            margin-bottom: 8px;
            font-size: 11px;
        }
        .info-value {
            color: #333;
            line-height: 1.8;
        }
        .invoice-details {
            background-color: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
        }
        .detail-row {
            margin-bottom: 8px;
            padding: 3px 0;
        }
        .detail-label {
            font-weight: bold;
            display: inline-block;
            width: 140px;
        }
        .service-details {
            margin-top: 30px;
            background-color: #f9f9f9;
            padding: 15px;
            border: 1px solid #ddd;
        }
        .service-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
            font-size: 13px;
        }
        .amount-table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }
        .amount-table td {
            padding: 8px;
            text-align: right;
        }
        .amount-table .label {
            text-align: right;
            width: 70%;
            font-size: 13px;
        }
        .amount-table .value {
            text-align: right;
            width: 30%;
            font-size: 13px;
            font-weight: bold;
        }
        .total-row {
            border-top: 2px solid #333;
            font-size: 16px;
            font-weight: bold;
        }
        .paid-amount {
            color: #2d7a2d;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #333;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
            border-color: #155724;
        }
        .status-unpaid {
            background-color: #fff3cd;
            color: #856404;
            border-color: #856404;
        }
        .status-overdue {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #721c24;
        }
        .reminder-box {
            margin-top: 30px;
            padding: 15px;
            background-color: #fff9e6;
            border-left: 4px solid #cc8800;
        }
        .payment-history {
            margin-top: 30px;
        }
        .payment-history-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .payment-table th {
            padding: 10px;
            text-align: left;
            border: 1px solid #333;
            background-color: #e9e9e9;
            font-weight: bold;
        }
        .payment-table td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        .payment-table td.right {
            text-align: right;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">ISP MANAGEMENT SYSTEM</div>
        <div style="color: #666; font-size: 11px;">Internet Service Provider</div>
        <div class="invoice-title">INVOICE</div>
    </div>

    <div class="info-section">
        <div class="info-label">BILL TO:</div>
        <div class="info-value">
            <strong>{{ $invoice->customer->name }}</strong><br>
            @if($invoice->customer->address)
                {{ $invoice->customer->address }}<br>
            @endif
            @if($invoice->customer->phone)
                Phone: {{ $invoice->customer->phone }}<br>
            @endif
            @if($invoice->customer->email)
                Email: {{ $invoice->customer->email }}<br>
            @endif
            @if($invoice->customer->account_number)
                Account No: {{ $invoice->customer->account_number }}
            @endif
        </div>
    </div>

    <div class="info-section right">
        <div class="invoice-details">
            <div class="detail-row">
                <span class="detail-label">Invoice Number:</span>
                <strong>{{ $invoice->invoice_number }}</strong>
            </div>
            <div class="detail-row">
                <span class="detail-label">Invoice Date:</span>
                {{ \Carbon\Carbon::parse($invoice->created_at)->format('F j, Y') }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Due Date:</span>
                {{ \Carbon\Carbon::parse($invoice->due_date)->format('F j, Y') }}
            </div>
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                @if($invoice->status === 'paid')
                    <span class="status-badge status-paid">Paid</span>
                @elseif($invoice->isOverdue())
                    <span class="status-badge status-overdue">Overdue</span>
                @else
                    <span class="status-badge status-unpaid">Unpaid</span>
                @endif
            </div>
        </div>
    </div>

    <div class="clear"></div>

    @if($invoice->subscription)
    <div class="service-details">
        <div class="service-title">SERVICE DETAILS</div>
        <div class="detail-row">
            <span class="detail-label">Service Plan:</span>
            {{ $invoice->subscription->servicePlan->name ?? 'N/A' }}
        </div>
        @if($invoice->subscription->servicePlan)
        <div class="detail-row">
            <span class="detail-label">Plan Speed:</span>
            {{ $invoice->subscription->servicePlan->speed ?? 'N/A' }}
        </div>
        <div class="detail-row">
            <span class="detail-label">Bandwidth Limit:</span>
            {{ $invoice->subscription->servicePlan->bandwidth_limit ?? 'Unlimited' }}
        </div>
        @endif
        <div class="detail-row">
            <span class="detail-label">Billing Period:</span>
            {{ ucfirst($invoice->subscription->billing_cycle ?? 'monthly') }}
        </div>
    </div>
    @endif

    <table class="amount-table">
        <tr>
            <td class="label">Subtotal:</td>
            <td class="value">PHP {{ number_format($invoice->amount, 2) }}</td>
        </tr>
        @if($invoice->payments->where('status', 'paid')->count() > 0)
        <tr class="paid-amount">
            <td class="label">Paid Amount:</td>
            <td class="value">PHP {{ number_format($invoice->payments->where('status', 'paid')->sum('amount'), 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td class="label">TOTAL AMOUNT:</td>
            <td class="value">PHP {{ number_format($invoice->amount, 2) }}</td>
        </tr>
    </table>

    @if($invoice->status === 'unpaid')
    <div class="reminder-box">
        <strong>Payment Reminder:</strong> Please ensure payment is made by the due date to avoid service interruption.
    </div>
    @endif

    @if($invoice->payments->where('status', 'paid')->count() > 0)
    <div class="payment-history">
        <div class="payment-history-title">PAYMENT HISTORY</div>
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->payments->where('status', 'paid') as $payment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('M j, Y') }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td class="right">PHP {{ number_format($payment->amount, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>For questions about this invoice, please contact our support team.</p>
        <p style="margin-top: 10px;">Generated on {{ \Carbon\Carbon::now()->format('F j, Y g:i A') }}</p>
    </div>
</body>
</html>
