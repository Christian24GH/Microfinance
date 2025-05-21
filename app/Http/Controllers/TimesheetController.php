<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Timesheet;
use App\Models\TimeEntry;
use App\Events\TimesheetStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TimesheetController extends Controller
{
    use AuthorizesRequests;

    public function getPendingTimesheets()
    {
        $timesheets = Timesheet::with(['employee', 'entries'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'timesheets' => $timesheets
        ]);
    }

    public function getTimesheetStats()
    {
        $stats = [
            'pending_count' => Timesheet::where('status', 'pending')->count(),
            'approved_today' => Timesheet::where('status', 'approved')->count(),
            'rejected_today' => Timesheet::where('status', 'rejected')->count()
        ];

        return response()->json([
            'status' => 'success',
            ...$stats
        ]);
    }

    public function getTimesheet($id)
    {
        $timesheet = Timesheet::with(['employee', 'entries'])
            ->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'timesheet' => $timesheet
        ]);
    }

    public function approveTimesheet($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $this->authorize('approve', $timesheet);
        if ($timesheet->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Timesheet is not in pending status'
            ], 409);
        }
        DB::beginTransaction();
        try {
            $timesheet->status = 'approved';
            $timesheet->approved_by = auth()->id();
            $timesheet->approved_at = now();
            $timesheet->save();
            // Update all time entries to approved
            TimeEntry::where('timesheet_id', $timesheet->id)
                ->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now()
                ]);
            // Broadcast the status update
            broadcast(new TimesheetStatusUpdated($timesheet))->toOthers();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Timesheet approved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function rejectTimesheet(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ]);
        $timesheet = Timesheet::findOrFail($id);
        $this->authorize('approve', $timesheet);
        if ($timesheet->status !== 'pending') {
            return response()->json([
                'status' => 'error',
                'message' => 'Timesheet is not in pending status'
            ], 409);
        }
        DB::beginTransaction();
        try {
            $timesheet->status = 'rejected';
            $timesheet->rejected_by = auth()->id();
            $timesheet->rejected_at = now();
            $timesheet->rejection_reason = $request->reason;
            $timesheet->save();
            // Update all time entries to rejected
            TimeEntry::where('timesheet_id', $timesheet->id)
                ->update([
                    'status' => 'rejected',
                    'rejected_by' => auth()->id(),
                    'rejected_at' => now(),
                    'rejection_reason' => $request->reason
                ]);
            // Broadcast the status update
            broadcast(new TimesheetStatusUpdated($timesheet))->toOthers();
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Timesheet rejected successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
