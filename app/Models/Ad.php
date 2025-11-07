<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Ad extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer',
        'USD',
        'Rate',
        'NRP',
        'Ad_Account',
        'Payment',
        'Duration',
        'Quantity',
        'Status',
        'Ad_Nature_Page',
        'add_on',
        'admin',
        'is_complete',
        'advance',
    ];
        protected $casts = [
        'add_on' => 'array',
    ];

    // Other model methods and properties

    public function calculateStatus($created_at, $duration)
    {
        $endDate = Carbon::parse($created_at)->addDays($duration);
        $now = Carbon::now();

        // Time difference in hours
        $hoursDifference = $now->diffInHours($endDate, false);
        $daysDifference = $now->diffInDays($endDate, false);

        if ($hoursDifference <= -24 && $daysDifference <= -7) {
            return null; // More than 7 days ago, no need to show status
        }

        if ($hoursDifference <= -24) {
            return "Ended " . abs($daysDifference) . " days ago";
        } elseif ($hoursDifference < 0) {
            return "Ended " . abs($hoursDifference) . " hours ago";
        } elseif ($hoursDifference === 0) {
            return "Ending today";
        } elseif ($hoursDifference <= 24) {
            return "Ending tomorrow at " . $endDate->format('H:i:s');
        } else {
            return "Running";
        }
    }
    
    public function customer()
{
    return $this->belongsTo(Customer::class, 'customer', 'phone'); // assuming 'customer' in Ad corresponds to 'phone' in Customer
}
    public function customerRelation()
{
    return $this->belongsTo(Customer::class, 'customer', 'phone');
}
}
