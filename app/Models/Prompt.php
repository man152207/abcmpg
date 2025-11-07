<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prompt extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'client', 'department', 'body', 'is_fav', 'created_by'
    ];

    protected $casts = [
        'is_fav' => 'boolean',
    ];

    // ⬇️ Add this
    public function creator()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'created_by');
    }
}
