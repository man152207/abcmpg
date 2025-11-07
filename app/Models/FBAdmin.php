<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FBAdmin extends Model
{
    use HasFactory;

    protected $table = 'fbadmins';
    protected $fillable = ['name', 'active_since'];

    // Define the relationship with AdAccount
    public function adAccounts()
    {
        return $this->belongsToMany(AdAccount::class, 'ad_account_fbadmin', 'fbadmin_id', 'ad_account_id');
    }
}
