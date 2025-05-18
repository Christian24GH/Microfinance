@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Shipment</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-warning" role="alert">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif
    <form action="{{ route('shipment.store') }}" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="order_id" class="form-label">Order</label>
            <select name="order_id" id="order_id" class="form-select" required>
                <option value="">-- Select Order --</option>
                @foreach($order as $order)
                    <option value="{{ $order->order_id }}">Order #{{ $order->order_id }} - {{ $order->order_date }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="ship_date" class="form-label">Ship Date</label>
            <input type="date" name="ship_date" id="ship_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="carrier" class="form-label">Carrier</label>
            <input type="text" name="carrier" id="carrier" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tracking_no" class="form-label">Tracking Number</label>
            <input type="text" name="tracking_no" id="tracking_no" class="form-control">
        </div>

        <div class="mb-3">
            <label for="delivery_status" class="form-label">Delivery Status</label>
            <select name="delivery_status" id="delivery_status" class="form-select">
                <option value="Pending">Pending</option>
                <option value="Shipped">Shipped</option>
                <option value="Delivered">Delivered</option>
                <option value="Delayed">Delayed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Shipment</button>
    </form>

    <h4>Shipment List</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Ship Date</th>
                <th>Carrier</th>
                <th>Tracking No</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipment as $shipment)
            <tr>
                <td>{{ $shipment->shipment_id }}</td>
                <td>{{ $shipment->order_id }}</td>
                <td>{{ $shipment->ship_date }}</td>
                <td>{{ $shipment->carrier }}</td>
                <td>{{ $shipment->tracking_no }}</td>
                <td>{{ $shipment->delivery_status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
