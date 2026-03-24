@extends('layouts.app')

@section('title', 'Client/User Payments')

@section('content')
<style>
    .payments-container {
        max-width: 1400px;
        margin: 2rem auto;
        padding: 0 2rem;
    }

    .page-header {
        background: linear-gradient(135deg, #D4AF76, #C19A6B);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(196, 154, 107, 0.3);
    }

    .page-header h1 {
        color: #FFFFFF;
        font-family: 'Poppins', sans-serif;
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .page-header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
    }

    .filter-section {
        background: #FFFFFF;
        border: 2px solid #E8D5C4;
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(232, 213, 196, 0.2);
    }

    .filter-form {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: end;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
    }

    .filter-group label {
        display: block;
        color: #8B6F47;
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .filter-group input,
    .filter-group select {
        width: 100%;
        padding: 0.75rem;
        background: #FFFAF0;
        border: 2px solid #E8D5C4;
        border-radius: 10px;
        color: #333333;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .filter-group input:focus,
    .filter-group select:focus {
        border-color: #D4AF76;
        outline: none;
        box-shadow: 0 0 0 3px rgba(212, 175, 118, 0.1);
    }

    .filter-btn {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #D4AF76, #C19A6B);
        color: #FFFFFF;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 2px 10px rgba(196, 154, 107, 0.3);
    }

    .filter-btn:hover {
        background: linear-gradient(135deg, #C19A6B, #AA8352);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(196, 154, 107, 0.4);
    }

    .payment-table-container {
        background: #FFFFFF;
        border: 2px solid #E8D5C4;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(232, 213, 196, 0.2);
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
    }

    .payment-table thead {
        background: linear-gradient(135deg, #FFFAF0, #FFF5E6);
        border-bottom: 2px solid #E8D5C4;
    }

    .payment-table th {
        padding: 1rem;
        text-align: left;
        color: #8B6F47;
        font-weight: 700;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .payment-table tbody tr {
        border-bottom: 1px solid #F5E6D3;
        transition: background 0.2s;
    }

    .payment-table tbody tr:hover {
        background: #FFFAF0;
    }

    .payment-table td {
        padding: 1rem;
        color: #333333;
        font-size: 0.95rem;
    }

    .client-name {
        font-weight: 600;
        color: #8B6F47;
    }

    .status-badge {
        display: inline-block;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #FFF4E6;
        color: #F59E0B;
        border: 1px solid #FCD34D;
    }

    .status-paid {
        background: #ECFDF5;
        color: #10B981;
        border: 1px solid #6EE7B7;
    }

    .status-failed {
        background: #FEF2F2;
        color: #EF4444;
        border: 1px solid #FCA5A5;
    }

    .status-overdue {
        background: #FEF2F2;
        color: #DC2626;
        border: 1px solid #FCA5A5;
    }

    .amount {
        font-weight: 700;
        color: #059669;
        font-size: 1.05rem;
    }

    .edit-btn {
        padding: 0.5rem 1.25rem;
        background: #D4AF76;
        color: #FFFFFF;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
        font-size: 0.9rem;
    }

    .edit-btn:hover {
        background: #C19A6B;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(196, 154, 107, 0.3);
    }

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
        margin: 5% auto;
        padding: 0;
        border: 2px solid #E8D5C4;
        border-radius: 20px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: slideDown 0.3s;
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
        background: linear-gradient(135deg, #D4AF76, #C19A6B);
        padding: 1.5rem 2rem;
        border-radius: 18px 18px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        color: #FFFFFF;
        font-family: 'Poppins', sans-serif;
        margin: 0;
        font-size: 1.5rem;
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

    .alert {
        padding: 1rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        font-weight: 500;
    }

    .alert-success {
        background: #ECFDF5;
        color: #10B981;
        border: 1px solid #6EE7B7;
    }

    .alert-error {
        background: #FEF2F2;
        color: #EF4444;
        border: 1px solid #FCA5A5;
    }

    .pagination-container {
        padding: 1.5rem;
        display: flex;
        justify-content: center;
    }

    .empty-state {
        text-align: center;
        padding: 3rem;
        color: #9A826C;
    }

    .empty-state svg {
        width: 80px;
        height: 80px;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    /* Note about limited access */
    .access-notice {
        background: #FFF4E6;
        border: 2px solid #FCD34D;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        margin-bottom: 2rem;
        color: #92400E;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .access-notice svg {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
    }
</style>

<div class="payments-container">
    <!-- Page Header -->
    <div class="page-header">
        <h1>� Client/User Payment Management</h1>
        <p>View client information and manage their payment records</p>
    </div>

    <!-- Limited Access Notice -->
    <div class="access-notice">
        <svg fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
        </svg>
        <span><strong>Limited Access Mode:</strong> This is a client/user payment page. You can view client information and edit payment records, but client deletion is restricted.</span>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <!-- Filter Section -->
    <div class="filter-section">
        <form method="GET" action="{{ route('payments.index') }}" class="filter-form">
            <div class="filter-group">
                <label for="search">Search Client</label>
                <input type="text" id="search" name="search" placeholder="Enter client name..." value="{{ request('search') }}">
            </div>
            <div class="filter-group">
                <label for="status">Payment Status</label>
                <select id="status" name="status">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Overdue</option>
                </select>
            </div>
            <button type="submit" class="filter-btn">🔍 Filter</button>
        </form>
    </div>

    <!-- Payment Table -->
    <div class="payment-table-container">
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Client/User Name</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Payment Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr id="payment-row-{{ $payment->id }}">
                    <td class="client-name">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20" style="color: #D4AF76;">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            {{ $payment->user->name }}
                        </div>
                    </td>
                    <td style="color: #6B7280; font-size: 0.9rem;">{{ $payment->user->email }}</td>
                    <td class="amount">${{ number_format($payment->amount, 2) }}</td>
                    <td>
                        <span class="status-badge status-{{ $payment->status }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : 'N/A' }}</td>
                    <td>
                        <button class="edit-btn" onclick="openEditModal({{ $payment->id }})">✏️ Edit</button>
                        <!-- Note: No delete button - Limited Access Mode -->
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="empty-state">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                        <p>No client payment records found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($payments->hasPages())
        <div class="pagination-container">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Payment Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Payment</h2>
            <button class="close-btn" onclick="closeEditModal()">&times;</button>
        </div>/User Name (Read-Only)</label>
                    <input type="text" id="modal-client-name" readonly style="background: #F5F5F5; cursor: not-allowed;">
                    <small style="color: #6B7280; font-size: 0.85rem; display: block; margin-top: 0.25rem;">
                        Client information cannot be modified from this page
                    </small
            <div id="modalAlert"></div>
            
            <form id="editPaymentForm">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label>Client Name (Read-Only)</label>
                    <input type="text" id="modal-client-name" readonly style="background: #F5F5F5; cursor: not-allowed;">
                </div>

                <div class="form-group">
                    <label>Amount *</label>
                    <input type="number" step="0.01" id="modal-amount" name="amount" required>
                </div>

                <div class="form-group">
                    <label>Status *</label>
                    <select id="modal-status" name="status" required>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Payment Date</label>
                    <input type="date" id="modal-payment-date" name="payment_date">
                </div>

                <div class="form-group">
                    <label>Notes</label>
                    <textarea id="modal-notes" name="notes" rows="3" style="resize: vertical;"></textarea>
                </div>

                <button type="submit" class="filter-btn" style="width: 100%; margin-top: 1rem;">
                    💾 Update Payment
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let currentPaymentId = null;

    function openEditModal(paymentId) {
        currentPaymentId = paymentId;
        document.getElementById('editModal').style.display = 'block';
        document.getElementById('modalAlert').innerHTML = '';
        
        // Fetch payment data
        fetch(`/payments/${paymentId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('modal-client-name').value = data.client_name;
            document.getElementById('modal-amount').value = data.amount;
            document.getElementById('modal-status').value = data.status;
            document.getElementById('modal-payment-date').value = data.payment_date || '';
            document.getElementById('modal-notes').value = data.notes || '';
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalAlert').innerHTML = 
                '<div class="alert alert-error">Failed to load payment data</div>';
        });
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        currentPaymentId = null;
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    }

    // Handle form submission
    document.getElementById('editPaymentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch(`/payments/${currentPaymentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 2].innerHTML = `<span class="amount">$${result.payment.amount}</span>`;
                    cells[3].innerHTML = `<span class="status-badge status-${data.status}">${result.payment.status}</span>`;
                    cells[4
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Update the table row
                const row = document.getElementById(`payment-row-${currentPaymentId}`);
                if (row) {
                    const cells = row.getElementsByTagName('td');
                    cells[1].innerHTML = `<span class="amount">$${result.payment.amount}</span>`;
                    cells[2].innerHTML = `<span class="status-badge status-${data.status}">${result.payment.status}</span>`;
                    cells[3].textContent = result.payment.payment_date;
                }
                
                document.getElementById('modalAlert').innerHTML = 
                    '<div class="alert alert-success">Payment updated successfully!</div>';
                
                setTimeout(() => {
                    closeEditModal();
                    location.reload(); // Refresh to show updated data
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('modalAlert').innerHTML = 
                '<div class="alert alert-error">Failed to update payment. Please try again.</div>';
        });
    });
</script>
@endsection