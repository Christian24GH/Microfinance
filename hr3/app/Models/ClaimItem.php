<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_id',
        'description',
        'amount',
        'date',
        'receipt_number',
        'category'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date'
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }
}
