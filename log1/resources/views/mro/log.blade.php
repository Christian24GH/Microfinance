@extends('layout.default')

@section('content')
<div class="container-fluid" style="min-height: 100vh">
    @forelse($logs as $log)
    <div class="card mb-1">
        <div class="card-body gap-1">
            <h5 class="card-title text-secondary">Maintenance ID : {{$log->mID}}</h5>
            <small class="lato-thin">By {{$log->fullname}} posted on {{$log->created_at}}</small>
            <p class="lato-regular">{{$log->description}}</p>
        </div>
    </div>
    @empty
    <div class="card">
        <div class="card-body">
            <h3 class="text-center">No Logs Available</h3>
        </div>
    </div>
    
    @endforelse
</div>
@endsection