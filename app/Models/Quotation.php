<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'company',
        'email',
        'phone',
        'address',
        'service_details',
        'campaign_objectives',
        'budget',
        'duration',
        'target_location',
        'age_range',
        'gender',
        'total_price',
        'status',
    ];
}
