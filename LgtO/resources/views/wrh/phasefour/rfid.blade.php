@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">RFID Tag Management</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('rfid_tag.store') }}" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="inventory_id" class="form-label">Inventory</label>
            <select name="inventory_id" id="inventory_id" class="form-select" required>
                <option value="">-- Select Inventory --</option>
                @foreach($inventory as $inventory)
                    <option value="{{ $inventory->inventory_id }}">{{ $inventory->item_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="barcode" class="form-label">Barcode</label>
            <input type="text" name="barcode" id="barcode" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="scanned_time" class="form-label">Scanned Time</label>
            <input type="datetime-local" name="scanned_time" id="scanned_time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add RFID Tag</button>
    </form>

    <h4>RFID Tag List</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Inventory</th>
                <th>Barcode</th>
                <th>Scanned Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rfidTag as $tag)
            <tr>
                <td>{{ $tag->tag_id }}</td>
                <td>{{ $tag->inventory->item_name ?? 'N/A' }}</td>
                <td>{{ $tag->barcode }}</td>
                <td>{{ $tag->scanned_time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
