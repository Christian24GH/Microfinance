@extends('layout.default')

@section('content')
<div class="container min-vh-100"">
  @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <!-- Teams Table -->
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>Team Name</th>
        <th>Team Leader</th>
        <th>Members</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($teams as $team)
        <tr>
          <td>{{ $team['group'] }}</td>
          <td>{{ $team['leader'] }}</td>
          <td>{{ $team['members']->implode(', ') ?: 'No members' }}</td>
          <td>
            <form action="{{ route('pm.teams.destroy', $team['group']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this team?');">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-danger">Delete</button>
            </form>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="4" class="text-center">No teams created yet.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>


<!-- Create Team Modal -->
<div class="modal fade" id="createTeamModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <form action="{{ route('pm.teams.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Create New Team</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Team Name</label>
          <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Leader</label>
          <select class="form-select" type="checkbox" name="leader">
            <option value="" disabled selected>Select Leader</option>
            @foreach ($accounts as $account)
            <option value="{{$account->id}}">
                {{ $account->fullname }}
            </option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Select Members</label>
          <div class="form-check">
            @foreach ($accounts as $account)
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="members[]" value="{{ $account->id }}" id="member{{ $account->id }}">
                <label class="form-check-label" for="member{{ $account->id }}">
                  {{ $account->fullname }}
                </label>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Create Team</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </form>
  </div>
</div>
@endsection