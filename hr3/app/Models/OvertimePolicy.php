<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimePolicy extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'overtime_rate',
        'eligibility_criteria',
        'is_active',
    ];

    protected $casts = [
        'eligibility_criteria' => 'array',
        'is_active' => 'boolean',
    ];

    public function overtimeRecords()
    {
        return $this->hasMany(OvertimeRecord::class, 'policy_id');
    }
}
