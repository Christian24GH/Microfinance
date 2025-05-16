@extends('layout.default')

@section('modal')
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
@endsection

@section('content_pagination')
<nav class="pages ms-1 mt-1">
    <div class="d-flex border-0 rounded-top-3">
        <div class="bg active"><a><p class="lato-bold m-0">Requests</p></a></div>
        <div class="bg"><a href="{{route('prc.bid.index')}}"><p class="lato-bold m-0">Bidding</p></a></div>
    </div>
</nav>
@endsection
@section('content')
<div class="container-fluid border py-2" style="min-height: 100vh">
    <h6 class="mb-0">List of Acquistion Request</h6>
    <table class="table align-middle">
        <thead>
            <tr>
                <th>#</th>
                <th>Request Number</th>
                <th>Requested By</th>
                <th>Subject</th>
                <th>Description</th>
                <th>Subject Type</th>
                <th>Quantity</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
            <tr>
                <form action="{{route('prc.request.update')}}" method="post">
                @csrf
                    <td scope="row"></td>
                    <input type="hidden" name='id' value="{{$request->id}}">
                    <td>{{$request->request_number}}</td>
                    <td>ID: {{$request->requested_by}}</td>
                    <td>{{$request->subject}}</td>
                    <td><input type="text" class="form-control form-control-sm" name="description" value='{{$request->description}}'/></td>
                    <td>
                        <select class="form-select form-select-sm" name="subject_type">
                            <option value="Service" {{$request->subject_type === 'Service' ? 'selected': ''}} > Service</option>
                            <option value="Asset" {{$request->subject_type === 'Asset' ? 'selected': ''}} > Asset</option>
                        </select>
                        
                    </td>
                    <td>
                        <div class="input-group  input-group-sm">
                            <input type="text" class="form-control form-control-sm" name="quantity" value="{{$request->quantity}}" style="width:3rem;">
                            <input type="text" class="form-control form-control-sm" name="unit" value="{{$request->unit}}" style="width:3rem;">
                        </div>
                    </td>
                    <td><input type="date" class="form-control form-control-sm" name="dueDate" value="{{$request->due_date}}"></td>
                    <td>
                        <select name="status" class="form-select form-select-sm">
                            <option value="Approved" {{$request->status === 'Approved' ? 'selected' : ''}}> Approved</option>
                            <option value="Rejected" {{$request->status === 'Rejected' ? 'selected' : ''}}> Rejected</option>
                            <option value="Pending" disabled {{$request->status === 'Pending' ? 'selected' : ''}} > Pending</option>
                            <option value="Fulfilled" disabled  {{$request->status === 'Fulfilled' ? 'selected' : ''}}> Fulfilled</option>
                        </select>
                    </td>
                    <td>
                        <div class="dropdown">
                            <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </a>
    
                            <ul class="dropdown-menu">
                                <li><button type="submit" class="dropdown-item lato-regular"><i class="bi bi-arrow-up-circle me-2"></i>Update</button></li>
                                <li><a class="dropdown-item lato-regular" 
                                    onclick="event.preventDefault(); document.getElementById('delete-form-{{$request->id}}').submit();">
                                    <i class="bi bi-trash3 me-2"></i>Delete</a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </form>
                <form id="delete-form-{{$request->id}}" action="{{ route('prc.request.destroy', ['id'=>$request->id]) }}" method="post">
                    @csrf
                    @method('DELETE')
                </form>
            </tr>
            @empty
                <tr><td colspan="9" class="text-center">No Active Request</td></tr>
            @endforelse
            
        </tbody>
    </table>
</div>

@endsection