<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - {{ $payment->id }}</title>
    <style>
        @page {
            margin: 20px;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2c5aa0;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 5px;
        }
        .receipt-title {
            font-size: 28px;
            font-weight: bold;
            color: #28a745;
            margin-top: 15px;
            letter-spacing: 2px;
        }
        .receipt-badge {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 8px 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-row {
            margin-bottom: 20px;
            overflow: hidden;
        }
        .info-left {
            float: left;
            width: 50%;
        }
        .info-right {
            float: right;
            width: 45%;
            text-align: right;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 11px;
            margin-bottom: 5px;
        }
        .info-value {
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }
        .receipt-number {
            font-size: 16px;
            font-weight: bold;
            color: #2c5aa0;
        }
        .amount-section {
            background-color: #f0f8ff;
            border: 2px solid #2c5aa0;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }
        .amount-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .amount-value {
            font-size: 36px;
            font-weight: bold;
            color: #2c5aa0;
            margin: 10px 0;
        }
        .amount-words {
            font-size: 12px;
            color: #666;
            font-style: italic;
            margin-top: 10px;
        }
        .details-section {
            margin: 30px 0;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 2px solid #e0e0e0;
        }
        .details-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .details-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .details-table td.label {
            width: 40%;
            color: #666;
            font-weight: 600;
        }
        .details-table td.value {
            width: 60%;
            color: #333;
        }
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .invoice-table th {
            background-color: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #dee2e6;
            font-size: 11px;
        }
        .invoice-table td {
            padding: 10px 12px;
            border: 1px solid #dee2e6;
        }
        .invoice-table td.right {
            text-align: right;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-paid {
            background-color: #d4edda;
            color: #155724;
        }
        .thank-you-box {
            background-color: #fff9e6;
            border-left: 4px solid #28a745;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }
        .thank-you-box h3 {
            margin: 0 0 10px 0;
            color: #28a745;
            font-size: 18px;
        }
        .thank-you-box p {
            margin: 5px 0;
            color: #666;
            font-size: 12px;
        }
        .signature-section {
            margin-top: 50px;
            overflow: hidden;
        }
        .signature-box {
            float: left;
            width: 45%;
            text-align: center;
            padding-top: 30px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 11px;
            color: #666;
        }
        .footer {
            margin-top: 60px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #e0e0e0;
            padding-top: 15px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(40, 167, 69, 0.05);
            font-weight: bold;
            z-index: -1;
        }
        .contact-info {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .qr-placeholder {
            width: 100px;
            height: 100px;
            border: 2px dashed #ccc;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="watermark">PAID</div>

    <div class="header">
        <div class="company-name">ISP MANAGEMENT SYSTEM</div>
        <div style="color: #666; font-size: 11px;">Internet Service Provider</div>
        <div class="receipt-title">RECEIPT</div>
        <div class="receipt-badge">PAYMENT RECEIVED</div>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-left">
                <div class="info-label">RECEIPT NUMBER:</div>
                <div class="info-value receipt-number">RCP-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</div>
            </div>
            <div class="info-right">
                <div class="info-label">DATE:</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($payment->payment_date)->format('F j, Y') }}</div>
            </div>
        </div>

        <div class="info-row">
            <div class="info-left">
                <div class="info-label">RECEIVED FROM:</div>
                <div class="info-value">
                    <strong>{{ $payment->customer->name }}</strong><br>
                    {{ $payment->customer->email }}<br>
                    {{ $payment->customer->phone }}<br>
                    @if($payment->customer->address)
                    {{ $payment->customer->address }}
                    @endif
                </div>
            </div>
            <div class="info-right">
                <div class="info-label">PAYMENT METHOD:</div>
                <div class="info-value">{{ ucfirst($payment->payment_method ?? 'Cash') }}</div>
                @if($payment->reference_number)
                <div class="info-label" style="margin-top: 10px;">REFERENCE NUMBER:</div>
                <div class="info-value">{{ $payment->reference_number }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="amount-section">
        <div class="amount-label">AMOUNT PAID</div>
        <div class="amount-value">PHP {{ number_format($payment->amount, 2) }}</div>
        <div class="amount-words">
            {{ ucwords(\NumberFormatter::create('en', \NumberFormatter::SPELLOUT)->format($payment->amount)) }} Pesos
            @if(($payment->amount - floor($payment->amount)) > 0)
            and {{ str_pad(round(($payment->amount - floor($payment->amount)) * 100), 2, '0', STR_PAD_LEFT) }}/100
            @else
            Only
            @endif
        </div>
    </div>

    <div class="details-section">
        <div class="section-title">PAYMENT DETAILS</div>
        <table class="details-table">
            <tr>
                <td class="label">Payment Status:</td>
                <td class="value">
                    <span class="status-badge status-paid">{{ strtoupper($payment->status) }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">Customer ID:</td>
                <td class="value">CUST-{{ str_pad($payment->customer->id, 5, '0', STR_PAD_LEFT) }}</td>
            </tr>
            @if($payment->customer->subscriptions->first())
            <tr>
                <td class="label">Service Plan:</td>
                <td class="value">{{ $payment->customer->subscriptions->first()->servicePlan->name ?? 'N/A' }}</td>
            </tr>
            @endif
            @if($payment->notes)
            <tr>
                <td class="label">Notes:</td>
                <td class="value">{{ $payment->notes }}</td>
            </tr>
            @endif
        </table>
    </div>

    @if($payment->invoice_id && $payment->invoice)
    <div class="details-section">
        <div class="section-title">INVOICE INFORMATION</div>
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Invoice Date</th>
                    <th>Due Date</th>
                    <th class="right">Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>INV-{{ str_pad($payment->invoice->id, 6, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->invoice->invoice_date)->format('M j, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($payment->invoice->due_date)->format('M j, Y') }}</td>
                    <td class="right">PHP {{ number_format($payment->invoice->amount, 2) }}</td>
                    <td><span class="status-badge status-{{ $payment->invoice->status }}">{{ strtoupper($payment->invoice->status) }}</span></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="thank-you-box">
        <h3>Thank You for Your Payment!</h3>
        <p>We appreciate your prompt payment and continued business.</p>
        <p>Your account has been credited with this payment.</p>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                Received By<br>
                {{ auth()->user()->name ?? 'Authorized Personnel' }}
            </div>
        </div>
        <div class="signature-box" style="float: right;">
            <div class="signature-line">
                Customer Signature
            </div>
        </div>
    </div>

    <div class="contact-info">
        <p><strong>ISP Management System</strong></p>
        <p>For inquiries, please contact our support team</p>
        <p>Email: eyodii01@gmail.com | Website: www.ispmanagement.com</p>
    </div>

    <div class="footer">
        <p><strong>OFFICIAL RECEIPT</strong></p>
        <p>This is a computer-generated receipt and serves as proof of payment.</p>
        <p style="margin-top: 10px;">Generated on {{ \Carbon\Carbon::now()->format('F j, Y g:i A') }}</p>
        <p style="margin-top: 5px;">Receipt ID: RCP-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>
</body>
</html>
