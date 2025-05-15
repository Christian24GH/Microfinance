@extends('layout.default')
@section('content')
<div class="container-fluid pt-2" style="min-height: 100vh">
    <div class="px-2">
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
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reportModal--{{$task->id}}">Make Report</button>
                    </td>
                </tr>
                <div class="modal fade" id="reportModal--{{$task->id}}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog  modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Work Order -- {{$task->id}}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <form id="storeWorkOrder" action="{{route('mro.assignment.reportgen')}}" method="post">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{$task->id}}">
                                        <input type="hidden" name="work_order_id" value="{{$task->work_order_id}}">
                                        <label for="desc">Log Description</label>
                                        <textarea id="desc" class="form-control" name="description" required></textarea>
                                        <br>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input form-check-input-lg border-2" type="checkbox" id="LoggedAsComplete" name="LoggedAsComplete">
                                            <label for="LoggedAsComplete" class="form-check-label">Log as Complete</label>
                                        </div>
                                    </form>
                                </div>
                                <div class="container d-flex justify-content-center align-items-center gap-3 mt-3">
                                    <button type="submit" class="btn btn-primary" onclick="document.getElementById('storeWorkOrder').requestSubmit()" style="min-width: 10rem;"><b>Submit Report</b></button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="min-width: 10rem;"><b>Cancel</b></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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