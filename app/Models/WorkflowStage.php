<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'workflow_id',
        'name',
        'stage_order',
        'approver_type',
        'approver_id',
        'is_final',
        'description'
    ];

    protected $casts = [
        'is_final' => 'boolean'
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function getApproverTypeLabelAttribute(): string
    {
        return match($this->approver_type) {
            'employee' => 'Specific Employee',
            'role' => 'Role-based',
            'department' => 'Department Head',
            default => 'Unknown'
        };
    }

    public function getApproverNameAttribute(): string
    {
        return match($this->approver_type) {
            'employee' => Employee::find($this->approver_id)?->full_name ?? 'Unknown',
            'role' => Role::find($this->approver_id)?->name ?? 'Unknown',
            'department' => Department::find($this->approver_id)?->name ?? 'Unknown',
            default => 'Unknown'
        };
    }
}
