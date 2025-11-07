<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherIncome extends Model
{
    use HasFactory;

    protected $table = 'other_incomes';

    protected $fillable = [
        'date',
        'contact_number',
        'customer_name',
        'amount',  // Updated to use 'amount' instead of 'payment_source'
        'remarks',
        'income_type',
    ];
}
