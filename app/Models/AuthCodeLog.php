<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthCodeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'auth_code_id',
        'admin_id',
        'device',
        'location',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    public function authCode()
    {
        return $this->belongsTo(AuthCode::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}