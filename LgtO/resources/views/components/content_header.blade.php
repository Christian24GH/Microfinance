<div class="container-fluid d-flex justify-content-between align-items-center" style="height:5rem">
    <h5 class="ps-2">{{$pageTitle}}</h5>
    @isset($hasBtn)
        @switch($hasBtn)
            @case('addWork')
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWork">Create Task</button>
            </div>
            @break
            @case('createSupplierModal')
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSupplierModal">Add Supplier</button>
            </div>
            @break
            @case('procurementRequestModal')
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#procurementRequestModal">Procure</button>
            </div>
            @break
            @case('createProjectModal')
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProjectModal">Create Project</button>
            </div>
            @break
            @case('createTeamModal')
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTeamModal">Create Team</button>
            </div>
            @break
            @case('addAssetModal')
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAssetModal">
                Add New Asset
            </button>
            @break
        @endswitch
    @endisset
</div>