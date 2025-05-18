@extends('layout/default')

@section('content')
<div class="container-fluid" style="min-height: 100vh">
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
                <div class="card-body">
                    <h5 class="card-title text-dark-emphasis">New Requests</h5>
                    <p class="card-text text-center my-2 lato-light text-dark-emphasis" style="font-size: 2rem; ">{{$NewRequest}}</p>
                    <a href="{{route('prc.request.index')}}" class="btn" style="background-color: var(--mfc4);">View</a>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="card" style="width: 18rem;">
                <div class="card-body ">
                    <h5 class="card-title text-dark-emphasis">Unpaid Bills</h5>
                    <p class="card-text text-center my-2 lato-light text-dark-emphasis" style="font-size: 2rem; ">{{$unpaidBills}}</p>
                    <a href="{{route('prc.invoice.index')}}" class="btn" style="background-color: var(--mfc4);">View Invoices</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Overall Spending</h5>
                    <canvas id="dailySpendingChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // @ts-nocheck
    window.dailyAmounts = @json($dailyAmounts);
</script>
@vite('resources/js/prc.chart.js')
@endsection