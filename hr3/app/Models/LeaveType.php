<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'default_days',
        'requires_approval',
        'is_active'
    ];

    protected $casts = [
        'requires_approval' => 'boolean',
        'is_active' => 'boolean',
        'default_days' => 'integer'
    ];

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
