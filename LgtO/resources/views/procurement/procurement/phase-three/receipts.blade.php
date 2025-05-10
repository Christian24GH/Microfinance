@extends('layout.default')
@section('content_pagination')
<nav class="pages ms-1 mt-1">
    <div class="d-flex border-0 rounded-top-3">
        <div class="bg "><a href="{{route('prc.invoice.index')}}"><p class="lato-bold m-0">Invoice</p></a></div>
        <div class="bg active"><a><p class="lato-bold m-0">Receipts</p></a></div>
    </div>
</nav>
@endsection
@section('content')
<div class="container-fluid">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Receipt No.</th>
                <th>Payment Date</th>
                <th>Amount Paid</th>
                <th>Invoice Amount</th>
                <th>Payment Status</th>
                <th>Procurement Subject</th>
                <th>Offer Price</th>
                <th>Request No.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipts as $receipt)
            <tr>
                <td>{{ $receipt->receipt_number }}</td>
                <td>{{ $receipt->payment_date }}</td>
                <td>{{ number_format($receipt->amount, 2) }}</td>
                <td>{{ number_format($receipt->invoice_amount, 2) }}</td>
                <td>
                    {{ $receipt->payment_status }}
                </td>
                <td>{{ $receipt->subject }}</td>
                <td>{{ number_format($receipt->offer_price, 2) }}</td>
                <td>{{ $receipt->request_number }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection