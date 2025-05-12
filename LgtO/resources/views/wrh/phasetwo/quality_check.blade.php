@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Quality Check</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="inventory_id" class="form-label">Inventory</label>
            <select name="inventory_id" id="inventory_id" class="form-select" required>
                <option value="">-- Select Inventory --</option>
                @foreach($inventory as $inventory)
                    <option value="{{ $inventory->inventory_id }}">{{ $inventory->item_name }} (ID: {{ $inventory->inventory_id }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="check_date" class="form-label">Check Date</label>
            <input type="date" name="check_date" id="check_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="result" class="form-label">Result</label>
            <select name="result" id="result" class="form-select" required>
                <option value="">-- Select Result --</option>
                <option value="Pass">Pass</option>
                <option value="Fail">Fail</option>
                <option value="Pending">Pending</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Add Quality Check</button>
    </form>

    <h4>Quality Check Records</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Inventory Item</th>
                <th>Check Date</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            @foreach($qualityCheck as $qc)
            <tr>
                <td>{{ $qc->qc_id }}</td>
                <td>{{ $qc->inventory->item_name ?? 'N/A' }}</td>
                <td>{{ $qc->check_date }}</td>
                <td>{{ $qc->result }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
