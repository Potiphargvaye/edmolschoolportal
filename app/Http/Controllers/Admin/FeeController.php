<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StudentFee;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class FeeController extends Controller
{
    public function index(Request $request)
    {
        $selected_year = $request->get('academic_year', '');

        // Get statistics
        $statsQuery = StudentFee::select(
            DB::raw('SUM(amount) as total_fees_due'),
            DB::raw('SUM(paid_amount) as total_fees_collected'),
            DB::raw('COUNT(*) as total_records'),
            DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count"),
            DB::raw("SUM(CASE WHEN status = 'partial' THEN 1 ELSE 0 END) as partial_count"),
            DB::raw("SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid_count"),
            DB::raw("SUM(CASE WHEN status = 'overdue' THEN 1 ELSE 0 END) as overdue_count")

        );

        if (!empty($selected_year) && $selected_year != 'all') {
            $statsQuery->where('academic_year', $selected_year);
        }

        $stats = $statsQuery->first();
        $remaining_fees_due = $stats->total_fees_due - $stats->total_fees_collected;

        // Get academic years for filter
        $academic_years = StudentFee::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        // Get fees with students
        $feesQuery = StudentFee::with('student')
            ->orderBy('due_date', 'desc');

        if (!empty($selected_year) && $selected_year != 'all') {
            $feesQuery->where('academic_year', $selected_year);
        }

        $fees = $feesQuery->get();
        $students = Student::orderBy('name')->get();

        return view('admin.fees.index', compact(
            'stats',
            'remaining_fees_due',
            'academic_years',
            'selected_year',
            'fees',
            'students'
        ));
    }

    public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,student_id',
        'fee_type' => 'required|string|max:255',
        'installment_number' => 'required|integer|min:1',
        'academic_year' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'due_date' => 'required|date',
    ]);

    StudentFee::create($request->all());

    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Fee assigned successfully!'
        ]);
    }

    return redirect()->route('admin.fees.index')
        ->with('success', 'Fee assigned successfully!');
}

public function updatePayment(Request $request, $id)
{
    $request->validate([
        'paid_amount' => 'required|numeric|min:0',
        'payment_method' => 'required|string|max:255',
        'reference_number' => 'nullable|string|max:255',
    ]);

    $fee = StudentFee::findOrFail($id);

    $new_paid_amount = $fee->paid_amount + $request->paid_amount;
    $status = 'partial';

    if ($new_paid_amount >= $fee->amount) {
        $status = 'paid';
        $new_paid_amount = $fee->amount;
    }

    $fee->update([
        'paid_amount' => $new_paid_amount,
        'payment_date' => now(),
        'payment_method' => $request->payment_method,
        'reference_number' => $request->reference_number,
        'status' => $status,
    ]);

    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully!'
        ]);
    }

    return redirect()->route('admin.fees.index')
        ->with('success', 'Payment recorded successfully!');
}

public function destroy(Request $request, $id)
{
    try {
        $fee = StudentFee::findOrFail($id);
        $fee->delete();

        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Fee record deleted successfully!'
            ]);
        }

        return redirect()->route('admin.fees.index')
            ->with('success', 'Fee record deleted successfully!');

    } catch (\Exception $e) {
        // Check if it's an AJAX request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete fee record: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->route('admin.fees.index')
            ->with('error', 'Failed to delete fee record: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    $request->validate([
        'fee_type' => 'required|string|max:255',
        'installment_number' => 'required|integer|min:1',
        'amount' => 'required|numeric|min:0',
        'paid_amount' => 'required|numeric|min:0',
        'due_date' => 'required|date',
        'status' => 'required|in:pending,partial,paid,overdue',
    ]);

    $fee = StudentFee::findOrFail($id);
    
    // Ensure paid amount doesn't exceed amount
    $paid_amount = min($request->paid_amount, $request->amount);
    
    $fee->update([
        'fee_type' => $request->fee_type,
        'installment_number' => $request->installment_number,
        'amount' => $request->amount,
        'paid_amount' => $paid_amount,
        'due_date' => $request->due_date,
        'status' => $request->status,
    ]);

    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Fee record updated successfully!'
        ]);
    }

    return redirect()->route('admin.fees.index')
        ->with('success', 'Fee record updated successfully!');
}

/**
 * Get fee details for viewing (API endpoint)
 */
public function getFeeDetails($id)
{
    try {
        $fee = StudentFee::with('student')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'fee_id' => $fee->fee_id,
            'student' => [
                'name' => $fee->student->name,
                'student_id' => $fee->student->student_id
            ],
            'fee_type' => $fee->fee_type,
            'installment_number' => $fee->installment_number,
            'academic_year' => $fee->academic_year,
            'amount' => (float) $fee->amount,
            'paid_amount' => (float) $fee->paid_amount,
            'due_date' => $fee->due_date->toDateString(),
            'status' => $fee->status
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch fee details',
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Get fee data for editing (API endpoint)
 */
public function getFeeEditData($id)
{
    try {
        $fee = StudentFee::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'fee_id' => $fee->fee_id,
            'fee_type' => $fee->fee_type,
            'installment_number' => $fee->installment_number,
            'amount' => (float) $fee->amount,
            'paid_amount' => (float) $fee->paid_amount,
            'due_date' => $fee->due_date->format('Y-m-d'),
            'status' => $fee->status
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch fee data for editing',
            'message' => $e->getMessage()
        ], 500);
    }
}
}