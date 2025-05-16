@extends('layout.default')

@section('modal')
<!-- Add Asset Modal -->
<div class="modal fade" id="addAssetModal" tabindex="-1" aria-labelledby="addAssetModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ route('asset.asset.store') }}" method="POST">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addAssetModalLabel">Add New Asset</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
          <!-- Asset Tag -->
          <div class="mb-3">
            <label for="asset_tag" class="form-label">Asset Tag</label>
            <input type="text" class="form-control" id="asset_tag" name="asset_tag" maxlength="20" required>
          </div>

          <!-- Category -->
          <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
              <option value="" disabled selected>Select category</option>
              <option value="vehicle">Vehicle</option>
              <option value="electronic">Electronic</option>
              <option value="furniture">Furniture</option>
              <option value="building">Building</option>
              <option value="others">Others</option>
            </select>
          </div>

          <!-- Status -->
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
              <option value="" disabled selected>Select status</option>
              <option value="active">Active</option>
              <option value="under repair">Under Repair</option>
              <option value="decommissioned">Decommissioned</option>
            </select>
          </div>

          <!-- Purchase Date -->
          <div class="mb-3">
            <label for="purchase_date" class="form-label">Purchase Date</label>
            <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ now()->format('Y-m-d') }}" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Asset</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Procurement Modal -->
<div class="modal fade" id="procurementRequestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="requestModalLabel">New Request</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <form action="{{route('prc.request.store')}}" method="post">
        @csrf
        <div class="modal-body">
            <input type="hidden" class="form-control" name="requested_by" value="{{$id}}" required>

            <div class="mb-3">
                <label for="subject" class="form-label">Subject</label>
                <input type="text" class="form-control" id="subject" name="subject" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="subjectType" class="form-label">Subject Type</label>
                <select type="text" class="form-select" name="subject_type" required>
                    <option value="Service" > Service</option>
                    <option value="Asset" > Asset</option>
                </select>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Quantity</span>
                        <input type="number" class="form-control" name="quantity" min="1" required>
                    </div>
                </div>
                <div class="col">
                    <div class="input-group mb-3">
                        <span class="input-group-text">Unit</span>
                        <input type="text" class="form-control" name="unit" required>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="dueDate" class="form-label">Due Date</label>
                <input type="date" class="form-control" id="dueDate" name="dueDate" required>
            </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit Request</button>
        </div>
      </form>
      
    </div>
  </div>
</div>

<!-- Add Maintenance Modal -->
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
@section('content')
<div class="container-fluid d-flex mb-1" >
    <div class="border rounded-2 w-100 hstack justify-content-between" style="background-color:white">
      <div class="d-flex gap-2" role="group">
          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addAssetModal">
                  Add New Asset
          </button>
          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#procurementRequestModal">Write Procurement Request</button>
          <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addWork">Write Maintenance Request</button>
      </div>
      <div class="btn-group" role="group">
          <button id="deleteRow" class="btn btn-danger btn-sm">Delete</button>
      </div>
    </div>
</div>
<div class="container-fluid" style="min-height: 100vh">
    <table class="table table-sm align-middle table-bordered">
        <thead>
            <tr>
                <th style="width: 2rem;"></th>
                <th>#</th>
                <th>Tag</th>
                <th>Category</th>
                <th>Status</th>
                <th>Purchase Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
            <tr class="border">
                <td class="d-flex align-items-center justify-content-center border-0"><input class="form-check-input " name="action[]" type="checkbox" value="{{$asset->id}}"/></td>
                <form action="{{route('asset.asset.update')}}" id="AssetForm-{{$asset->id}}" method="post">
                @csrf
                    <td>{{$loop->iteration}}</td>
                    <td>{{$asset->asset_tag}}</td>
                    <td>
                        <select class="form-select form-select-sm" name="status">
                            <option value="active" {{$asset->status == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="decommissioned" {{$asset->status == 'decommissioned' ? 'selected' : '' }}>Decommissioned</option>
                            <option value="under repair" {{$asset->status == 'under repair' ? 'selected' : '' }}>Under Repair</option>
                        </select>
                    </td>
                    <td>
                        <select class="form-select form-select-sm" name="category">
                            <option value="building" {{$asset->category == 'building' ? 'selected' : '' }}>Building</option>
                            <option value="electronic" {{$asset->category == 'electronic' ? 'selected' : '' }}>Electronic</option>
                            <option value="furniture" {{$asset->category == 'furniture' ? 'selected' : '' }}>Furniture</option>
                            <option value="vehicle" {{$asset->category == 'vehicle' ? 'selected' : '' }}>Vehicle</option>
                            <option value="others" {{$asset->category == 'others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </td>
                    <td>{{$asset->purchase_date}}</td>
                    <td>
                        <input type="hidden" name="id" value="{{$asset->id}}">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </td>
                </form>
            </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">No Asset Found</td>
                </tr>
            @endforelse
            <script>
                $route = "{{route('asset.asset.destroy')}}";
                document.getElementById('deleteRow').addEventListener('click', function(){
                    let values = Array.from(document.querySelectorAll("input[type='checkbox']:checked"))
                        .map(e=>e.value);

                    if(values.length > 0){
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = $route;

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);

                        values.forEach(value => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = value;
                            form.appendChild(input);
                        });

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            </script>
        </tbody>
    </table>
</div>
@endsection