@extends('layout.default')
@section('content_header')
<div class="container-fluid border border-bottom-2 px-5 d-flex justify-content-between align-items-center">
    <h3 class="py-5">Work Order</h3>
    <div>
        <!--<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWork">Create Task</button>-->
    </div>
</div>
@endsection
@section('content_pagination')
<nav class="pages">
    <div class="d-flex">
        <a class="rounded-0" href="{{route('mro.workorder.index')}}"><p class="lato-bold m-0">Maintenance Works</p></a>
        <a class="rounded-0 active"><p class="lato-bold m-0">Assign Tasks</p></a>
    </div>
</nav>
@endsection
@section('content')
<!-- Modal -->
<div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModal" data-bs-target="#staticBackdrop">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5"></h1>
      </div>
      <div class="modal-body position-relative">
        <div class="loading align-items-center justify-content-center w-100 position-absolute h-100 start-50 top-50  translate-middle z-3 py-5">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <form id="assignTechnicians" action="{{route('mro.task.save')}}" method="post">
            @csrf
            <input id="workid" type="hidden" name="workid" value="" required>
            <div id="techniciansContainer"></div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('assignTechnicians').requestSubmit()">Save changes</button>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid border">
    <div class="px-2 min-vh-100 table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Asset</th>
                    <th>Description</th>
                    <th>Maintenance Type</th>
                    <th>Status</th>
                    <th>Technicians</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($workOrders as $workOrder)
                <tr>
                    <td>{{$loop->iteration}}</td>        
                    <td>
                        @foreach($assets as $asset)
                            {{$workOrder->asset_id == $asset->id ? $asset->asset_tag : ''}}
                        @endforeach
                    </td>
                    <td>{{$workOrder->description}}</td>
                    <td class="text-capitalize">{{$workOrder->maintenance_type}}</td>
                    <td class="text-capitalize">{{$workOrder->status}}</td>
                    <td>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#assignmentModal" 
                            data-bs-workid="{{$workOrder->id}}">
                            Technicians
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<script>
    const spinner = document.querySelector('.loading')
    let container = document.getElementById("techniciansContainer");
    function loading(state){
        if(state === 'show'){
            spinner.style.display = 'flex';
            container.style.display = 'none';
        } else if(state === 'hidden'){
            spinner.style.display = 'none';
            container.style.display = 'block';
        }
    }
    async function getTechnicians(work_order_id){
        
        let url = "{{ route('mro.task.getTechnicians', ':id') }}";
        url = url.replace(':id', work_order_id);
        const response = await fetch(url);
        const data = await response.json();
        
        const all = data.all;
        const assigned = data.assigned;
        
        container.innerHTML = "";

        const checkedId = [];
        for(const [key, value] of Object.entries(assigned)){
            checkedId.push(value.technicians_id);
        }
        console.log(checkedId);
        
        Array.from(all).forEach(e=>{
            console.log(e)
            const isAssigned = checkedId.includes(e.id);
            const checkboxGroup = createCheckbox(e.id, e.fullname, isAssigned);
            container.appendChild(checkboxGroup);
        })
        loading('hidden');
    }
    
    function createCheckbox(id, labelText, isChecked) {
        let div = document.createElement('div');
        div.className = 'form-check';

        let input = document.createElement('input');
        input.type = 'checkbox';
        input.className = 'form-check-input';
        input.name = 'technicians[]';
        input.value = id;
        input.id = 'tech_' + id;
        input.checked = isChecked;

        let label = document.createElement('label');
        label.className = 'form-check-label';
        label.htmlFor = input.id;
        label.textContent = labelText;

        div.appendChild(input);
        div.appendChild(label);

        return div;
    }
    
    const assignmentModal = document.getElementById('assignmentModal')
        if (assignmentModal) {
            
            assignmentModal.addEventListener('show.bs.modal', event => {
                loading('show')
                const button = event.relatedTarget
                
                const work_id = button.getAttribute('data-bs-workid')
                
                getTechnicians(work_id);
                const modalTitle = assignmentModal.querySelector('.modal-title')
                const hiddentInput = assignmentModal.querySelector('.modal-body form input#workid')
                modalTitle.textContent = `Technicians for Work Order ${work_id}`
                hiddentInput.value = work_id;
            })
        }
</script>
@endsection