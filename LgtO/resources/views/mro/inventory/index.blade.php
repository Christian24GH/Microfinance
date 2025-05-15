@extends('layout.default')

@section('content')
<div class="container-fluid pt-2" style="min-height: 100vh">
    <div class="px-2">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Part Name</th>
                    <th>Part Number</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Quantity In Stock</th>
                    <th>Unit</th>
                    <th>Reorder Level</th>
                    <th>Unit Cost (₱)</th>
                    <th>Status</th>
                    <th>Option</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($partsInventory as $part)
                <form action="{{route('mro.inventory.update')}}" method="post">
                    @csrf
                    <tr>
                        <input type="hidden" name="id" value="{{$part->id}}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $part->part_name }}</td>
                        <td>{{ $part->part_number }}</td>
                        <td>{{ $part->description ?? 'N/A' }}</td>
                        <td>{{ ucfirst($part->category) }}</td>
                        <td>{{ $part->quantity_in_stock }}</td>
                        <td>{{ ucfirst($part->unit) }}</td>
                        <td>
                            <input type="number" class="form-control" name="reorder_level" value="{{ $part->reorder_level }}">
                        </td>
                        <td>₱{{ number_format($part->unit_cost, 2) }}</td>
                        <td>
                            <select class="form-select" name="status">
                                <option value="active" {{ $part->status == 'active' ? 'selected' : ''}} >Active</option>
                                <option value="discontinued" {{ $part->status == 'discontinued' ? 'selected' : ''}}> Discontinued</option>
                            </select>
                        </td>
                        <td>
                            <div class="dropdown">
                                <a class="btn" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </a>

                                <ul class="dropdown-menu">
                                    <li><button type="submit" class="dropdown-item lato-regular"><i class="bi bi-arrow-up-circle me-2"></i>Update</button></li>
                                    <li><a class="dropdown-item lato-regular" 
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{$part->id}}').submit();">
                                        <i class="bi bi-trash3 me-2"></i>Delete</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </form>
                <form id="delete-form-{{$part->id}}" action="{{route('mro.inventory.destroy', ['id'=>$part->id])}}" method="post">
                    @csrf
                    @method('DELETE')
                </form>
                @empty
                <tr>
                    <td colspan="10" class="text-center">No parts in inventory.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@endsection