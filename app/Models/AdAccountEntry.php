<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAccountEntry extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'ad__accounts';

    // Define the fillable columns
    protected $fillable = [
        'ad_account_name',
        'current_threshold',
        'current_balance',
        'targeted_budget',
    ];

    // Timestamps are automatically handled; override if necessary
    public $timestamps = true;
}
