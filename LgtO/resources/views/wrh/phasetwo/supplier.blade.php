@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Supplier</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="" method="POST" class="card p-4 mb-4 shadow-sm">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Supplier Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contact_no" class="form-label">Contact Number</label>
            <input type="text" name="contact_no" id="contact_no" class="form-control">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea name="address" id="address" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Add Supplier</button>
    </form>

    <h4>Supplier List</h4>
    <table class="table table-bordered bg-white shadow-sm">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Email</th>
                <th>Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($supplier as $supplier)
            <tr>
                <td>{{ $supplier->supplier_id }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->contact_no }}</td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->address }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
