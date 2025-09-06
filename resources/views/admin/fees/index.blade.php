@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Banner Header -->
    <div class="banner bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 mb-6 rounded-xl shadow-sm">
        <h1 class="text-2xl sm:text-3xl font-bold">Fees Management System</h1>
        <p class="text-indigo-100 mt-2">Manage student fees and payments</p>
    </div>

    <div class="admin-container">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <h2 class="text-xl font-bold text-gray-800">Fees Management</h2>
            <button onclick="openModal('assignFeeModal')" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Assign New Fee
            </button>
        </div>

        <!-- Academic Year Filter -->
        <div class="academic-year-filter mb-6">
            <form method="GET" action="{{ route('admin.fees.index') }}" class="flex items-center gap-4">
                <label for="academic_year" class="form-label mb-0">Filter by Academic Year:</label>
                <select id="academic_year" name="academic_year" onchange="this.form.submit()" class="form-input w-auto">
                    <option value="all" {{ empty($selected_year) || $selected_year == 'all' ? 'selected' : '' }}>All Academic Years</option>
                    @foreach($academic_years as $year)
                        <option value="{{ $year }}" {{ $selected_year == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        <!-- Statistics Dashboard -->
        <div class="dashboard-container grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="stats-card bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Fees Due {{ !empty($selected_year) && $selected_year != 'all' ? "($selected_year)" : '' }}</h3>
                <div class="stats-value text-2xl font-bold text-indigo-600">${{ number_format($remaining_fees_due, 2) }}</div>
                <div class="stats-detail text-sm text-gray-500 mt-2">
                    From {{ $stats->total_records }} fee records
                    @if(!empty($selected_year) && $selected_year != 'all')
                        <div class="filter-indicator flex items-center mt-1">
                            <i class="fas fa-filter text-xs mr-1"></i> Filtered by: {{ $selected_year }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="stats-card bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Fees Collected {{ !empty($selected_year) && $selected_year != 'all' ? "($selected_year)" : '' }}</h3>
                <div class="stats-value text-2xl font-bold text-green-600">${{ number_format($stats->total_fees_collected, 2) }}</div>
                <div class="stats-detail text-sm text-gray-500 mt-2">
                    @if($stats->total_fees_due > 0)
                        {{ number_format(($stats->total_fees_collected / $stats->total_fees_due) * 100, 1) }}% collected
                    @else
                        No fees due
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Breakdown -->
        <div class="status-breakdown grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="status-item bg-white rounded-lg shadow p-4 text-center">
                <span class="status-indicator status-pending inline-block w-3 h-3 rounded-full mb-2"></span>
                <span class="status-count font-semibold">{{ $stats->pending_count }} Pending</span>
            </div>
            <div class="status-item bg-white rounded-lg shadow p-4 text-center">
                <span class="status-indicator status-partial inline-block w-3 h-3 rounded-full mb-2"></span>
                <span class="status-count font-semibold">{{ $stats->partial_count }} Partial</span>
            </div>
            <div class="status-item bg-white rounded-lg shadow p-4 text-center">
                <span class="status-indicator status-paid inline-block w-3 h-3 rounded-full mb-2"></span>
                <span class="status-count font-semibold">{{ $stats->paid_count }} Paid</span>
            </div>
            <div class="status-item bg-white rounded-lg shadow p-4 text-center">
                <span class="status-indicator status-overdue inline-block w-3 h-3 rounded-full mb-2"></span>
                <span class="status-count font-semibold">{{ $stats->overdue_count }} Overdue</span>
            </div>
        </div>

        <!-- Payment Form -->
        <div class="payment-form bg-white rounded-lg shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Record Payment</h3>
            <form action="{{ route('admin.fees.payment', 0) }}" method="POST" id="paymentForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="fee_id" class="form-label">Select Fee Record</label>
                        <select id="fee_id" name="fee_id" class="form-input" required>
                            <option value="">Select Fee Record</option>
                            @foreach($fees as $fee)
                                <option value="{{ $fee->fee_id }}">
                                    {{ $fee->student->name }} - {{ $fee->fee_type }} (${{ number_format($fee->amount, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paid_amount" class="form-label">Amount Paid</label>
                        <input type="number" id="paid_amount" name="paid_amount" step="0.01" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="payment_method" class="form-label">Payment Method</label>
                        <select id="payment_method" name="payment_method" class="form-input" required>
                            <option value="">Select Method</option>
                            <option value="Cash">Cash</option>
                            <option value="Check">Check</option>
                            <option value="Bank Transfer">Bank Transfer</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="Mobile Money">Mobile Money</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="reference_number" class="form-label">Reference Number</label>
                        <input type="text" id="reference_number" name="reference_number" class="form-input">
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-money-bill-wave mr-2"></i> Record Payment
                    </button>
                </div>
            </form>
        </div>






        <!-- Search and Filter Section -->
<div class="search-section bg-white rounded-lg shadow-sm p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    id="feeSearchInput" 
                    placeholder="Search fees by student, fee type, status, amount..." 
                    class="search-input pl-10 w-full"
                >
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <button id="clearSearch" class="text-gray-400 hover:text-gray-600 hidden">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-3">
            <select id="statusFilter" class="filter-select">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="partial">Partial</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
            </select>
            
            <select id="feeTypeFilter" class="filter-select">
                <option value="">All Fee Types</option>
                <option value="Tuition Fee">Tuition Fee</option>
                <option value="Registration Fee">Registration Fee</option>
                <option value="Activity Fee">Activity Fee</option>
                <option value="Technology Fee">Technology Fee</option>
                <option value="Library Fee">Library Fee</option>
            </select>
        </div>
    </div>
    
    <div class="mt-4 flex flex-wrap items-center gap-4">
        <span class="text-sm text-gray-600" id="searchResultsCount">
            Showing {{ count($fees) }} records
        </span>
        <button id="resetFilters" class="text-sm text-blue-600 hover:text-blue-800">
            <i class="fas fa-refresh mr-1"></i> Reset Filters
        </button>
    </div>
</div>

        <!-- Fees Table -->
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Fee Records</h3>
        <div class="table-container bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Installment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Due Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($fees as $fee)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->student->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->fee_type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->installment_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($fee->amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${{ number_format($fee->paid_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $fee->due_date->format('M j, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="status-badge status-{{ $fee->status }} px-2 py-1 rounded-full text-xs">
                                    {{ ucfirst($fee->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex space-x-2">
                                    <button onclick="viewFee({{ $fee->fee_id }})" class="btn-view">
                                        <i class="fas fa-eye mr-1"></i> View
                                    </button>
                                    <button onclick="editFee({{ $fee->fee_id }})" class="btn-edit">
                                        <i class="fas fa-edit mr-1"></i> Edit
                                    </button>
                                    <button onclick="confirmDelete({{ $fee->fee_id }}, '{{ addslashes($fee->student->name . ' - ' . $fee->fee_type) }}')" class="btn-delete">
                                        <i class="fas fa-trash mr-1"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Assign Fee Modal -->
<div id="assignFeeModal" class="modal">
    <div class="modal-content bg-white rounded-lg shadow-xl max-w-2xl">
        <div class="modal-header bg-indigo-600 text-white p-4 rounded-t-lg">
            <h3 class="text-xl font-semibold">Assign New Fee</h3>
            <button class="modal-close text-white" onclick="closeModal('assignFeeModal')">&times;</button>
        </div>
        <div class="modal-body p-6">
            <form action="{{ route('admin.fees.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="student_id" class="form-label">Student</label>
                        <select id="student_id" name="student_id" class="form-input" required>
                            <option value="">Select Student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->student_id }}">
                                    {{ $student->name }} ({{ $student->student_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="fee_type" class="form-label">Fee Type</label>
                        <select id="fee_type" name="fee_type" class="form-input" required>
                            <option value="">Select Fee Type</option>
                            <option value="Tuition Fee">Tuition Fee</option>
                            <option value="Registration Fee">Registration Fee</option>
                            <option value="Activity Fee">Activity Fee</option>
                            <option value="Technology Fee">Technology Fee</option>
                            <option value="Library Fee">Library Fee</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="installment_number" class="form-label">Installment Number</label>
                        <select id="installment_number" name="installment_number" class="form-input">
                            <option value="1">1st Installment</option>
                            <option value="2">2nd Installment</option>
                            <option value="3">3rd Installment</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="academic_year" class="form-label">Academic Year</label>
                        <input type="text" id="academic_year" name="academic_year" value="{{ date('Y') . '/' . (date('Y') + 1) }}" class="form-input" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" id="amount" name="amount" step="0.01" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" id="due_date" name="due_date" class="form-input" required>
                    </div>
                </div>
                <div class="modal-footer bg-gray-50 p-4 rounded-b-lg flex justify-end space-x-3">
                    <button type="button" class="btn-cancel" onclick="closeModal('assignFeeModal')">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i> Assign Fee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteFeeModal" class="modal">
    <div class="modal-content bg-white rounded-lg shadow-xl max-w-md">
        <div class="modal-header bg-red-600 text-white p-4 rounded-t-lg">
            <h3 class="text-xl font-semibold">Confirm Delete</h3>
            <button class="modal-close text-white" onclick="closeModal('deleteFeeModal')">&times;</button>
        </div>
        <div class="modal-body p-6">
            <p>Are you sure you want to delete the fee record for:</p>
            <p id="delete-fee-details" class="font-bold my-3 p-3 bg-gray-50 rounded"></p>
            <p class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>This action cannot be undone.</p>
        </div>
        <div class="modal-footer bg-gray-50 p-4 rounded-b-lg flex justify-end space-x-3">
            <button type="button" class="btn-cancel" onclick="closeModal('deleteFeeModal')">
                <i class="fas fa-times mr-2"></i> Cancel
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-permanent">
                    <i class="fas fa-trash mr-2"></i> Delete Permanently
                </button>
            </form>
        </div>
    </div>
</div>

<!-- View Fee Modal -->
<div id="viewFeeModal" class="modal">
    <div class="modal-content bg-white rounded-lg shadow-xl max-w-2xl">
        <div class="modal-header bg-blue-600 text-white p-4 rounded-t-lg flex justify-between items-center">
            <div class="flex items-center">
                <!-- Print Icon -->
                <button onclick="enhancedPrintFeeDetails()" class="print-btn mr-3" title="Print Fee Details">
               <i class="fas fa-print"></i>
                </button>
                <h3 class="text-xl font-semibold">Fee Details</h3>
            </div>
            <button class="modal-close text-white" onclick="closeModal('viewFeeModal')">&times;</button>
        </div>
        <div class="modal-body p-6">
            <!-- Printable content container -->
            <div id="printable-fee-details">
                <div class="print-header hidden">
                    <h2 class="text-2xl font-bold text-center mb-4">Fee Details Receipt</h2>
                    <div class="border-b-2 border-gray-300 mb-4"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="detail-group">
                        <label class="detail-label">Student:</label>
                        <p class="detail-value" id="view-student">Loading...</p>
                    </div>
                    <div class="detail-group">
                        <label class="detail-label">Fee Type:</label>
                        <p class="detail-value" id="view-fee-type">Loading...</p>
                    </div>
                    <div class="detail-group">
                        <label class="detail-label">Installment:</label>
                        <p class="detail-value" id="view-installment">Loading...</p>
                    </div>
                    <div class="detail-group">
                        <label class="detail-label">Academic Year:</label>
                        <p class="detail-value" id="view-academic-year">Loading...</p>
                    </div>
                    <div class="detail-group">
                        <label class="detail-label">Amount:</label>
                        <p class="detail-value" id="view-amount">Loading...</p>
                    </div>
                    <div class="detail-group">
                        <label class="detail-label">Paid Amount:</label>
                        <p class="detail-value" id="view-paid-amount">Loading...</p>
                    </div>
                    <div class="detail-group">
                        <label class="detail-label">Due Date:</label>
                        <p class="detail-value" id="view-due-date">Loading...</p>
                    </div>
                    <div class="detail-group">
                        <label class="detail-label">Status:</label>
                        <p class="detail-value" id="view-status">Loading...</p>
                    </div>
                </div>

                <!-- Payment History Section -->
                <div class="payment-history mt-6">
                    <h4 class="text-lg font-semibold mb-3">Payment History</h4>
                    <div id="payment-history-container">
                        <!-- Payment history will be dynamically inserted here -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer bg-gray-50 p-4 rounded-b-lg flex justify-end">
            <button type="button" class="btn-cancel" onclick="closeModal('viewFeeModal')">
                <i class="fas fa-times mr-2"></i> Close
            </button>
        </div>
    </div>
</div>

<!-- Edit Fee Modal -->
<div id="editFeeModal" class="modal">
    <div class="modal-content bg-white rounded-lg shadow-xl max-w-2xl">
        <div class="modal-header bg-indigo-600 text-white p-4 rounded-t-lg">
            <h3 class="text-xl font-semibold">Edit Fee</h3>
            <button class="modal-close text-white" onclick="closeModal('editFeeModal')">&times;</button>
        </div>
        <div class="modal-body p-6">
            <form id="editFeeForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit-fee-id" name="fee_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="edit-fee-type" class="form-label">Fee Type</label>
                        <select id="edit-fee-type" name="fee_type" class="form-input" required>
                            <option value="">Select Fee Type</option>
                            <option value="Tuition Fee">Tuition Fee</option>
                            <option value="Registration Fee">Registration Fee</option>
                            <option value="Activity Fee">Activity Fee</option>
                            <option value="Technology Fee">Technology Fee</option>
                            <option value="Library Fee">Library Fee</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit-installment" class="form-label">Installment</label>
                        <select id="edit-installment" name="installment_number" class="form-input" required>
                            <option value="1">1st Installment</option>
                            <option value="2">2nd Installment</option>
                            <option value="3">3rd Installment</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="edit-amount" class="form-label">Amount ($)</label>
                        <input type="number" id="edit-amount" name="amount" step="0.01" min="0" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-paid-amount" class="form-label">Paid Amount ($)</label>
                        <input type="number" id="edit-paid-amount" name="paid_amount" step="0.01" min="0" class="form-input">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label for="edit-due-date" class="form-label">Due Date</label>
                        <input type="date" id="edit-due-date" name="due_date" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-status" class="form-label">Status</label>
                        <select id="edit-status" name="status" class="form-input" required>
                            <option value="pending">Pending</option>
                            <option value="partial">Partial</option>
                            <option value="paid">Paid</option>
                            <option value="overdue">Overdue</option>
                        </select>
                    </div>
                </div>
                
                <div class="modal-footer bg-gray-50 p-4 rounded-b-lg flex justify-end space-x-3">
                    <button type="button" class="btn-cancel" onclick="closeModal('editFeeModal')">
                        <i class="fas fa-times mr-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i> Update Fee
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Enhanced Status Colors */
    .status-pending { background-color: #ffedd5; color: #9a3412; border: 1px solid #fed7aa; }
    .status-partial { background-color: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
    .status-paid { background-color: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .status-overdue { background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    
    .status-indicator {
        width: 0.75rem;
        height: 0.75rem;
        border-radius: 9999px;
        display: inline-block;
    }
    .status-indicator.status-pending { background-color: #f59e0b; }
    .status-indicator.status-partial { background-color: #3b82f6; }
    .status-indicator.status-paid { background-color: #10b981; }
    .status-indicator.status-overdue { background-color: #ef4444; }
    
    /* Button Styles */
    .btn-view { 
        background-color: #3b82f6; 
        color: white; 
        padding: 0.5rem 1rem; 
        border-radius: 0.375rem; 
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-view:hover { background-color: #2563eb; transform: translateY(-1px); }
    
    .btn-edit { 
        background-color: #f59e0b; 
        color: white; 
        padding: 0.5rem 1rem; 
        border-radius: 0.375rem; 
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-edit:hover { background-color: #d97706; transform: translateY(-1px); }
    
    .btn-delete { 
        background-color: #ef4444; 
        color: white; 
        padding: 0.5rem 1rem; 
        border-radius: 0.375rem; 
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
    }
    .btn-delete:hover { background-color: #dc2626; transform: translateY(-1px); }
    
    .btn-primary { 
        background-color: #4f46e5; 
        color: white; 
        padding: 0.75rem 1.5rem; 
        border-radius: 0.5rem; 
        font-weight: 600;
        transition: all 0.2s;
        border: none;
    }
    .btn-primary:hover { background-color: #4338ca; transform: translateY(-1px); }
    
    .btn-cancel { 
        background-color: #ef4444; 
        color: white; 
        padding: 0.75rem 1.5rem; 
        border-radius: 0.5rem; 
        font-weight: 600;
        transition: all 0.2s;
        border: none;
    }
    .btn-cancel:hover { background-color: #dc2626; transform: translateY(-1px); }
    
    .btn-delete-permanent { 
        background-color: #b91c1c; 
        color: white; 
        padding: 0.75rem 1.5rem; 
        border-radius: 0.5rem; 
        font-weight: 600;
        transition: all 0.2s;
        border: none;
    }
    .btn-delete-permanent:hover { background-color: #991b1b; transform: translateY(-1px); }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        margin: 5% auto;
        animation: modalFadeIn 0.3s;
        max-height: 90vh;
        overflow-y: auto;
    }
    
    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-50px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Enhanced Table Styles */
    .table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .table-container table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .table-container th {
        background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
        padding: 0.75rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: #374151;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .table-container td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f3f4f6;
        color: #374151;
    }
    
    .table-container tr:last-child td {
        border-bottom: none;
    }
    
    .table-container tr:hover {
        background-color: #f9fafb;
    }
    
    /* Detail Groups */
    .detail-group {
        margin-bottom: 1rem;
    }
    
    .detail-label {
        font-weight: 600;
        color: #374151;
        display: block;
        margin-bottom: 0.25rem;
    }
    
    .detail-value {
        color: #6b7280;
        font-weight: 500;
    }





    /* Print Button Styles */
.print-btn {
    background: rgba(255, 255, 255, 0.2);
    padding: 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.print-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.1);
}

/* Print Styles */
@media print {
    body * {
        visibility: hidden;
    }
    
    #printable-fee-details,
    #printable-fee-details * {
        visibility: visible;
    }
    
    #printable-fee-details {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        padding: 20px;
    }
    
    .print-header {
        display: block !important;
        margin-bottom: 20px;
    }
    
    .detail-group {
        margin-bottom: 10px;
        page-break-inside: avoid;
    }
    
    .detail-label {
        font-weight: bold;
        color: #000 !important;
    }
    
    .detail-value {
        color: #000 !important;
    }
    
    /* Hide non-essential elements */
    .modal-header, .modal-footer, .print-btn,
    .payment-history h4 {
        display: none !important;
    }
}
</style>

<script>
// Modal Functions
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
    document.body.style.overflow = 'auto';
}

function confirmDelete(feeId, feeDetails) {
    document.getElementById('delete-fee-details').textContent = feeDetails;
    document.getElementById('deleteForm').action = '/admin/fees/' + feeId;
    openModal('deleteFeeModal');
}

// View Fee Function - Fetch real data from server
async function viewFee(feeId) {
    try {
        console.log('Fetching fee details for ID:', feeId);
        
        // Show loading states
        setViewModalLoading(true);
        openModal('viewFeeModal');
        
        // Fetch real data from server
        const response = await fetch(`/admin/fees/${feeId}/details`);
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            // Try to get error details from response
            const errorText = await response.text();
            console.error('Server response:', errorText);
            
            let errorMessage = `Server returned ${response.status} status`;
            try {
                const errorData = JSON.parse(errorText);
                errorMessage = errorData.message || errorData.error || errorMessage;
            } catch (e) {
                // If not JSON, use the raw text
                if (errorText) {
                    errorMessage = errorText;
                }
            }
            
            throw new Error(errorMessage);
        }
        
        const feeData = await response.json();
        console.log('Fee data received:', feeData);
        
        // Update modal with real data
        updateViewModalWithData(feeData);
        
    } catch (error) {
        console.error('Error fetching fee details:', error);
        alert('Error loading fee details: ' + error.message);
        closeModal('viewFeeModal');
    }
}

// Edit Fee Function - Fetch real data from server
async function editFee(feeId) {
    try {
        console.log('Fetching edit data for fee ID:', feeId);
        
        // Fetch real data from server
        const response = await fetch(`/admin/fees/${feeId}/edit`);
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            // Try to get error details from response
            const errorText = await response.text();
            console.error('Server response:', errorText);
            
            let errorMessage = `Server returned ${response.status} status`;
            try {
                const errorData = JSON.parse(errorText);
                errorMessage = errorData.message || errorData.error || errorMessage;
            } catch (e) {
                // If not JSON, use the raw text
                if (errorText) {
                    errorMessage = errorText;
                }
            }
            
            throw new Error(errorMessage);
        }
        
        const feeData = await response.json();
        console.log('Edit data received:', feeData);
        
        // Populate form with real data
        populateEditForm(feeData);
        openModal('editFeeModal');
        
    } catch (error) {
        console.error('Error fetching fee data for editing:', error);
        alert('Error loading fee data for editing: ' + error.message);
    }
}

// Helper functions
function setViewModalLoading(loading) {
    const elements = [
        'view-student', 'view-fee-type', 'view-installment', 
        'view-academic-year', 'view-amount', 'view-paid-amount', 
        'view-due-date', 'view-status'
    ];
    
    elements.forEach(id => {
        document.getElementById(id).textContent = loading ? 'Loading...' : '';
    });
}

function updateViewModalWithData(feeData) {
    document.getElementById('view-student').textContent = `${feeData.student.name} (ID: ${feeData.student.student_id})`;
    document.getElementById('view-fee-type').textContent = feeData.fee_type;
    document.getElementById('view-installment').textContent = `${feeData.installment_number}${getOrdinalSuffix(feeData.installment_number)} Installment`;
    document.getElementById('view-academic-year').textContent = feeData.academic_year;
    document.getElementById('view-amount').textContent = `$${parseFloat(feeData.amount).toFixed(2)}`;
    document.getElementById('view-paid-amount').textContent = `$${parseFloat(feeData.paid_amount).toFixed(2)}`;
    document.getElementById('view-due-date').textContent = new Date(feeData.due_date).toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric' 
    });
    document.getElementById('view-status').innerHTML = `<span class="status-badge status-${feeData.status}">${feeData.status.charAt(0).toUpperCase() + feeData.status.slice(1)}</span>`;
}

function populateEditForm(feeData) {
    document.getElementById('edit-fee-id').value = feeData.fee_id;
    document.getElementById('edit-fee-type').value = feeData.fee_type;
    document.getElementById('edit-installment').value = feeData.installment_number;
    document.getElementById('edit-amount').value = feeData.amount;
    document.getElementById('edit-paid-amount').value = feeData.paid_amount;
    document.getElementById('edit-due-date').value = feeData.due_date;
    document.getElementById('edit-status').value = feeData.status;
    
    document.getElementById('editFeeForm').action = `/admin/fees/${feeData.fee_id}`;
}

function getOrdinalSuffix(number) {
    const suffixes = ['th', 'st', 'nd', 'rd'];
    const value = number % 100;
    return suffixes[(value - 20) % 10] || suffixes[value] || suffixes[0];
}

// Close modal when clicking outside
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
}

// Update payment form action dynamically
document.getElementById('fee_id').addEventListener('change', function() {
    const feeId = this.value;
    const form = document.getElementById('paymentForm');
    form.action = `/admin/fees/${feeId}/payment`;
});

</script>








<script>
// Enhanced print function with school logo
function enhancedPrintFeeDetails() {
    const printableContent = document.getElementById('printable-fee-details').cloneNode(true);
    const printHeader = printableContent.querySelector('.print-header');
    
    if (printHeader) {
        printHeader.classList.remove('hidden');
    }
    
    const printWindow = window.open('', '_blank', 'width=800,height=600');
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Fee Details Receipt - ${document.querySelector('.school-name')?.textContent || 'EDMOL Baptist'}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 25px; color: #000; }
                .header { text-align: center; margin-bottom: 25px; }
                .school-info { margin-bottom: 15px; }
                .school-name { font-size: 24px; font-weight: bold; margin-bottom: 5px; }
                .receipt-title { font-size: 20px; margin: 15px 0; color: #2c5282; }
                .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 20px 0; }
                .detail-group { margin-bottom: 12px; page-break-inside: avoid; }
                .detail-label { font-weight: bold; color: #000; }
                .detail-value { color: #000; }
                .payment-history { margin-top: 25px; border-top: 1px solid #ccc; padding-top: 15px; }
                .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
                @media print { body { margin: 15mm; } }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="school-info">
                    <div class="school-name">${document.querySelector('.school-name')?.textContent || 'EDMOL Baptist'}</div>
                    <div>Fee Details Receipt</div>
                </div>
                <div style="border-bottom: 2px solid #333; margin: 15px 0;"></div>
            </div>
            ${printableContent.innerHTML}
            <div class="footer">
                Printed on ${new Date().toLocaleDateString()} at ${new Date().toLocaleTimeString()}
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    
    printWindow.onload = function() {
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
            // Don't close immediately - let user check print preview
            // printWindow.close();
        }, 250);
    };
}
</script>








<style>
/* Search and Filter Styles */
.search-section {
    background: linear-gradient(to right, #f8fafc, #f1f5f9);
    border: 1px solid #e2e8f0;
}

.search-input {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-input:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.filter-select {
    padding: 10px 12px;
    border: 2px solid #e2e8f0;
    border-radius: 6px;
    background: white;
    font-size: 14px;
    min-width: 140px;
    transition: border-color 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: #3b82f6;
}

/* Highlight animation for results count */
.highlight {
    animation: highlightFade 0.5s ease;
}

@keyframes highlightFade {
    0% { color: #3b82f6; transform: scale(1.05); }
    100% { color: #6b7280; transform: scale(1); }
}

/* Responsive design for filters */
@media (max-width: 768px) {
    .search-section {
        padding: 1rem;
    }
    
    .filter-select {
        min-width: 120px;
        font-size: 13px;
    }
    
    .flex-wrap {
        justify-content: flex-start;
    }
}

/* Hover effects for buttons */
#clearSearch, #resetFilters {
    transition: all 0.2s ease;
}

#clearSearch:hover {
    color: #ef4444;
}

#resetFilters:hover {
    text-decoration: underline;
}
</style>



<script>
    // Search and Filter functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('feeSearchInput');
    const statusFilter = document.getElementById('statusFilter');
    const feeTypeFilter = document.getElementById('feeTypeFilter');
    const clearSearchBtn = document.getElementById('clearSearch');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const searchResultsCount = document.getElementById('searchResultsCount');
    
    const tableRows = document.querySelectorAll('tbody tr');
    let activeFilters = {
        search: '',
        status: '',
        feeType: ''
    };
    
    // Initial count
    updateResultsCount(tableRows.length);
    
    // Search input event
    searchInput.addEventListener('input', function(e) {
        activeFilters.search = e.target.value.toLowerCase();
        clearSearchBtn.classList.toggle('hidden', !e.target.value);
        filterTable();
    });
    
    // Status filter event
    statusFilter.addEventListener('change', function(e) {
        activeFilters.status = e.target.value;
        filterTable();
    });
    
    // Fee type filter event
    feeTypeFilter.addEventListener('change', function(e) {
        activeFilters.feeType = e.target.value;
        filterTable();
    });
    
    // Clear search button
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        activeFilters.search = '';
        clearSearchBtn.classList.add('hidden');
        filterTable();
    });
    
    // Reset all filters
    resetFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        feeTypeFilter.value = '';
        activeFilters = {
            search: '',
            status: '',
            feeType: ''
        };
        clearSearchBtn.classList.add('hidden');
        filterTable();
    });
    
    // Filter table function
    function filterTable() {
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const studentName = row.cells[0].textContent.toLowerCase();
            const feeType = row.cells[1].textContent.toLowerCase();
            const installment = row.cells[2].textContent.toLowerCase();
            const amount = row.cells[3].textContent.toLowerCase();
            const paidAmount = row.cells[4].textContent.toLowerCase();
            const dueDate = row.cells[5].textContent.toLowerCase();
            const status = row.cells[6].textContent.toLowerCase();
            
            // Check search term
            const matchesSearch = !activeFilters.search || 
                studentName.includes(activeFilters.search) ||
                feeType.includes(activeFilters.search) ||
                installment.includes(activeFilters.search) ||
                amount.includes(activeFilters.search) ||
                paidAmount.includes(activeFilters.search) ||
                dueDate.includes(activeFilters.search) ||
                status.includes(activeFilters.search);
            
            // Check status filter
            const matchesStatus = !activeFilters.status || 
                status.includes(activeFilters.status.toLowerCase());
            
            // Check fee type filter
            const matchesFeeType = !activeFilters.feeType || 
                feeType.includes(activeFilters.feeType.toLowerCase());
            
            // Show/hide row based on filters
            if (matchesSearch && matchesStatus && matchesFeeType) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });
        
        updateResultsCount(visibleCount);
    }
    
    function updateResultsCount(count) {
        searchResultsCount.textContent = `Showing ${count} record${count !== 1 ? 's' : ''}`;
        
        // Add highlight animation when count changes
        searchResultsCount.classList.add('highlight');
        setTimeout(() => {
            searchResultsCount.classList.remove('highlight');
        }, 500);
    }
    
    // Add keyboard shortcut for search (Ctrl/Cmd + F)
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            searchInput.focus();
        }
    });
});
</script>
@endsection