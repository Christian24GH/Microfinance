@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">{{ session('error') }}</div>
    @endif
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Claim Submission & Tracking</h1>
        <button onclick="openClaimModal()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
            New Claim
        </button>
    </div>

    <!-- Claims List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="claimsList">
                    @forelse($claims as $claim)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $claim->employee ? $claim->employee->name . ' (' . $claim->employee->employee_id . ')' : '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $claim->claim_type ?? '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $claim->total_amount ?? $claim->amount }} {{ $claim->currency ?? 'PHP' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{
                                    $claim->status === 'approved' ? 'bg-green-100 text-green-800' :
                                    ($claim->status === 'rejected' ? 'bg-red-100 text-red-800' :
                                    ($claim->status === 'paid' ? 'bg-blue-100 text-blue-800' :
                                    'bg-yellow-100 text-yellow-800'))
                                }}">
                                    {{ ucfirst($claim->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $claim->submitted_at ? $claim->submitted_at->format('Y-m-d') : '' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                @if($claim->status === 'pending')
                                    <form action="{{ route('claims.approve', $claim) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                    </form>
                                    <button type="button" class="text-red-600 hover:text-red-900 mr-2" onclick="openRejectModal({{ $claim->id }})">Reject</button>
                                @elseif($claim->status === 'approved')
                                    <form action="{{ route('claims.reimburse', $claim) }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 mr-2">Reimburse</button>
                                    </form>
                                @endif
                                <button onclick="viewClaim({{ $claim->id }})" class="text-blue-600 hover:text-blue-900">View</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No claims found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- New/Edit Claim Modal -->
    <div id="claimModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 id="modalTitle" class="text-xl font-semibold text-gray-900">New Claim</h2>
                <button onclick="closeClaimModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="claimForm" class="p-6" method="POST" action="{{ route('claims.claim_store') }}" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">Employee</label>
                        <select id="employee_id" name="employee_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select an employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->employee_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="claim_type_id" class="block text-sm font-medium text-gray-700 mb-2">Claim Type</label>
                        <select id="claim_type_id" name="claim_type_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Select a type</option>
                            @foreach($claimTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <input type="number" id="amount" name="amount" step="0.01" min="0.01" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency</label>
                        <input type="text" id="currency" name="currency" value="PHP" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">Attachments</label>
                        <input type="file" id="attachments" name="attachments[]" multiple class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">You may upload receipts or supporting documents (max 10MB each).</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="closeClaimModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Submit Claim</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md mx-4">
            <form id="rejectForm" method="POST">
                @csrf
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Reject Claim</h2>
                    <button type="button" onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="p-6">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">Reason for rejection</label>
                    <textarea id="rejection_reason" name="rejection_reason" rows="3" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
                <div class="p-6 flex justify-end space-x-4">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600">Reject</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Claim Modal -->
    <div id="viewClaimModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl mx-4">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-900">Claim Details</h2>
                <button onclick="closeViewClaimModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6" id="claimDetailsContent">
                <!-- Claim details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Prevent accidental GET requests to /claims/{id}/reimburse
if (window.location.pathname.match(/^\/claims\/[0-9]+\/reimburse$/) && window.location.method !== 'POST') {
    alert('You cannot access this page directly. Please use the Reimburse button in the table.');
    window.location.href = '/claims/claim-submission';
}

function openClaimModal() {
    document.getElementById('modalTitle').textContent = 'New Claim';
    document.getElementById('claimForm').reset();
    document.getElementById('claimModal').classList.remove('hidden');
}

function closeClaimModal() {
    document.getElementById('claimModal').classList.add('hidden');
}

let rejectClaimId = null;
function openRejectModal(claimId) {
    rejectClaimId = claimId;
    document.getElementById('rejectForm').action = '/claims/' + claimId + '/reject';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    rejectClaimId = null;
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejection_reason').value = '';
}

function viewClaim(claimId) {
    fetch(`/claims/${claimId}/json`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const claim = data.claim;
                let html = `<div class='mb-4'><strong>Employee:</strong> ${claim.employee_name ?? ''} (${claim.employee_id ?? ''})</div>`;
                html += `<div class='mb-4'><strong>Type:</strong> ${claim.claim_type}</div>`;
                html += `<div class='mb-4'><strong>Amount:</strong> ${claim.total_amount} PHP</div>`;
                html += `<div class='mb-4'><strong>Status:</strong> ${claim.status}</div>`;
                html += `<div class='mb-4'><strong>Submitted:</strong> ${claim.submitted_at ?? ''}</div>`;
                html += `<div class='mb-4'><strong>Description:</strong> ${claim.description ?? ''}</div>`;
                if (claim.attachments && claim.attachments.length > 0) {
                    html += `<div class='mb-4'><strong>Attachments:</strong><ul>`;
                    claim.attachments.forEach(att => {
                        html += `<li><a href='${att.url}' target='_blank'>${att.filename}</a></li>`;
                    });
                    html += `</ul></div>`;
                }
                document.getElementById('claimDetailsContent').innerHTML = html;
                document.getElementById('viewClaimModal').classList.remove('hidden');
            } else {
                alert('Failed to load claim details.');
            }
        })
        .catch(() => alert('Failed to load claim details.'));
}
function closeViewClaimModal() {
    document.getElementById('viewClaimModal').classList.add('hidden');
}
</script>
@endsection
