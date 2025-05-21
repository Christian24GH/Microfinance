@extends('layouts.app')

@section('title', __('Clocking Management'))

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-blue-100">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-blue-900 tracking-tight">{{ __('Clocking Management') }}</h1>
                        <p class="text-base text-blue-600 mt-1">{{ __('Clock in/out and view your records') }}</p>
                    </div>
                    <div class="hidden md:block">
                        <i class="bi bi-clock-history text-4xl text-blue-400"></i>
                    </div>
                </div>
                <form id="clockingForm" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                    <div>
                        <label for="employee_id" class="block text-sm font-semibold text-blue-800 mb-2">Employee</label>
                        <select id="employee_id" name="employee_id" required class="w-full rounded-xl border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base py-2 px-3">
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="clocking_type" class="block text-sm font-semibold text-blue-800 mb-2">Type</label>
                        <select id="clocking_type" name="clocking_type" required class="w-full rounded-xl border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base py-2 px-3">
                            <option value="IN">Clock In</option>
                            <option value="OUT">Clock Out</option>
                            <option value="BREAK_START">Break Start</option>
                            <option value="BREAK_END">Break End</option>
                        </select>
                    </div>
                    <div>
                        <label for="clocking_time" class="block text-sm font-semibold text-blue-800 mb-2">Date & Time</label>
                        <div class="relative">
                            <input type="text" id="clocking_time" name="clocking_time" required placeholder="Select date & time" class="w-full rounded-xl border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-base py-2 pl-10 pr-3" autocomplete="off">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-blue-400 pointer-events-none">
                                <i class="bi bi-calendar-event"></i>
                            </span>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="w-full py-2 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold shadow transition-all duration-200">Submit</button>
                    </div>
                </form>
            </div>
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                <h2 class="text-xl font-bold text-blue-900 mb-4 flex items-center gap-2">
                    <i class="bi bi-list-check text-blue-400"></i> Clocking Records
                </h2>
                <div class="overflow-x-auto rounded-xl border border-blue-100">
                    <table class="min-w-full divide-y divide-blue-100">
                        <thead class="bg-blue-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Date & Time</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-blue-700 uppercase tracking-wider">Total Hours</th>
                            </tr>
                        </thead>
                        <tbody id="clockingRecordsBody" class="bg-white divide-y divide-blue-50">
                            <!-- Records will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Flatpickr CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/all.js"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
let clockingRecords = [];

function calculateTotalHours(records) {
    // Map employee_id to last IN record
    const lastIn = {};
    const result = [];
    for (const record of records.slice().reverse()) { // reverse for chronological
        if (record.clocking_type === 'IN') {
            lastIn[record.employee_id] = record;
        } else if (record.clocking_type === 'OUT' && lastIn[record.employee_id]) {
            // Calculate duration
            const inTime = new Date(lastIn[record.employee_id].clocking_time);
            const outTime = new Date(record.clocking_time);
            const diffMs = outTime - inTime;
            const hours = (diffMs / 1000 / 60 / 60).toFixed(2);
            record.total_hours = hours > 0 ? hours : '-';
            lastIn[record.employee_id] = null; // Reset after OUT
        }
    }
    // Forward pass to assign total_hours to OUT records
    for (const record of records) {
        if (record.clocking_type === 'OUT' && record.total_hours) {
            result.push({ ...record, total_hours: record.total_hours });
        } else if (record.clocking_type === 'IN') {
            result.push({ ...record, total_hours: '-' });
        } else {
            result.push({ ...record, total_hours: '-' });
        }
    }
    return result;
}

document.addEventListener('DOMContentLoaded', function() {
    flatpickr('#clocking_time', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i',
        time_24hr: !(new Date().toLocaleTimeString().toLowerCase().includes('am') || new Date().toLocaleTimeString().toLowerCase().includes('pm')),
        locale: navigator.language || 'en',
        altInput: true,
        altFormat: 'F j, Y h:i K',
        allowInput: true,
        wrap: false,
        defaultHour: new Date().getHours(),
        defaultMinute: new Date().getMinutes(),
    });
    loadClockingRecords();
    document.getElementById('clockingForm').addEventListener('submit', submitClockingForm);
    // Listen for realtime events
    if (window.Echo) {
        Echo.private('clocking')
            .listen('ClockingRecorded', (e) => { loadClockingRecords(); })
            .listen('ClockingUpdated', (e) => { loadClockingRecords(); })
            .listen('ClockingDeleted', (e) => { loadClockingRecords(); });
    } else {
        setInterval(loadClockingRecords, 5000);
    }
});

function submitClockingForm(event) {
    event.preventDefault();
    const form = event.target;
    const data = {
        employee_id: form.employee_id.value,
        clocking_type: form.clocking_type.value,
        clocking_time: form.clocking_time.value,
        timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
    };
    fetch('/timeattendance/clocking/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Clocking recorded successfully!', 'success');
            // No need to call loadClockingRecords, Echo will update
            form.reset();
        } else {
            showToast(data.message || 'Failed to record clocking.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to record clocking.', 'error');
    });
}

function loadClockingRecords() {
    fetch('/timeattendance/clocking/records')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                clockingRecords = data.data || [];
                renderClockingRecords();
            }
        });
}

function renderClockingRecords() {
    const tbody = document.getElementById('clockingRecordsBody');
    tbody.innerHTML = '';
    const recordsWithHours = calculateTotalHours(clockingRecords);
    recordsWithHours.forEach((record, idx) => {
        const avatar = record.employee ? `<span class='inline-flex items-center justify-center h-8 w-8 rounded-full bg-blue-${(idx%2 ? '200' : '400')} text-white font-bold mr-2'>${record.employee.name ? record.employee.name.charAt(0).toUpperCase() : '?'}</span>` : '';
        const row = document.createElement('tr');
        row.className = idx % 2 === 0 ? 'bg-blue-50 transition-all duration-300' : 'transition-all duration-300';
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm flex items-center">${avatar}${record.employee ? record.employee.name + ' <span class=\'text-xs text-blue-400\'>(' + record.employee.employee_id + ')</span>' : '-'}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-900 font-semibold">${record.clocking_type}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${formatDateTime12h(record.clocking_time, record.timezone)}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-700 font-bold">${record.total_hours || '-'}</td>
        `;
        tbody.appendChild(row);
    });
}

function formatDateTime12h(dateTime, timezone) {
    try {
        const date = new Date(dateTime);
        return date.toLocaleString('en-US', { hour12: true });
    } catch {
        return dateTime;
    }
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
