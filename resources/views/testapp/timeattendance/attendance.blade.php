@extends('layouts.app')

@section('title', __('Attendance Tracking'))

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-4 px-4">
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ __('Attendance Tracking') }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ __('View and manage attendance records') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <form id="attendanceFilterForm" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <div>
                    <label for="from" class="block text-sm font-medium text-gray-700 mb-2">{{ __('From') }}</label>
                    <input type="date" id="from" name="from" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="to" class="block text-sm font-medium text-gray-700 mb-2">{{ __('To') }}</label>
                    <input type="date" id="to" name="to" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Status') }}</label>
                    <select id="status" name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">{{ __('All') }}</option>
                        <option value="PRESENT">{{ __('Present') }}</option>
                        <option value="ABSENT">{{ __('Absent') }}</option>
                        <option value="LATE">{{ __('Late') }}</option>
                        <option value="ON_LEAVE">{{ __('On Leave') }}</option>
                        <option value="HOLIDAY">{{ __('Holiday') }}</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all duration-200 w-full">{{ __('Filter') }}</button>
                </div>
            </form>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <h2 class="text-lg font-semibold mb-4">{{ __('Attendance Records') }}</h2>
            <div class="overflow-x-auto rounded-xl border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Status') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Hours Worked') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Late (min)') }}</th>
                        </tr>
                    </thead>
                    <tbody id="attendanceRecordsBody" class="bg-white divide-y divide-gray-200">
                        <!-- Records will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
        <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadAttendanceRecords();
    document.getElementById('attendanceFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        loadAttendanceRecords();
    });
});

function loadAttendanceRecords() {
    const from = document.getElementById('from').value;
    const to = document.getElementById('to').value;
    const status = document.getElementById('status').value;
    let url = '/api/attendance?';
    if (from) url += 'from=' + encodeURIComponent(from) + '&';
    if (to) url += 'to=' + encodeURIComponent(to) + '&';
    if (status) url += 'status=' + encodeURIComponent(status) + '&';
    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('attendanceRecordsBody');
                tbody.innerHTML = '';
                data.data.data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.attendance_date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.status}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.hours_worked ?? '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${record.late_minutes ?? '-'}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        });
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
