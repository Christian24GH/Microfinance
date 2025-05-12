@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Warehouse Report</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="warehouse_id" class="form-label">Warehouse</label>
            <select name="warehouse_id" id="warehouse_id" class="form-select" required>
                <option value="">-- Select Warehouse --</option>
                @foreach($warehouse as $warehouse)
                    <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="report_type" class="form-label">Report Type</label>
            <input type="text" name="report_type" id="report_type" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="generated_date" class="form-label">Generated Date</label>
            <input type="date" name="generated_date" id="generated_date" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Report</button>
    </form>

    <h4>Existing Reports</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Warehouse</th>
                <th>Report Type</th>
                <th>Generated Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report as $report)
            <tr>
                <td>{{ $report->report_id }}</td>
                <td>{{ $report->warehouse->name ?? 'N/A' }}</td>
                <td>{{ $report->report_type }}</td>
                <td>{{ $report->generated_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
