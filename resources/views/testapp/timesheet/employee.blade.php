@extends('layouts.app')

@section('title', 'Employee Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-4 px-4">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Employee Management</h1>
                    <p class="text-sm text- gray-600 mt-1">Track and manage employee time entries in real-time</p>
                </div>
                <div class="flex gap-3 w-full md:w-auto">
                    <div class="relative flex-1 md:flex-none">
                        <input type="text" id="searchInput" placeholder="Search employees..."
                               class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm transition-all duration-200">
                        <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <button onclick="showAddEmployeeModal()"
                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition-all duration-200 shadow-sm hover:shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Add Employee
                    </button>
                    <button onclick="refreshEmployeeList()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition-all duration-200 shadow-sm hover:shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Add Employee Modal -->
        <div id="addEmployeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="addEmployeeModalTitle" tabindex="-1">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 modal-animate border border-blue-100">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h2 id="addEmployeeModalTitle" class="text-2xl font-bold text-blue-900 flex items-center gap-2">
                            <i class="bi bi-person-plus-fill text-blue-500 text-2xl"></i> Add New Employee
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Fill in the employee details below</p>
                    </div>
                    <button onclick="closeModal('addEmployeeModal')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200 rounded-full p-2" aria-label="Close Add Employee Modal">
                        <i class="bi bi-x-lg text-xl"></i>
                    </button>
                </div>
                <form id="addEmployeeForm" class="p-6 space-y-6" onsubmit="saveEmployee(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                            <input type="text" id="employee_id" name="employee_id" required class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                            <input type="text" id="name" name="name" required class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" id="email" name="email" required class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                            <input type="tel" id="phone" name="phone" required class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                            <select id="department_id" name="department_id" required class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="position_id" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                            <select id="position_id" name="position_id" required class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="">Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" required class="w-full rounded-lg border-blue-200 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('addEmployeeModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-all duration-200">
                            <i class="bi bi-x-lg"></i> Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all duration-200 flex items-center gap-2">
                            <i class="bi bi-person-plus"></i> Save Employee
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Employee Details Modal -->
        <div id="employeeDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="employeeDetailsModalTitle" tabindex="-1">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 id="employeeDetailsModalTitle" class="text-xl font-semibold text-gray-900">Employee Details</h2>
                            <p class="text-sm text-gray-600 mt-1">View and manage employee information</p>
                        </div>
                        <button onclick="closeModal('employeeDetailsModal')" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" aria-label="Close Employee Details Modal">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="employeeDetailsContent" class="p-6">
                    <!-- Content will be dynamically inserted here -->
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 flex items-center gap-1">Total Employees <span title="Total number of employees in the system."><i class="bi bi-info-circle text-blue-400"></i></span></p>
                        <p class="text-2xl font-bold text-gray-900" id="totalEmployees">{{ $employees->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 flex items-center gap-1">Active Today <span title="Number of employees who have clocked in today."><i class="bi bi-info-circle text-green-400"></i></span></p>
                        <p class="text-2xl font-bold text-gray-900" id="activeEmployees">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 flex items-center gap-1">Total Hours Today <span title="Sum of all hours worked by all employees today."><i class="bi bi-info-circle text-yellow-400"></i></span></p>
                        <p class="text-2xl font-bold text-gray-900" id="totalHoursToday">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
        </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 flex items-center gap-1">Pending Approvals <span title="Number of timesheets or leave requests waiting for approval."><i class="bi bi-info-circle text-purple-400"></i></span></p>
                        <p class="text-2xl font-bold text-gray-900" id="pendingApprovals">0</p>
            </div>
            </div>
            </div>
        </div>

        <!-- Employee Grid -->
        <div id="employeeGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($employees as $employee)
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 employee-card flex flex-col justify-between">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center">
                        <div class="avatar">{{ strtoupper(substr($employee->name,0,2)) }}</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $employee->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $employee->position->name ?? 'No Position' }}</p>
                            <p class="text-sm text-gray-500">{{ $employee->department->name ?? 'No Department' }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">{{ $employee->employee_id }}</span>
                </div>
                <div class="flex flex-col gap-2 mt-2">
                    <button onclick="showTimeEntries({{ $employee->id }})"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm shadow-sm hover:shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        View Time Entries
                    </button>
                    <button onclick="editEmployee({{ $employee->id }})"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Edit
                    </button>
                    <button onclick="deleteEmployee({{ $employee->id }})"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H3a1 1 0 000 2h1v10a2 2 0 002 2h8a2 2 0 002-2V6h1a1 1 0 100-2h-2V3a1 1 0 00-1-1H6zm2 3V4h4v1H8zm-2 2h8v10H6V6z" clip-rule="evenodd" />
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Time Entries Modal -->
        <div id="timeEntriesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="timeEntriesModalTitle" tabindex="-1">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl mx-4">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 id="timeEntriesModalTitle" class="text-xl font-semibold text-gray-900">Time     </h2>
                            <p class="text-sm text-gray-600 mt-1">View and manage time tracking details</p>
                        </div>
                        <button onclick="closeModal('timeEntriesModal')" class="text-gray-400 hover:text-gray-600" aria-label="Close Time Entries Modal">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <button id="addTimeEntryBtn" class="mb-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">Add Time Entry</button>
                    <div class="overflow-x-auto rounded-xl border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="timeEntriesBody" class="bg-white divide-y divide-gray-200">
                                <!-- Time entries will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Time Entry Modal -->
        <div id="addTimeEntryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="addTimeEntryModalTitle" tabindex="-1">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 id="addTimeEntryModalTitle" class="text-xl font-semibold text-gray-900">Add Time Entry</h2>
                    <button onclick="closeModal('addTimeEntryModal')" class="text-gray-400 hover:text-gray-600" aria-label="Close Add Time Entry Modal">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="addTimeEntryForm" class="p-6" onsubmit="saveTimeEntry(event)">
                    <div class="mb-4">
                        <label for="add_date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" id="add_date" name="date" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="add_time_in" class="block text-sm font-medium text-gray-700 mb-2">Time In</label>
                        <input type="time" id="add_time_in" name="time_in" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="add_time_out" class="block text-sm font-medium text-gray-700 mb-2">Time Out</label>
                        <input type="time" id="add_time_out" name="time_out" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="add_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea id="add_notes" name="notes" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('addTimeEntryModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Save Entry</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Time Entry Modal -->
        <div id="editTimeEntryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" role="dialog" aria-modal="true" aria-labelledby="editTimeEntryModalTitle" tabindex="-1">
            <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 id="editTimeEntryModalTitle" class="text-xl font-semibold text-gray-900">Edit Time Entry</h2>
                    <button onclick="closeModal('editTimeEntryModal')" class="text-gray-400 hover:text-gray-600" aria-label="Close Edit Time Entry Modal">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <form id="editTimeEntryForm" class="p-6" onsubmit="updateTimeEntry(event)">
                    <input type="hidden" name="entry_id" id="edit_entry_id">
                    <div class="mb-4">
                        <label for="edit_date" class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                        <input type="date" name="date" id="edit_date" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="edit_time_in" class="block text-sm font-medium text-gray-700 mb-2">Time In</label>
                        <input type="time" name="time_in" id="edit_time_in" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="edit_time_out" class="block text-sm font-medium text-gray-700 mb-2">Time Out</label>
                        <input type="time" name="time_out" id="edit_time_out" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="mb-4">
                        <label for="edit_notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" id="edit_notes" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('editTimeEntryModal')" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">Cancel</button>
                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">Update Entry</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    /* World-class card and button polish */
    .employee-card {
        transition: box-shadow 0.25s, transform 0.18s;
        box-shadow: 0 2px 12px 0 rgba(44,60,140,0.07);
        border: 1.5px solid #e5e7eb;
    }
    .employee-card:hover {
        box-shadow: 0 8px 32px 0 rgba(75,197,236,0.13), 0 2px 8px 0 rgba(44,60,140,0.10);
        transform: translateY(-3px) scale(1.012);
        border-color: #4bc5ec;
    }
    .employee-actions button {
        font-size: 1.05rem;
        font-weight: 600;
        border-radius: 0.9rem;
        box-shadow: 0 1px 6px 0 rgba(44,60,140,0.07);
        transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.15s;
    }
    .employee-actions button:active {
        transform: scale(0.97);
    }
    .employee-actions .bg-blue-600 {
        background: linear-gradient(90deg, #2563eb 60%, #4bc5ec 100%);
        color: #fff;
        border: none;
    }
    .employee-actions .bg-blue-600:hover {
        background: linear-gradient(90deg, #1d4ed8 60%, #4bc5ec 100%);
        box-shadow: 0 4px 16px 0 rgba(37,99,235,0.13);
    }
    .employee-actions .bg-gray-100 {
        background: linear-gradient(90deg, #f3f4f6 60%, #e5e7eb 100%);
        color: #374151;
        border: none;
    }
    .employee-actions .bg-gray-100:hover {
        background: #e5e7eb;
        box-shadow: 0 2px 8px 0 rgba(44,60,140,0.10);
    }
    .employee-actions .bg-red-600 {
        background: linear-gradient(90deg, #dc2626 60%, #f87171 100%);
        color: #fff;
        border: none;
    }
    .employee-actions .bg-red-600:hover {
        background: #b91c1c;
        box-shadow: 0 2px 8px 0 rgba(220,38,38,0.13);
    }
    .employee-actions button svg {
        width: 1.25em;
        height: 1.25em;
    }
    /* Modal animation */
    .modal-animate {
        animation: modalFadeIn 0.35s cubic-bezier(.4,0,.2,1);
    }
    @keyframes modalFadeIn {
        0% { opacity: 0; transform: translateY(40px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    /* Toast animation */
    .toast-animate {
        animation: toastFadeIn 0.4s cubic-bezier(.4,0,.2,1);
    }
    @keyframes toastFadeIn {
        0% { opacity: 0; transform: translateY(30px) scale(0.98); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    /* Spinner */
    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #4bc5ec;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        animation: spin 1s linear infinite;
        margin: 2rem auto;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
let currentEmployeeId = null;
let currentEchoChannel = null;
let toastTimeout = null;

// Show loading spinner
function showSpinner() {
    document.getElementById('employeeGrid').innerHTML = '<div class="spinner"></div>';
}

// Real-time updates
function initializeRealTimeUpdates() {
    Echo.private('employees')
        .listen('EmployeeCreated', (e) => {
            refreshEmployeeList();
            showToast('New employee added', 'success');
            updateStats();
        })
        .listen('EmployeeUpdated', (e) => {
            refreshEmployeeList();
            showToast('Employee information updated', 'success');
            updateStats();
        })
        .listen('EmployeeDeleted', (e) => {
            refreshEmployeeList();
            showToast('Employee removed', 'success');
            updateStats();
        });
}

document.addEventListener('DOMContentLoaded', function() {
    initializeRealTimeUpdates();
    updateStats();
});

// Toast notification
function showToast(message, type = 'success') {
    clearTimeout(toastTimeout);
    const container = document.getElementById('toast-container');
    container.innerHTML = `<div class="toast-animate px-6 py-3 rounded-xl shadow-lg mb-2 flex items-center gap-3 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'}">
        <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill'} text-xl"></i>
        <span>${message}</span>
    </div>`;
    toastTimeout = setTimeout(() => { container.innerHTML = ''; }, 2500);
}

// Employee card avatar (initials)
function getInitials(name) {
    return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0,2);
}

// Refresh employee list (with spinner)
function refreshEmployeeList() {
    showSpinner();
    fetch('/api/employees')
        .then(res => res.json())
        .then(data => {
            const employees = data.employees.data || data.employees;
            const grid = document.getElementById('employeeGrid');
            grid.innerHTML = employees.map(emp => `
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 employee-card flex flex-col justify-between">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center">
                            <div class="avatar">${getInitials(emp.name)}</div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">${emp.name}</h3>
                                <p class="text-sm text-gray-600">${emp.position?.name || 'No Position'}</p>
                                <p class="text-sm text-gray-500">${emp.department?.name || 'No Department'}</p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-800 rounded-full">${emp.employee_id}</span>
                    </div>
                    <div class="flex flex-col gap-2 mt-2">
                        <button onclick="showTimeEntries(${emp.id})"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm shadow-sm hover:shadow">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                            </svg>
                            View Time Entries
                        </button>
                        <button onclick="editEmployee(${emp.id})"
                            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                            </svg>
                            Edit
                        </button>
                        <button onclick="deleteEmployee(${emp.id})"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H3a1 1 0 000 2h1v10a2 2 0 002 2h8a2 2 0 002-2V6h1a1 1 0 100-2h-2V3a1 1 0 00-1-1H6zm2 3V4h4v1H8zm-2 2h8v10H6V6z" clip-rule="evenodd" />
                            </svg>
                            Delete
                        </button>
                    </div>
                </div>
            `).join('');
        });
}

function showAddEmployeeModal() {
    document.getElementById('addEmployeeModalTitle').innerHTML = '<i class="bi bi-person-plus-fill text-blue-500 text-2xl"></i> Add New Employee';
    const submitButton = document.querySelector('#addEmployeeForm button[type="submit"]');
    submitButton.innerHTML = '<i class="bi bi-person-plus"></i> Save Employee';
    // Reset form and set onsubmit to saveEmployee
    const form = document.getElementById('addEmployeeForm');
    form.reset();
    form.onsubmit = function(e) {
        e.preventDefault();
        saveEmployee(e);
    };
    document.getElementById('addEmployeeModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('employee_id').focus();
    }, 100);
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    if (modalId === 'timeEntriesModal') {
        // Unsubscribe from the channel when closing the modal
        if (currentEchoChannel) {
            Echo.leave('timesheet.' + currentEmployeeId);
            currentEchoChannel = null;
            currentEmployeeId = null;
        }
    }
}

function saveEmployee(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());

    fetch('/api/employees', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('addEmployeeModal');
            form.reset();
            showToast('Employee added successfully', 'success');
            location.reload();
        } else {
            showToast(data.message || 'Failed to add employee', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to add employee', 'error');
    });
}

function editEmployee(id) {
    fetch(`/api/employees/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const employee = data.employee;
                const form = document.getElementById('addEmployeeForm');
                form.employee_id.value = employee.employee_id;
                form.name.value = employee.name;
                form.email.value = employee.email;
                form.phone.value = employee.phone;
                form.department_id.value = employee.department_id;
                form.position_id.value = employee.position_id;
                form.status.value = employee.status;

                // Set modal title and button
                document.getElementById('addEmployeeModalTitle').innerHTML = '<i class="bi bi-pencil-fill text-blue-500 text-2xl"></i> Edit Employee';
                const submitButton = form.querySelector('button[type="submit"]');
                submitButton.innerHTML = '<i class="bi bi-pencil"></i> Update Employee';

                // Set onsubmit to updateEmployee
                form.onsubmit = function(e) {
                    e.preventDefault();
                    updateEmployee(id, new FormData(form))
                };

                document.getElementById('addEmployeeModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load employee details', 'error');
        });
}

function updateEmployee(id, formData) {
    const data = Object.fromEntries(formData.entries());

    fetch(`/api/employees/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('addEmployeeModal');
            showToast('Employee updated successfully', 'success');
            location.reload();
        } else {
            showToast(data.message || 'Failed to update employee', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to update employee', 'error');
    });
}

function showTimeEntries(employeeId) {
    currentEmployeeId = employeeId;

    // Always show the modal immediately
    document.getElementById('timeEntriesModal').classList.remove('hidden');

    // Unsubscribe from previous channel if exists
    if (currentEchoChannel) {
        Echo.leave('timesheet.' + currentEmployeeId);
    }

    // Subscribe to new channel
    currentEchoChannel = Echo.private('timesheet.' + currentEmployeeId)
        .listen('TimeEntryCreated', (e) => {
            showTimeEntries(currentEmployeeId);
            showToast('New time entry added', 'success');
            updateStats();
        })
        .listen('TimeEntryUpdated', (e) => {
            showTimeEntries(currentEmployeeId);
            showToast('Time entry updated', 'success');
            updateStats();
        })
        .listen('TimeEntryDeleted', (e) => {
            showTimeEntries(currentEmployeeId);
            showToast('Time entry deleted', 'success');
            updateStats();
        });

    fetch(`/api/employees/${employeeId}/time-entries`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const tbody = document.getElementById('timeEntriesBody');
                tbody.innerHTML = '';
                data.time_entries.forEach(entry => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.date}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.time_in}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.time_out || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${entry.total_hours || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                ${entry.status === 'approved' ? 'bg-green-100 text-green-800' :
                                  entry.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                  'bg-red-100 text-red-800'}">
                                ${entry.status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button onclick="editTimeEntry(${entry.id})" class="text-blue-600 hover:underline mr-2">Edit</button>
                            <button onclick="deleteTimeEntry(${entry.id})" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            } else {
                showToast('Failed to load time entries', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load time entries', 'error');
        });
}

function updateStats() {
    fetch('/api/employees/stats')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                document.getElementById('activeEmployees').textContent = data.active_employees;
                document.getElementById('totalHoursToday').textContent = data.total_hours_today;
                document.getElementById('pendingApprovals').textContent = data.pending_approvals;
            }
        })
        .catch(error => {
            console.error('Error updating stats:', error);
        });
}

document.getElementById('addTimeEntryBtn').addEventListener('click', function() {
    document.getElementById('addTimeEntryModal').classList.remove('hidden');
    setTimeout(() => {
        document.getElementById('add_date').focus();
    }, 100);
});

function saveTimeEntry(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    fetch(`/api/employees/${currentEmployeeId}/time-entries`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(Object.fromEntries(formData.entries()))
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('addTimeEntryModal');
            showTimeEntries(currentEmployeeId);
            showToast('Time entry added successfully', 'success');
            form.reset();
        } else {
            showToast(data.message || 'Failed to add time entry', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to add time entry', 'error');
    });
}

function editTimeEntry(entryId) {
    fetch(`/api/employees/${currentEmployeeId}/time-entries`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const entry = data.time_entries.find(e => e.id === entryId);
                if (entry) {
                    document.getElementById('edit_entry_id').value = entry.id;
                    document.getElementById('edit_date').value = entry.date;
                    document.getElementById('edit_time_in').value = entry.time_in;
                    document.getElementById('edit_time_out').value = entry.time_out || '';
                    document.getElementById('edit_notes').value = entry.notes || '';
                    document.getElementById('editTimeEntryModal').classList.remove('hidden');
                    setTimeout(() => {
                        document.getElementById('edit_date').focus();
                    }, 100);
                }
            }
        });
}

function updateTimeEntry(event) {
    event.preventDefault();
    const form = event.target;
    const entryId = document.getElementById('edit_entry_id').value;
    const data = {
        date: document.getElementById('edit_date').value,
        time_in: document.getElementById('edit_time_in').value,
        time_out: document.getElementById('edit_time_out').value,
        notes: document.getElementById('edit_notes').value
    };
    fetch(`/api/employees/${currentEmployeeId}/time-entries/${entryId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            closeModal('editTimeEntryModal');
            showTimeEntries(currentEmployeeId);
            showToast('Time entry updated successfully', 'success');
        } else {
            showToast(data.message || 'Failed to update time entry', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to update time entry', 'error');
    });
}

function deleteTimeEntry(entryId) {
    if (!confirm('Are you sure you want to delete this time entry?')) return;
    fetch(`/api/employees/${currentEmployeeId}/time-entries/${entryId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showTimeEntries(currentEmployeeId);
            showToast('Time entry deleted successfully', 'success');
        } else {
            showToast(data.message || 'Failed to delete time entry', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to delete time entry', 'error');
    });
}

function deleteEmployee(id) {
    if (!confirm('Are you sure you want to delete this employee?')) return;
    fetch(`/api/employees/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showToast('Employee deleted successfully', 'success');
            location.reload();
        } else {
            showToast(data.message || 'Failed to delete employee', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Failed to delete employee', 'error');
    });
}
</script>
@endsection



