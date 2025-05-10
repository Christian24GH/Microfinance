@extends('layout.default')
@section('content_pagination')
<nav class="pages ms-1 mt-1">
    <div class="d-flex border-0 rounded-top-3">
        <div class="bg "><a href="{{route('prc.request.index')}}"><p class="lato-bold m-0">Requests</p></a></div>
        <div class="bg active"><a ><p class="lato-bold m-0">Bidding</p></a></div>
    </div>
</nav>
@endsection
@section('content')
<div class="container-fluid border py-3">
    
    <div class="container list-group gap-2 overflow-y-scroll"  style="height: 80vh">
        <h6 class="mb-1">List of Available Bidding</h6>
        @forelse($bids as $bid)
        
        <div class="container list-group-item">
            <div class="row mx-1 mb-2">
                <div class="col-12 d-flex justify-content-between align-items-center">
                    <h5 class="mb-1">{{$bid->request_number}}</h5>
                    <small>{{$bid->created_at}}</small>
                </div>
            </div>
            <div class="row mx-1 mb-2">
                <div class="col">
                    <p class="mb-1">{{$bid->subject}}, {{$bid->subject_type}}, {{$bid->quantity . ' ' . $bid->unit}}, {{$bid->due_date}}</p>
                    <small>Supplier: {{$bid->name}}</small><br>
                    <small><b>{{$bid->contact}}</b></small>
                </div>
                <div class="col-1 d-flex align-items-center justify-content-end">
                    <h3 class="mb-0">{{$bid->offer_price}}</h3>
                </div>
            </div>
            
            <div class="row ms-2 me-1">
                <div class="col-10 rounded-1 bg-light">
                    <small>{{$bid->agreement_text ?? 'No Agreement Notes'}}</small>
                </div>
                <div class="col-auto flex-fill d-flex align-items-center justify-content-end">
                    <div class="btn-group" role="group">
                            <form action="{{route('prc.bid.update')}}" method="post">
                                @csrf
                                <input type="hidden" name="procurement_bid_id" value="{{$bid->PBID}}">
                                <input type="hidden" name="procurement_request_id" value="{{$bid->PRID}}">
                                <Button type="submit" name="btn" value="accept" class="btn btn-primary">Accept</Button>
                                <Button type="submit" name="btn" value="deny" class="btn btn-danger">Deny</Button>
                            </form>
                        </div>
                </div> 
            </div>
        </div>
        @empty
        <a href="#" class="container list-group-item">
            <h6 class="text-center">No Offers Available</h6>
        </a>
        @endforelse
    </div>
       
</div>

@endsection