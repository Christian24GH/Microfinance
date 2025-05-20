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
                @php
                    $orders = DB::table('order as o')
                        ->leftJoin('shipment as s', 's.order_id', '=', 'o.order_id')
                        ->whereNull('s.order_id')
                        ->select('o.*') // or specify which fields you need
                        ->get();
                @endphp
                <option value="">-- Select Order --</option>
                @foreach($orders as $order)
                    <option value="{{ $order->order_id }}">Order #{{ $order->order_id }} - {{ $order->order_date }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="warehouse_id" class="form-label">Warehouse</label>
            @php
                $warehouses = DB::table('warehouse')->get();
            @endphp
            <select name="warehouse_id" id="warehouse_id" class="form-select" required>
                <option value="">-- Select Warehouse --</option>
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
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
                <th>Warehouse</th>
                <th>Ship Date</th>
                <th>Carrier</th>
                <th>Tracking No</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
            <form id="updateForm" action="{{route('shipment.update', ['id'=>$shipment->shipment_id])}}" method="post">
                @csrf
                <tr>
                <td>{{ $shipment->shipment_id }}</td>
                <td>{{ $shipment->order_id }}</td>
                <td>{{ $shipment->warehouse }}</td>
                <td>{{ $shipment->ship_date }}</td>
                <td>{{ $shipment->carrier }}</td>
                <td>{{ $shipment->tracking_no }}</td>
                <td>{{ $shipment->delivery_status }}</td>
                @if(!($shipment->delivery_status == 'delivered' || $shipment->delivery_status == 'cancelled'))
                <td>
                    <button class="btn btn-primary btn-sm" name="status" value="delivered" type="submit">Mark as Delivered</button>
                    <button class="btn btn-danger btn-sm" name="status" value="cancelled" type="submit">Mark as Cancelled</button>
                </td>
                @endif
                </tr>
            </form>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
