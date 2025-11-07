<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyCardSpend extends Model
{
    use HasFactory;
    protected $fillable = [
    'card_name',
    'date',
    'amount_usd',
    'ad_account',
    'description',
];

}
