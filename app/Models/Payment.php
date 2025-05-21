<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_id',
        'amount',
        'status',
        'payment_method',
        'reference_number',
        'payment_date',
        'notes',
        'created_by',
        'processed_by',
        'processed_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'processed_at' => 'datetime'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
