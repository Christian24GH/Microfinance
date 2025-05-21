<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TimeEntry;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'phone',
        'department_id',
        'position_id',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function timesheets()
    {
        return $this->hasMany(Timesheet::class);
    }

    public function getTotalHoursAttribute()
    {
        return $this->timeEntries()
            ->where('status', 'approved')
            ->sum('total_hours');
    }

    public function getActiveTimeEntryAttribute()
    {
        return $this->timeEntries()
            ->whereNull('time_out')
            ->where('date', today())
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    public function scopeByPosition($query, $positionId)
    {
        return $query->where('position_id', $positionId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('employee_id', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }
}
