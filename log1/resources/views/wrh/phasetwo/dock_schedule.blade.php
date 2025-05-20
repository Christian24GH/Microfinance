@extends('layout/default')

@section('content')
<div class="container mt-4 min-vh-100">
    <h2 class="mb-4">Add Dock Schedule</h2>

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
    <form action="{{ route('dockschedule.store') }}" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="warehouse_id" class="form-label">Warehouse</label>
            <select name="warehouse_id" id="warehouse_id" class="form-select" required>
                <option value="">-- Select Warehouse --</option>
                @php
                    $warehouses = DB::table('warehouse')->get();    
                @endphp
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->warehouse_id }}">{{ $warehouse->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="timeslot" class="form-label">Time Slot</label>
            <input type="datetime-local" name="timeslot" id="timeslot" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Schedule</button>
    </form>

    <h4>Dock Schedule List</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Warehouse</th>
                <th>Time Slot</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->schedule_id }}</td>
                <td>{{ $schedule->warehouse_name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($schedule->timeslot)->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
