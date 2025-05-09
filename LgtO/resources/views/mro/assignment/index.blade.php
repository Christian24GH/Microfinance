@extends('layout.default')

@section('content')
<div class="container-fluid pt-2">
    <div class="px-2 min-vh-100 table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Maintenance Number</th>
                    <th>Asset</th>
                    <th>Schedule</th>
                    <th>Category</th>
                    <th>description</th>
                    <th>location</th>
                    <th>priority</th>
                    <th>Report</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td>{{  $loop->iteration  }}</td>
                    <td>{{  $task->work_order_id }}</td>
                    <td>{{ $task->asset_tag }}</td>
                    <td>{{ $task->maintenance_date }}</td>
                    <td>{{ ucfirst($task->maintenance_type) }}</td>
                    <td>{{ $task->description }}</td>
                    <td>{{ ucfirst($task->location) }}</td>
                    <td>{{ ucfirst($task->priority) }}</td>
                    <td class="">
                        <button class="btn btn-primary">Make Report</button>
                    </td>
                </tr>
                
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No work assigned for you. Come back later.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection