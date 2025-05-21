<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timesheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'total_hours',
        'status',
        'notes',
        'current_approver_id',
        'current_workflow_stage',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'total_hours' => 'decimal:2'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function entries(): HasMany
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function currentApprover(): BelongsTo
    {
        return $this->belongsTo(User::class, 'current_approver_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(TimesheetApproval::class);
    }

    public function calculateTotalHours()
    {
        $this->total_hours = $this->entries()
            ->whereBetween('date', [$this->start_date, $this->end_date])
            ->sum('total_hours');
        $this->save();
    }

    public function submitForApproval()
    {
        $this->status = 'pending';
        $this->save();

        // Get the first stage approver
        $workflow = Workflow::where('type', 'timesheet')
            ->where('is_active', true)
            ->first();

        if ($workflow) {
            $firstStage = $workflow->stages()
                ->orderBy('stage_order')
                ->first();

            if ($firstStage) {
                $this->current_workflow_stage = $firstStage->stage_order;
                $this->current_approver_id = $this->getApproverId($firstStage);
                $this->save();

                // Create approval record
                TimesheetApproval::create([
                    'timesheet_id' => $this->id,
                    'approver_id' => $this->current_approver_id,
                    'stage' => $firstStage->stage_order,
                    'status' => 'pending'
                ]);

                // Broadcast event
                event(new \App\Events\TimesheetStatusUpdated($this));
            }
        }
    }

    public function approve($approverId)
    {
        $workflow = Workflow::where('type', 'timesheet')
            ->where('is_active', true)
            ->first();

        if ($workflow) {
            $currentStage = $workflow->stages()
                ->where('stage_order', $this->current_workflow_stage)
                ->first();

            if ($currentStage) {
                // Update current approval
                $approval = $this->approvals()
                    ->where('stage', $this->current_workflow_stage)
                    ->first();

                if ($approval) {
                    $approval->update([
                        'status' => 'approved',
                        'approved_at' => now()
                    ]);
                }

                // Check if this is the final stage
                if ($currentStage->is_final) {
                    $this->status = 'approved';
                    $this->approved_by = $approverId;
                    $this->approved_at = now();
                    $this->save();
                } else {
                    // Move to next stage
                    $nextStage = $workflow->getNextStage($this->current_workflow_stage);
                    if ($nextStage) {
                        $this->current_workflow_stage = $nextStage->stage_order;
                        $this->current_approver_id = $this->getApproverId($nextStage);
                        $this->save();

                        // Create next approval record
                        TimesheetApproval::create([
                            'timesheet_id' => $this->id,
                            'approver_id' => $this->current_approver_id,
                            'stage' => $nextStage->stage_order,
                            'status' => 'pending'
                        ]);
                    }
                }

                // Broadcast event
                event(new \App\Events\TimesheetStatusUpdated($this));
            }
        }
    }

    public function reject($rejectorId, $reason)
    {
        $this->status = 'rejected';
        $this->rejected_by = $rejectorId;
        $this->rejected_at = now();
        $this->rejection_reason = $reason;
        $this->save();

        // Update current approval
        $approval = $this->approvals()
            ->where('stage', $this->current_workflow_stage)
            ->first();

        if ($approval) {
            $approval->update([
                'status' => 'rejected',
                'comments' => $reason
            ]);
        }

        // Broadcast event
        event(new \App\Events\TimesheetStatusUpdated($this));
    }

    private function getApproverId($stage)
    {
        // Logic to determine approver based on stage configuration
        if ($stage->approver_type === 'employee') {
            return $stage->approver_id;
        } elseif ($stage->approver_type === 'role') {
            // Get first user with the specified role
            return User::role($stage->approver_id)->first()?->id;
        } elseif ($stage->approver_type === 'department') {
            // Get department head
            return Employee::where('department_id', $stage->approver_id)
                ->where('position_id', function($query) {
                    $query->select('id')
                        ->from('positions')
                        ->where('name', 'like', '%Head%')
                        ->first();
                })
                ->first()?->id;
        }
        return null;
    }
}
