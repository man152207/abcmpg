<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StorredAdAccount extends Model
{
    use HasFactory;
    
    protected $table = 'storred_ad_accounts';
    protected $fillable = ['group_name', 'ad_account_name', 'created_at', 'updated_at'];
}
