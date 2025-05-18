@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Inventory</h2>

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
    <form action="{{ route('inventory.store') }}" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf
        @php
            $shipments = DB::table('shipment')->get();
        @endphp
        <div class="mb-3">
            <label for="shipment_id" class="form-label">Shipment</label>
            <select name="shipment_id" id="shipment_id" class="form-select" required>
                <option value="">-- Select Shipment --</option>
                @foreach($shipments as $shipment)
                    <option value="{{ $shipment->shipment_id }}">{{ $shipment->tracking_no }}</option>
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
            <label for="item_name" class="form-label">Item Name</label>
            <input type="text" name="item_name" id="item_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="received_date" class="form-label">Received Date</label>
            <input type="date" name="received_date" id="received_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="storage_condition" class="form-label">Storage Condition</label>
            <input type="text" name="storage_condition" id="storage_condition" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Add Inventory</button>
    </form>

    <h4>Inventory List</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Shipment</th>
                <th>Warehouse</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Received</th>
                <th>Condition</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory as $inv)
            <tr>
                <td>{{ $inv->inventory_id }}</td>
                <td>{{ $inv->shipment->tracking_no ?? 'N/A' }}</td>
                <td>{{ $inv->warehouse->name ?? 'N/A' }}</td>
                <td>{{ $inv->item_name }}</td>
                <td>{{ $inv->quantity }}</td>
                <td>{{ $inv->received_date }}</td>
                <td>{{ $inv->storage_condition }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
