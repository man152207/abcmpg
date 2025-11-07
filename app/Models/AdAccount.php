<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAccount extends Model
{
    use HasFactory;

    protected $table = 'ad_accounts';
    protected $fillable = [ 'account_name', 'active_since', 'account_threshold', 'initial_remaining_days', 'running_ads_balance', 'targeted_budget', 'new_applied_budget', 'threshold_reached_date', 'new_applied_history',];

    // Define the relationship with FBAdmin
    public function fbAdmins()
    {
        return $this->belongsToMany(FBAdmin::class, 'ad_account_fbadmin', 'ad_account_id', 'fbadmin_id');
    }
}
