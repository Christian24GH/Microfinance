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
@endsection
@section('content')
<div class="container-fluid d-flex justify-content-end mb-1">
    <div class="btn-group" role="group">
        <button id="deleteRow" class="btn btn-danger btn-sm">Delete</button>
    </div>
</div>
<div class="container-fluid">
    <table class="table table-sm align-middle table-bordered">
        <thead>
            <tr>
                <th style="width: 2rem;"></th>
                <th>#</th>
                <th>Tag</th>
                <th>Category</th>
                <th>Status</th>
                <th>Purchase Date</th>
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