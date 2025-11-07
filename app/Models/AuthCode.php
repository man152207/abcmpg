<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'auth_token_code',
        'recovery_code',
        'result',
    ];
}
