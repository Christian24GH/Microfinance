<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClaimAttachment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'claim_id',
        'file_path',
        'file_type',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $dates = [
        'uploaded_at',
    ];

    public function claim()
    {
        return $this->belongsTo(Claim::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
