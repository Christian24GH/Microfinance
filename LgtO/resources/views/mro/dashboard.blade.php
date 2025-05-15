@extends('layout/default')

@section('content')
<div class="container-fluid"  style="height: 100vh">
    <div class="row mb-3" style="min-height:20vh">
        <div class="col-auto flex-fill">
            <div class="card h-100 p-2">
                <div class="card-body">
                    <h4 class="mb-0 card-title">Welcome, {{isset($fullname) ? $fullname : 'Undefined'}}</h4>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card" style="width: 18rem;">
                <div class="card-body ">
                    <h5 class="card-title text-dark-emphasis">Requests</h5>
                    <p class="card-text text-center my-2 lato-light text-dark-emphasis" style="font-size: 2rem; ">{{$Requests}}</p>
                    <a href="#" class="btn" style="background-color: var(--mfc4);">View Tasks</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card" style="width: 18rem;">
                <div class="card-body ">
                    <h5 class="card-title text-dark-emphasis">Logs</h5>
                    <p class="card-text text-center my-2 lato-light text-dark-emphasis"
                        style="font-size: 2rem; ">
                        {{$Logs}} <small class="lato-light" style="font-size:medium;"> new logs</small></p>
                    <a href="#" class="btn" style="background-color: var(--mfc4);">View Tasks</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        @forelse($WorkOrders as $WorkOrder)
        <div class="col">
            <div class="card" style="min-width: fit-content;">
                <div class="card-body px-0 pb-0 d-flex flex-column justify-content-between">
                    <div class="row mb-3 px-3">
                        <div class="col-12">
                            <div class="card-title">Casual Work Order</div>
                            <h5>{{$WorkOrder->asset_tag}}</h5>
                            <p>{{$WorkOrder->description}}</p>
                            <div class="hstack gap-2">
                                <div class="border rounded-2 py-1 w-25 text-center
                                    border-warning text-warning lato-bold">{{$WorkOrder->status}}</div>
                                <div class="border rounded-2 py-1 px-3 text-center text-success-emphasis bg-success bg-opacity-50 lato-regular">{{$WorkOrder->priority}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="container-fluid d-flex px-0">
                        <div class="border px-3 py-2 w-50">
                            <p class="text-secondary lato-regular mb-0">Category</p>
                            <p class="lato-bold mb-0" style="color:var(--mfc6)">{{$WorkOrder->category}}</p>
                        </div>
                        <div class="border px-3 py-2 w-50">
                            <p class="text-secondary lato-regular mb-0">Maintenance Date</p>
                            <p class="lato-bold mb-0" style="color:var(--mfc6)">{{$WorkOrder->maintenance_date}}</p>
                        </div>
                    </div>
                    <div class="container-fluid d-flex px-0">
                        <div class="border px-3 py-2 w-50">
                            <p class="text-secondary lato-regular mb-0">Created By</p>
                            <p class="lato-bold mb-0" style="color:var(--mfc6)">{{$WorkOrder->fullname}}</p>
                        </div>
                        <div class="border px-3 py-2 w-50">
                            <p class="text-secondary lato-regular mb-0">Assigned To</p>
                            <p class="lato-bold mb-0" style="color:var(--mfc6)">{{$WorkOrder->assigned_to}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        @endforelse
    </div>
</div>
@endsection