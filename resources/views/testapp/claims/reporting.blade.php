@extends('layouts.app')

@section('title', 'Claims Reporting & Analytics')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="container mx-auto py-4 px-4">
        <!-- Header Section -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Claims Reporting & Analytics</h1>
                    <p class="text-sm text-gray-600 mt-1">Track and analyze claim data</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="exportReport()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 text-sm transition-all duration-200 shadow-sm hover:shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Export Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                    <select id="date_range" onchange="updateFilters()"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="this_week">This Week</option>
                        <option value="last_week">Last Week</option>
                        <option value="this_month" selected>This Month</option>
                        <option value="last_month">Last Month</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div id="customDateRange" class="hidden md:col-span-2">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                            <input type="date" id="start_date" onchange="updateFilters()"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                            <input type="date" id="end_date" onchange="updateFilters()"
                                   class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        </div>
                    </div>
                </div>
                <div>
                    <label for="claim_type" class="block text-sm font-medium text-gray-700 mb-2">Claim Type</label>
                    <select id="claim_type" onchange="updateFilters()"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="">All Types</option>
                        <option value="travel">Travel</option>
                        <option value="medical">Medical</option>
                        <option value="office">Office Supplies</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Claims</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalClaims">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Approved Claims</p>
                        <p class="text-2xl font-bold text-gray-900" id="approvedClaims">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rejected Claims</p>
                        <p class="text-2xl font-bold text-gray-900" id="rejectedClaims">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-all duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                        <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Amount</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalAmount">₱0.00</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Claims by Type</h3>
                <canvas id="claimsByTypeChart"></canvas>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Claims by Status</h3>
                <canvas id="claimsByStatusChart"></canvas>
            </div>
        </div>

        <!-- Claims Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Claim Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="claimsBody" class="bg-white divide-y divide-gray-200">
                        <!-- Claims will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed bottom-4 right-4 z-50"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let claimsByTypeChart = null;
let claimsByStatusChart = null;

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    updateFilters();
});

function initializeCharts() {
    // Initialize Claims by Type Chart
    const typeCtx = document.getElementById('claimsByTypeChart').getContext('2d');
    claimsByTypeChart = new Chart(typeCtx, {
        type: 'pie',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#3B82F6',
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Initialize Claims by Status Chart
    const statusCtx = document.getElementById('claimsByStatusChart').getContext('2d');
    claimsByStatusChart = new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#10B981',
                    '#F59E0B',
                    '#EF4444'
                ]
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function updateFilters() {
    const dateRange = document.getElementById('date_range').value;
    const claimType = document.getElementById('claim_type').value;
    let startDate = '';
    let endDate = '';

    if (dateRange === 'custom') {
        document.getElementById('customDateRange').classList.remove('hidden');
        startDate = document.getElementById('start_date').value;
        endDate = document.getElementById('end_date').value;
    } else {
        document.getElementById('customDateRange').classList.add('hidden');
        const dates = getDateRange(dateRange);
        startDate = dates.start;
        endDate = dates.end;
    }

    fetchReportData(startDate, endDate, claimType);
}

function getDateRange(range) {
    const today = new Date();
    let start = new Date();
    let end = new Date();

    switch (range) {
        case 'today':
            break;
        case 'yesterday':
            start.setDate(today.getDate() - 1);
            end.setDate(today.getDate() - 1);
            break;
        case 'this_week':
            start.setDate(today.getDate() - today.getDay());
            break;
        case 'last_week':
            start.setDate(today.getDate() - today.getDay() - 7);
            end.setDate(today.getDate() - today.getDay() - 1);
            break;
        case 'this_month':
            start.setDate(1);
            break;
        case 'last_month':
            start.setMonth(today.getMonth() - 1);
            start.setDate(1);
            end.setDate(0);
            break;
    }

    return {
        start: start.toISOString().split('T')[0],
        end: end.toISOString().split('T')[0]
    };
}

function fetchReportData(startDate, endDate, claimType) {
    fetch(`/api/claims/report?start_date=${startDate}&end_date=${endDate}&claim_type=${claimType}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                updateStats(data.stats);
                updateCharts(data.charts);
                updateClaimsTable(data.claims);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Failed to load report data', 'error');
        });
}

function updateStats(stats) {
    document.getElementById('totalClaims').textContent = stats.total_claims;
    document.getElementById('approvedClaims').textContent = stats.approved_claims;
    document.getElementById('rejectedClaims').textContent = stats.rejected_claims;
    document.getElementById('totalAmount').textContent = '₱' + stats.total_amount.toFixed(2);
}

function updateCharts(charts) {
    // Update Claims by Type Chart
    claimsByTypeChart.data.labels = charts.by_type.labels;
    claimsByTypeChart.data.datasets[0].data = charts.by_type.data;
    claimsByTypeChart.update();

    // Update Claims by Status Chart
    claimsByStatusChart.data.labels = charts.by_status.labels;
    claimsByStatusChart.data.datasets[0].data = charts.by_status.data;
    claimsByStatusChart.update();
}

function updateClaimsTable(claims) {
    const tbody = document.getElementById('claimsBody');
    tbody.innerHTML = '';
    claims.forEach(claim => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${claim.employee.name}</div>
                <div class="text-sm text-gray-500">${claim.employee.employee_id}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${claim.claim_type}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ₱${claim.amount.toFixed(2)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                    ${claim.status === 'approved' ? 'bg-green-100 text-green-800' :
                      claim.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-red-100 text-red-800'}">
                    ${claim.status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${claim.date}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button onclick="viewClaim(${claim.id})" class="text-blue-600 hover:text-blue-900">View</button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function exportReport() {
    const dateRange = document.getElementById('date_range').value;
    const claimType = document.getElementById('claim_type').value;
    let startDate = '';
    let endDate = '';

    if (dateRange === 'custom') {
        startDate = document.getElementById('start_date').value;
        endDate = document.getElementById('end_date').value;
    } else {
        const dates = getDateRange(dateRange);
        startDate = dates.start;
        endDate = dates.end;
    }

    window.location.href = `/api/claims/report/export?start_date=${startDate}&end_date=${endDate}&claim_type=${claimType}`;
}

function viewClaim(id) {
    window.location.href = `/claims/${id}`;
}

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg text-white ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 3000);
}
</script>
@endsection
