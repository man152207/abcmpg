<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['name','slug','is_active'];
    protected $casts = ['is_active' => 'boolean'];

    public function admins()
    {
        return $this->belongsToMany(\App\Models\Admin::class, 'admin_department')->withTimestamps();
    }
}
