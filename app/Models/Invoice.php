<?php

namespace App\Models;
use App\Models\Invoice;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'invoice_number',
        'salesperson',
        'description',
        'date',
        'created_by',
    ];
}
