@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Employee Management</h2>

    <table id="employeeTable">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Department</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- JS will fill this -->
        </tbody>
    </table>

    <!-- Modal for Time Entries -->
    <div id="timeEntriesModal" style="display:none;">
        <h3>Time Entries</h3>
        <div id="timeEntriesContent"></div>
        <button onclick="closeModal()">Close</button>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/employees.js') }}"></script>
@endsection
