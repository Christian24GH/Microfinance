<div id="sidebar" class="vstack visually-hidden d-flex justify-content-between border">
    <div class="top">
         <div class="d-flex align-items-center justify-content-between mt-2 ps-2" style="height: 3rem;">
            <div class="brand d-flex align-items-center">
                <div class="img_container">
                    <img src="{{asset('/img/1.5.png')}}" alt="">
                </div>
                <h4 class="mb-0 montserrat-header">TruLend</h4>
            </div>
            <div id="sidebarclose" class="d-flex align-items-center menu-btn small">
                <img src="{{asset('/img/sidebar.2.svg')}}" alt="">
            </div>
        </div>
        
        <br>
        <p class="px-4 lato-bold">General</p>
        <div class="navs px-4 d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
        @switch($role)
            @case("Technician")
                <div class="py-3 hstack nav-item  {{ request()->routeIs('mro.assignment.index') ? 'active' : '' }}">
                    <a class="nunito-nav" href="{{ route('mro.assignment.index') }}">Assigned Tasks</a>
                </div>
            @break
            @case("Maintenance Admin")
                <div class="py-3 hstack nav-item {{ request()->routeIs('mro.dashboard') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('mro.dashboard')}}" >Dashboard</a>
                </div>
    
                <div class="py-3 hstack nav-item {{ request()->routeIs('mro.workorder.index') || request()->routeIs('mro.task')  ? 'active' : ''}}">
                    <a class="nunito-nav" href="{{ route('mro.workorder.index') }}" >Work Orders</a>
                </div>
                
                <div class="py-3 hstack nav-item {{ request()->routeIs('mro.logs') ? 'active' : ''}}">
                    <a class="nunito-nav" href="{{ route('mro.logs') }}" >Logs</a>
                </div>
    
                <div class="py-3 hstack nav-item {{ request()->routeIs('mro.inventory.index') ? 'active' : ''}}">
                    <a class="nunito-nav" href="{{ route('mro.inventory.index') }}" >Inventory</a>
                </div>
            @break
    
    
            @case("Procurement Administrator")
                <div class="py-3 hstack nav-item {{ request()->routeIs('prc.dashboard.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('prc.dashboard.index')}}" >Dashboard</a>
                </div>
                
                <div class="py-3 hstack nav-item {{ request()->routeIs('prc.request.index')||
                    request()->routeIs('prc.bid.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('prc.request.index')}}" >Procurement</a>
                </div>
    
                <div class="py-3 hstack nav-item {{ request()->routeIs('prc.invoice.index')
                        || request()->routeIs('prc.receipt.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('prc.invoice.index')}}" >Bills and Receipts</a>
                </div>
            @break
            
            @case('Project Manager')
                <div class="py-3 hstack nav-item {{ request()->routeIs('pm.dashboard.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('pm.dashboard.index')}}" >Dashboard</a>
                </div>
                <div class="py-3 hstack nav-item {{ request()->routeIs('pm.projects.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('pm.projects.index')}}" >Projects</a>
                </div>
                <div class="py-3 hstack nav-item {{ request()->routeIs('pm.teams.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('pm.teams.index')}}" >Project Teams</a>
                </div>
            @break
            @case('Warehouse Manager')
                <div class="py-3 hstack nav-item {{ request()->routeIs('wrh.dashboard.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('wrh.dashboard.index')}}" >Dashboard</a>
                </div>
                <div class="py-3 hstack nav-item {{ request()->routeIs('wrh.inventory.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('wrh.inventory.index')}}" >Inventory</a>
                </div>
            @break
            @case('Asset Admin')
                <div class="py-3 hstack nav-item {{ request()->routeIs('asset.dashboard.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('asset.dashboard.index')}}" >Dashboard</a>
                </div>
                <div class="py-3 hstack nav-item {{ request()->routeIs('asset.asset.index') ? 'active' : ''}}" >
                    <a class="nunito-nav" href="{{route('asset.asset.index')}}" >Assets</a>
                </div>
            @break
        @endswitch
        </div>
    </div>
    <div class="container">
        <div id="profile" class="d-flex align-items-center bg-light
                                justify-content-between p-1
                                border-top  rounded-2 
                                flex-column mx-1"
            
                                style="min-height: fit-content">

            <div class="container-fluid lato-regular mt-2">
                <h6>{{isset($fullname) ? $fullname : 'Undefined'}}</h6>
                <small>{{isset($role) ? $role : 'Undefined'}}</small>
            </div>

            <div id="logoutCon" class="container rounded-pill border d-flex justify-content-center my-2 lato-bold">
                <form action="{{url('/logout')}}" method="POST">
                    @csrf
                    <button class="py-2" type="submit">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    
</div>