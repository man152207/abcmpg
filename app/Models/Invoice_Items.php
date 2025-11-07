<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice_Items extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'item_id',
        'name',
        'quantity',
        'rate',
        'tax',
        'amount',
    ];
}
