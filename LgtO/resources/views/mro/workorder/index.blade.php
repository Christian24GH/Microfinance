@extends('layout.default')
@section('modal')
<div class="modal fade" id="addWork" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Maintenace Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if(!isset($assets))
                    <div class="alert alert-secondary" role="alert">
                        No Assets Available.
                    </div>
                @else
                <div class="container">
                    <form id="storeWorkOrder" action="{{route('mro.workorder.store')}}" method="post">
                        @csrf
                        <input type="hidden" name="created_by" value="{{$id}}">
                        <div class="row">
                            <div class="col mb-3">
                                <label for="asset" class="form-label">Asset</label>
                                <select id="asset" class="form-select" name="asset" required>
                                    <option value="">Select Here</option>
                                    @foreach($assets as $asset)
                                    <option value="{{$asset->id}}">{{$asset->asset_tag}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label for="priority" class="form-label">Priority</label>
                                <select id="priority" class="form-select" name="priority" required>
                                    <option value="">Select Here</option>
                                    <option value="high">High</option>
                                    <option value="medium">Medium</option>
                                    <option value="low">Low</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control"  placeholder="Type here"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="maintenance_type" class="form-label">Maintenance Type</label>
                                <select id="maintenance_type" class="form-select" name="maintenance_type" required>
                                    <option value="">Select Here</option>
                                    <option value="preventive">Preventive</option>
                                    <option value="corrective">Corrective</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" name="location" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select id="status" class="form-select" name="status" required>
                                    <option value="">Select Here</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="cancelled">Cancelled</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label for="startdate" class="form-label">Start Date</label>
                                <input id="startdate" type="date" class="form-control" name="startdate" required/>
                            </div>
                        </div>
                    </form>
                </div>
                @endif 
                <div class="container d-flex justify-content-center align-items-center gap-3">
                    
                    <button type="submit" class="btn btn-primary" onclick="document.getElementById('storeWorkOrder').requestSubmit()" style="min-width: 10rem;"><b>Add Maintenance</b></button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="min-width: 10rem;"><b>Cancel</b></button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('content_header')
<div class="container-fluid border border-bottom-2 px-5 d-flex justify-content-between align-items-center" style="height:5rem" >
    <h3 class="py-5">Work Order</h3>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWork">Create Task</button>
    </div>
</div>
@endsection
@section('content_pagination')
<nav id="pagination" class="pages ms-1 mt-1">
    <div class="d-flex border-0 rounded-top-3">
        <div class="bg active"><a><p class="lato-bold m-0">Maintenance Works</p></a></div>
        <div class="bg"><a href="{{route('mro.task')}}"><p class="lato-bold m-0">Assign Tasks</p></a></div>
    </div>
</nav>
@endsection
@section('content')
<div class="container-fluid border" style="min-height: 100vh">
    <div class="px-2">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset</th>
                    <th>Description</th>
                    <th>Maintenance Type</th>
                    <th>Priority</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Schedule</th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="">
                @foreach($workOrders as $workOrder)
                <form action="{{route('mro.workorder.update')}}" method="post">
                    @csrf
                    <tr>
                        <td>
                            <input type="hidden" name="id" value="{{$workOrder->id}}">
                            {{$loop->iteration}}
                        </td>
                        <td>
                            <select name="asset_tag" id="" class="form-select">
                                @foreach($assets as $asset)
                                    <option value="{{$asset->id}}" 
                                        {{($workOrder->asset_id == $asset->id) ? 'selected' : ''}}>
                                        {{$asset->asset_tag}}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input name="description" class="form-control" value="{{$workOrder->description}}"/>
                        </td>
                        <td>
                            <select name="maintenance_type" class="form-select">
                                <option value="preventive" 
                                    {{($workOrder->maintenance_type == 'preventive') ? 'selected' : ''}}>
                                    Preventive
                                </option>
                                <option value="corrective" 
                                    {{($workOrder->maintenance_type == 'corrective') ? 'selected' : ''}}>
                                    Corrective
                                </option>
                            </select>
                        </td>
                        <td>
                            <select name="priority" class="form-select">
                                <option value="high" 
                                    {{($workOrder->priority == 'high') ? 'selected' : ''}}>
                                    High
                                </option>
                                <option value="medium" 
                                    {{($workOrder->priority == 'medium') ? 'selected' : ''}}>
                                    Medium
                                </option>
                                <option value="low" 
                                    {{($workOrder->priority == 'low') ? 'selected' : ''}}>
                                    Low
                                </option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="location" class="form-control" value="{{$workOrder->location}}">
                        </td>
                        <td>
                            <select name="status" class="form-select">
                                <option value="completed" 
                                    {{($workOrder->status == 'completed') ? 'selected' : ''}}>
                                    Completed
                                </option>
                                <option value="cancelled" 
                                    {{($workOrder->status == 'cancelled') ? 'selected' : ''}}>
                                    Cancelled
                                </option>
                                <option value="in_progress" 
                                    {{($workOrder->status == 'in_progress') ? 'selected' : ''}}>
                                    In Progress
                                </option>
                                <option value="pending" 
                                    {{($workOrder->status == 'pending') ? 'selected' : ''}}>
                                    Pending
                                </option>
                            </select>
                        </td>
                        <td>
                            <input type="date" name="startdate" class="form-control" value="{{$workOrder->maintenance_date}}"/>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><button type="submit" class="dropdown-item lato-regular"><i class="bi bi-arrow-up-circle me-2"></i>Update</button></li>
                                    <li><a class="dropdown-item lato-regular" 
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{$workOrder->id}}').submit();">
                                        <i class="bi bi-trash3 me-2"></i>Delete</a>
                                    </li>
                                </ul>
                            </div>

                            
                        </td>
                    </tr>
                </form>
                <form id="delete-form-{{$workOrder->id}}" action="{{route('mro.workorder.destroy', ['id'=>$workOrder->id])}}" method="post">
                    @csrf
                    @method('DELETE')
                </form>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection


