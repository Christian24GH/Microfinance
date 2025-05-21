<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
// use Spatie\Activitylog\Traits\LogsActivity;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'time_in',
        'time_out',
        'total_hours',
        'status',
        'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'time_in' => 'datetime',
        'time_out' => 'datetime',
        'total_hours' => 'float'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function timesheet(): BelongsTo
    {
        return $this->belongsTo(Timesheet::class);
    }

    public function calculateTotalHours()
    {
        if ($this->time_in && $this->time_out) {
            $timeIn = Carbon::parse($this->time_in);
            $timeOut = Carbon::parse($this->time_out);

            // Calculate the difference in hours
            $hours = $timeOut->diffInSeconds($timeIn) / 3600;

            // Round to 2 decimal places
            $this->total_hours = round($hours, 2);
            $this->save();
        }

        return $this;
    }

    public function getFormattedDateAttribute()
    {
        return $this->date->format('Y-m-d');
    }

    public function getFormattedTimeInAttribute()
    {
        return $this->time_in ? Carbon::parse($this->time_in)->format('h:i A') : null;
    }

    public function getFormattedTimeOutAttribute()
    {
        return $this->time_out ? Carbon::parse($this->time_out)->format('h:i A') : null;
    }

    public function getTimeInTimeAttribute()
    {
        return $this->time_in ? Carbon::parse($this->time_in)->format('H:i') : null;
    }

    public function getTimeOutTimeAttribute()
    {
        return $this->time_out ? Carbon::parse($this->time_out)->format('H:i') : null;
    }

    public function getTotalHoursAttribute($value)
    {
        return $value ? number_format($value, 2) : null;
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($timeEntry) {
            event(new \App\Events\TimeEntryCreated($timeEntry));
        });

        static::updated(function ($timeEntry) {
            event(new \App\Events\TimeEntryUpdated($timeEntry));
        });

        static::deleted(function ($timeEntry) {
            event(new \App\Events\TimeEntryDeleted($timeEntry));
        });
    }
}
