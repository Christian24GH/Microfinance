<div id="sidebar" class="vstack px-4">
    <div class="py-3">
        <h3 class="montserrat-header">{{$title}}</h3>
    </div>
    
    <div id="profile" class="d-flex gap-3 align-items-center ps-1 py-4">
        <div class="border img_container">
            <img src="" class="rounded-circle" alt="">
        </div>
        <div class="lato-regular">
            <h5>{{isset($fullname) ? $fullname : 'Undefined';}}</h5>
            <h6>{{isset($role) ? $role : 'Undefined';}}</h6>
        </div>
    </div>
    <hr>
    <div class="navs d-flex flex-column gap-1 align-items-center justify-content-center position-relative">
    @if($role === "Maintenance Admin")
        <div class="py-2 hstack gap-3 nav-item {{ request()->routeIs('mro.dashboard') ? 'active' : ''}}" >
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-speedometer2"></i>
            </h3>
            <a class="nunito-nav" href="{{route('mro.dashboard')}}" >Dashboard</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item {{ request()->routeIs('mro.workorder.index') || request()->routeIs('mro.task')  ? 'active' : ''}}">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-stack"></i>
            </h3>
            <a class="nunito-nav" href="{{ route('mro.workorder.index') }}" >Work Orders</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item  {{ request()->routeIs('mro.assignment.index') ? 'active' : '' }}">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-check-square"></i>
            </h3>
            <a class="nunito-nav" href="{{ route('mro.assignment.index') }}">Assigned Tasks</a>
        </div>

        <div class="py-2 hstack gap-3 nav-item {{ request()->routeIs('mro.logs') ? 'active' : ''}}">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-clock-history"></i>
            </h3>
            <a class="nunito-nav" href="{{ route('mro.logs') }}" >Logs</a>
        </div>

        <div class="py-2  hstack gap-3 nav-item {{ request()->routeIs('mro.inventory.index') ? 'active' : ''}}">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-backpack"></i>
            </h3>
            <a class="nunito-nav" href="{{ route('mro.inventory.index') }}" >Inventory</a>
        </div>   
        
        <div class="d-flex w-100 flex-column">
            <hr>
        </div>
        <!--Logout-->
        <div class="py-2 hstack gap-3 nav-item">
            <h3 class="rounded-3 d-flex align-items-center justify-content-center" style="height: 3rem; width:3rem">
                <i class="bi bi-power"></i>
            </h3>
            <form action="{{url('/logout')}}" method="POST">
                @csrf
                <button class="nunito-nav" type="submit">Logout</button>
            </form>
        </div>
    </div>
    @endif
</div>