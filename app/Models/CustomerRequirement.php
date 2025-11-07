<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerRequirement extends Model
{
    use HasFactory;

    protected $table = 'customer_requirements';

    protected $fillable = [
        'customer_id',
        'note_type',
        'priority',
        'body',
        'admin',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}