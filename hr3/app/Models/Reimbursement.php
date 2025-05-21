<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reimbursement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_id',
        'amount',
        'currency',
        'paid_at',
        'payment_reference',
        'status',
        'notes',
    ];

    protected $dates = [
        'paid_at',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
