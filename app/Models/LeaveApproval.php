<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_request_id',
        'approver_id',
        'stage',
        'status',
        'approved_at',
        'rejected_at',
        'comments'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime'
    ];

    public function leaveRequest()
    {
        return $this->belongsTo(LeaveRequest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
