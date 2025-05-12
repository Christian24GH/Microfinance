@extends('layout.default')
@section('content')
<div class="container">
  <h2>Create New Project</h2>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('projects.store') }}" method="POST">
    @csrf

    <div class="mb-3">
      <label for="project_code" class="form-label">Project Code</label>
      <input type="text" name="project_code" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="name" class="form-label">Project Name</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea name="description" class="form-control" rows="3"></textarea>
    </div>

    <div class="mb-3">
      <label for="category" class="form-label">Category</label>
      <input type="text" name="category" class="form-control" required>
    </div>

    <div class="row mb-3">
      <div class="col">
        <label for="start_date" class="form-label">Start Date</label>
        <input type="date" name="start_date" class="form-control" required>
      </div>
      <div class="col">
        <label for="end_date" class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label for="team_leader_id" class="form-label">Team Leader</label>
      <select name="team_leader_id" class="form-select" required>
        <option value="">Select Team Leader</option>
        @foreach($teamLeaders as $leader)
          <option value="{{ $leader->id }}">{{ $leader->name }}</option>
        @endforeach
      </select>
    </div>

    <button type="submit" class="btn btn-primary">Save Project</button>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
  </form>
</div>
@endsection