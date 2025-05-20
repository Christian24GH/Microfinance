@extends('layout/default')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Add Warehouse</h2>

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
    <form action="{{ route('warehouse.store') }}" method="POST" class="card p-4 mb-4 shadow-sm">
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
            @php
                $managers = DB::table('accounts as a')
                    ->join('employee_info as e', 'e.id', '=', 'a.employee_id')
                    ->join('roles as r', 'r.id', '=', 'a.role_id')
                    ->where('r.role', 'Warehouse Manager')
                    ->get([
                        'a.id',
                        DB::raw("CONCAT(e.firstname, ' ', COALESCE(e.middlename, ''), ' ', e.lastname) as fullname")
                    ]);
            @endphp

            <select name="manager" class="form-select" required>
                <option selected disabled>Select Here</option>
                @forelse($managers as $manager)
                    <option value="{{$manager->id}}">{{$manager->fullname}}</option>
                @empty 
                    <option selected disabled>There are no Warehouse Managers at the records</option>
                @endforelse
            </select>
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
            </tr>
        </thead>
        <tbody>
            @foreach($warehouse as $warehouse)
            <tr>
                <td>{{ $warehouse->warehouse_id }}</td>
                <td>{{ $warehouse->name }}</td>
                <td>{{ $warehouse->location }}</td>
                <td>{{ $warehouse->capacity }}</td>
                <td>{{ $warehouse->fullname }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
