@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid py-4" style="background: #f8f9fa; min-height: 100vh;">
        @include('hr.dashboard')
    </div>
@endsection
