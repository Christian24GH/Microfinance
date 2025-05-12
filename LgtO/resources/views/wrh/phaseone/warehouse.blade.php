@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Warehouse</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Warehouse Name</label>
            <input type="text" name="name" class="form-control" id="name" required>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Location</label>
            <input type="text" name="location" class="form-control" id="location" required>
        </div>
        <div class="mb-3">
            <label for="capacity" class="form-label">Capacity</label>
            <input type="number" name="capacity" class="form-control" id="capacity" required>
        </div>
        <div class="mb-3">
            <label for="manager_name" class="form-label">Manager Name</label>
            <input type="text" name="manager_name" class="form-control" id="manager_name">
        </div>
        <div class="mb-3">
            <label for="contact_no" class="form-label">Contact Number</label>
            <input type="text" name="contact_no" class="form-control" id="contact_no">
        </div>
        <button type="submit" class="btn btn-primary">Add Warehouse</button>
    </form>

    <h4>Warehouse List</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Location</th>
                <th>Capacity</th>
                <th>Manager</th>
                <th>Contact</th>
            </tr>
        </thead>
        <tbody>
            @foreach($warehouse as $warehouse)
            <tr>
                <td>{{ $warehouse->warehouse_id }}</td>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->location }}</td>
                <td>{{ $warehouse->capacity }}</td>
                <td>{{ $warehouse->manager_name }}</td>
                <td>{{ $warehouse->contact_no }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
