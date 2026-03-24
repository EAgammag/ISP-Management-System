@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Payment</h1>

    <form method="POST" action="{{ route('payments.update', $payment) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Amount</label>
            <input type="number" step="0.01" name="amount" value="{{ $payment->amount }}" required>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status" required>
                <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ $payment->status == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ $payment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                <option value="overdue" {{ $payment->status == 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
        </div>

        <div class="form-group">
            <label>Payment Date</label>
            <input type="date" name="payment_date" value="{{ $payment->payment_date }}" required>
        </div>

        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes">{{ $payment->notes }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection