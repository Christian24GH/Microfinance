@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Last Mile Delivery</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('lastmile_delivery.store') }}" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="shipment_id" class="form-label">Shipment</label>
            <select name="shipment_id" id="shipment_id" class="form-select" required>
                <option value="">-- Select Shipment --</option>
                @foreach($shipment as $shipment)
                    <option value="{{ $shipment->shipment_id }}">Shipment #{{ $shipment->shipment_id }} - {{ $shipment->carrier }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="delivery_person" class="form-label">Delivery Person</label>
            <input type="text" name="delivery_person" id="delivery_person" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="vehicle_no" class="form-label">Vehicle No</label>
            <input type="text" name="vehicle_no" id="vehicle_no" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="departure_time" class="form-label">Departure Time</label>
            <input type="datetime-local" name="departure_time" id="departure_time" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="arrival_time" class="form-label">Arrival Time</label>
            <input type="datetime-local" name="arrival_time" id="arrival_time" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Add Delivery</button>
    </form>

    <h4>Delivery Records</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Shipment</th>
                <th>Delivery Person</th>
                <th>Vehicle No</th>
                <th>Departure</th>
                <th>Arrival</th>
            </tr>
        </thead>
        <tbody>
            @foreach($delivery as $delivery)
            <tr>
                <td>{{ $delivery->delivery_id }}</td>
                <td>{{ $delivery->shipment->tracking_no ?? 'N/A' }}</td>
                <td>{{ $delivery->delivery_person }}</td>
                <td>{{ $delivery->vehicle_no }}</td>
                <td>{{ $delivery->departure_time }}</td>
                <td>{{ $delivery->arrival_time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
