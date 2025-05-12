<div id="sidebar" class="vstack visually-hidden d-flex justify-content-between">
    <div class="top">
        <div class="d-flex align-items-center ps-2" style="height: 3rem;">
            <h4 class="mb-0">TruLend</h4>
        </div>
        
        <br>
        <p class="px-4">General</p>
        <div class="navs px-4 d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
        @switch($role)
            @case("Maintenance Admin")
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('mro.dashboard') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-speedometer2"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('mro.dashboard')}}" >Dashboard</a>
                </div>
    
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('mro.workorder.index') || request()->routeIs('mro.task')  ? 'active' : ''}}">
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-stack"></i>
                    </h3>
                    <a class="nunito-nav" href="{{ route('mro.workorder.index') }}" >Work Orders</a>
                </div>
    
                <div class="py-0 hstack gap-3 nav-item  {{ request()->routeIs('mro.assignment.index') ? 'active' : '' }}">
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-check-square"></i>
                    </h3>
                    <a class="nunito-nav" href="{{ route('mro.assignment.index') }}">Assigned Tasks</a>
                </div>
    
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('mro.logs') ? 'active' : ''}}">
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-clock-history"></i>
                    </h3>
                    <a class="nunito-nav" href="{{ route('mro.logs') }}" >Logs</a>
                </div>
    
                <div class="py-0  hstack gap-3 nav-item {{ request()->routeIs('mro.inventory.index') ? 'active' : ''}}">
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-backpack"></i>
                    </h3>
                    <a class="nunito-nav" href="{{ route('mro.inventory.index') }}" >Inventory</a>
                </div>
            @break
    
    
            @case("Procurement Administrator")
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('prc.dashboard.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-speedometer2"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('prc.dashboard.index')}}" >Dashboard</a>
                </div>
                
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('prc.request.index')||
                    request()->routeIs('prc.bid.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-box-fill"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('prc.request.index')}}" >Procurement</a>
                </div>
    
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('prc.invoice.index')
                        || request()->routeIs('prc.receipt.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-file-earmark-fill"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('prc.invoice.index')}}" >Bills and Receipts</a>
                </div>
            @break
            
            @case('Project Manager')
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('pm.dashboard.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-speedometer2"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('pm.dashboard.index')}}" >Dashboard</a>
                </div>
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('pm.projects.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-kanban-fill"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('pm.projects.index')}}" >Projects</a>
                </div>
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('pm.teams.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-people-fill"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('pm.teams.index')}}" >Project Teams</a>
                </div>
            @break
            @case('Warehouse Manager')
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('wrh.dashboard.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-speedometer2"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('wrh.dashboard.index')}}" >Dashboard</a>
                </div>
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('wrh.inventory.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-box"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('wrh.inventory.index')}}" >Inventory</a>
                </div>
            @break
            @case('Asset Admin')
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('asset.dashboard.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-speedometer2"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('asset.dashboard.index')}}" >Dashboard</a>
                </div>
                <div class="py-0 hstack gap-3 nav-item {{ request()->routeIs('asset.asset.index') ? 'active' : ''}}" >
                    <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                        <i class="bi bi-box"></i>
                    </h3>
                    <a class="nunito-nav" href="{{route('asset.asset.index')}}" >Assets</a>
                </div>
            @break
        @endswitch
        </div>
    </div>
    <div class="container border-top">
        <div id="profile" class="d-flex align-items-center justify-content-between px-4 pt-1"
            style="height: 5rem">

            <div class="lato-regular">
                <h6>{{isset($fullname) ? $fullname : 'Undefined';}}</h6>
                <small>{{isset($role) ? $role : 'Undefined';}}</small>
            </div>

            <form action="{{url('/logout')}}" method="POST">
                @csrf
                <div class="dropdown">
                    <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-three-dots-vertical"></i>
                    </a>    
                    <ul class="dropdown-menu">
                        <button class="btn dropdown-item" type="submit">
                            <i class="bi bi-box-arrow-left"></i>
                            Logout
                        </button>
                    </ul>
                </div>
            </form>
        </div>
    </div>
    
</div>