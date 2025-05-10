@extends('layout.default')
@section('modal')
@endsection
@section('content_pagination')
<nav class="pages ms-1 mt-1">
    <div class="d-flex border-0 rounded-top-3">
        <div class="bg active"><a ><p class="lato-bold m-0">Invoice</p></a></div>
        <div class="bg "><a  href="{{route('prc.receipt.index')}}"><p class="lato-bold m-0">Receipts</p></a></div>
    </div>
</nav>
@endsection
@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Amount</th>
                <th>Payment Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($invoices as $invoice)
             <tr>
                <td>{{ $loop->iteration }}</td>
                <td>₱{{ number_format($invoice->invoice_amount, 2) }}</td>
                <td>{{ $invoice->payment_status }}</td>
                <td class="d-flex align-items-center gap-1 ">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#invoiceModal{{ $invoice->id }}">
                        View
                    </button>
                    @if($invoice->invoice_status === "Approved" && $invoice->payment_status === "Unpaid")
                    <form action="{{route('prc.invoice.markAsPaid', ['id'=>$invoice->id])}}" method="post">
                        @csrf
                        <button class="btn btn-sm btn-primary">Mark as Paid</button>
                    </form>
                @endif
                </td>
            </tr>
            <div class="modal fade" id="invoiceModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="invoiceModalLabel{{ $invoice->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel{{ $invoice->id }}">Invoice Details — #{{ $invoice->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                    
                    <h6>Procurement Request:</h6>
                    <ul>
                        <li><strong>Request Number:</strong> {{ $invoice->request_number }}</li>
                        <li><strong>Subject:</strong> {{ $invoice->subject }}</li>
                        <li><strong>Description:</strong> {{ $invoice->description }}</li>
                        <li><strong>Type:</strong> {{ $invoice->subject_type }}</li>
                        <li><strong>Quantity:</strong> {{ $invoice->quantity }} {{ $invoice->unit }}</li>
                        <li><strong>Due Date:</strong> {{ $invoice->request_due_date }}</li>
                    </ul>

                    <h6 class="mt-3">Bid Details:</h6>
                    <ul>
                        <li><strong>Offer Price:</strong> ₱{{ number_format($invoice->bid_offer_price, 2) }}</li>
                        <li><strong>Status:</strong> {{ $invoice->bid_status }}</li>
                        <li><strong>Agreement:</strong> {{ $invoice->agreement_text }}</li>
                    </ul>

                    <h6 class="mt-3">Invoice Info:</h6>
                    <ul>
                        <li><strong>Amount:</strong> ₱{{ number_format($invoice->invoice_amount, 2) }}</li>
                        <li><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</li>
                        <li><strong>Due Date:</strong> {{ $invoice->due_date }}</li>
                        <li><strong>Status:</strong> {{ $invoice->invoice_status }}</li>
                        <li><strong>Payment Status:</strong> {{ $invoice->payment_status }}</li>
                    </ul>

                    </div>
                    <div class="modal-footer">
                        @if($invoice->invoice_status !== "Closed")
                        <form action="{{ route('prc.invoice.update', $invoice->id) }}" method="POST" class="d-flex align-items-center gap-2">
                            @csrf
                            <select name="invoice_status" class="form-select form-select-sm w-auto" required>
                                @foreach(['Pending', 'Sent', 'Received', 'Approved', 'Disputed'] as $status)
                                    <option value="{{ $status }}" {{ $invoice->invoice_status == $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">Update</button>
                        </form>
                        @endif
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        @empty
            <tr><td class="text-center" colspan="7"><b>No Data Found</b> </td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection