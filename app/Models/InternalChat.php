<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalChat extends Model
{
    protected $fillable = ['customer_id', 'message', 'image_paths', 'is_admin', 'admin_id'];
    protected $casts = ['image_paths' => 'array'];
    
    public function admin()
    {
        return $this->belongsTo(\App\Models\Admin::class, 'admin_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'chat_id');
    }
}