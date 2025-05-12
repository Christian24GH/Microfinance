@extends('layout/default')

@section('content')
<div class="container mt-4">
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Warehouse</h5>
                    <p class="card-text fs-4">{{ $warehouseCount }}</p>
                    <a href="{{ route('wrh.warehouse.index') }}" class="btn btn-light btn-sm">View All</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Inventory</h5>
                    <p class="card-text fs-4">{{ $inventoryCount }}</p>
                    <a href="{{ route('wrh.inventory.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Shipment</h5>
                    <p class="card-text fs-4">{{ $shipmentCount }}</p>
                    <a href="{{ route('wrh.shipment.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>

        <!--
        <div class="col-md-3">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Order</h5>
                    <p class="card-text fs-4">{{ $orderCount }}</p>
                    <a href="" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>
-->
        <div class="col-md-3">
            <div class="card text-white bg-secondary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Dock Schedule</h5>
                    <p class="card-text fs-4">{{ $dockScheduleCount }}</p>
                    <a href="{{ route('wrh.dock_schedules.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-dark shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Supplier</h5>
                    <p class="card-text fs-4">{{ $supplierCount }}</p>
                    <a href="{{ route('wrh.supplier.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-danger shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Quality Check</h5>
                    <p class="card-text fs-4">{{ $qualityCheckCount }}</p>
                    <a href="{{ route('wrh.quality_check.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-teal shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Last-Mile Delivery</h5>
                    <p class="card-text fs-4">{{ $deliveryCount }}</p>
                    <a href="{{ route('wrh.lastmile_delivery.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-indigo shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">RFID Tag</h5>
                    <p class="card-text fs-4">{{ $rfidCount }}</p>
                    <a href="{{ route('wrh.rfid_tag.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-secondary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Report</h5>
                    <p class="card-text fs-4">{{ $reportCount }}</p>
                    <a href="{{ route('wrh.wrh_report.index') }}" class="btn btn-light btn-sm">View</a>
                </div>
            </div>
        </div>
    </div>

    <h5 class="mb-3 mt-5">Recent Shipment</h5>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Shipment ID</th>
                        <th>Carrier</th>
                        <th>Ship Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentShipments as $shipment)
                        <tr>
                            <td>{{ $shipment->shipment_id }}</td>
                            <td>{{ $shipment->carrier }}</td>
                            <td>{{ $shipment->ship_date }}</td>
                            <td>{{ $shipment->delivery_status }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No recent shipments.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
