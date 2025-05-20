@extends('layout.default')

@section('modal')
<div class="modal fade" id="createProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('pm.projects.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">New Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Project Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Budget</label>
          <input type="number" name="budget" class="form-control">
        </div>

        <div class="row mb-3">
          <div class="col">
            <label class="form-label">Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
          </div>
          <div class="col">
            <label class="form-label">End Date</label>
            <input type="date" name="end_date" class="form-control" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Assign Team</label>
          <select name="team_leader_id" class="form-select" required>
            <option value="">Select Team Leader</option>
            @foreach($leaders as $leader)
              <option value="{{ $leader->id }}">{{ $leader->fullname }}</option>
            @endforeach
        </select>
        </div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save Project</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('content')
<div class="container-fluid min-vh-100">
  <table class="table table-hover align-middle">
    <thead>
      <tr>
        <th>Code</th>
        <th>Name</th>
        <th>Description</th>
        <th>Budget</th>
        <th>Lead</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($projects as $project)
        <tr>
          <td>{{ $project->project_code }}</td>
          <td>{{ $project->name }}</td>
          <td>{{ $project->description }}</td>
          <td>{{ $project->budget }}</td>
          <td>
            {{$project->team_leader_name}}
          </td>
          <td>{{ ucfirst($project->status) }}</td>
          <td>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewProject{{ $project->id }}">View</button>
            <form action="{{ route('pm.projects.destroy', $project->id) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
          </td>
        </tr>
  
        <!-- View Modal -->
        <div class="modal fade" id="viewProject{{ $project->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Project: {{ $project->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <div class="modal-body">
                <p><strong>Description:</strong> {{ $project->description }}</p>
                <ul class="list-group mb-3">
                  <li class="list-group-item"><strong>Status:</strong> {{ $project->status }}</li>
                  <li class="list-group-item"><strong>Start:</strong> {{ $project->start_date }}</li>
                  <li class="list-group-item"><strong>End:</strong> {{ $project->end_date }}</li>
                </ul>
  
                <h6 class="mt-3">Team Leader:</h6>
                <p>{{ $project->team_leader_name ?? 'Not assigned' }}</p>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </tbody>
  </table>
</div>
@endsection
