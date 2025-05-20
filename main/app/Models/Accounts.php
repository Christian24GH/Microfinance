<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Accounts extends Authenticatable
{
    use HasFactory, HasApiTokens;
    public $timestamps = false;
    protected $table = "accounts";
    protected $fillable = ['fullname', 'email', 'password', 'role'];
    protected $hidden = [
        'password',
    ];
    protected $casts = [
        'role'=>'string'
    ];
}
