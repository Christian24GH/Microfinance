@extends('layout/default')

@section('content')
<div class="container mt-4 min-vh-100">
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
            $shipments = DB::table('shipment as s')
                ->leftJoin('inventory as i', 's.shipment_id', '=', 'i.shipment_id')
                ->whereNull('i.shipment_id')
                ->where('s.delivery_status', 'delivered')
                ->get('s.*');
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
            </tr>
        </thead>
        <tbody>
            @foreach($inventory as $inv)
            <tr>
                <td>{{ $inv->inventory_id }}</td>
                <td>{{ $inv->tracking_no ?? 'N/A' }}</td>
                <td>{{ $inv->warehouse_name ?? 'N/A' }}</td>
                <td>{{ $inv->item_name }}</td>
                <td>{{ $inv->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
