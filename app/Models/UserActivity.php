<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin; // <-- ADD THIS

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','login_time','active_hours','location','refresh_rate',
        'frequent_page','inactive_time','daily_data_entries','last_activity',
        'last_active_start','last_active_end','latitude','longitude',
    ];

    protected $casts = [
        'daily_data_entries' => 'array',
        'frequent_page'      => 'array', // JSON<->array
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id');
    }
}
