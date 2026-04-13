<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'email',
        'phone',
        'password',
        'phone_2',
        'address',
        'profile_picture',
        'requires_bill',
        'billing_status',
        'created_by',
    ];

    protected $casts = [
        'requires_bill' => 'boolean',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // ========== RELATIONSHIPS ==========

    public function campaignLinks()
    {
        return $this->hasMany(CampaignLink::class);
    }

    public function ads()
    {
        return $this->hasMany(\App\Models\Ad::class, 'customer', 'phone');
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function chats()
    {
        return $this->hasMany(InternalChat::class);
    }

    public function createdByAdmin()
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function packages()
    {
        return $this->belongsToMany(\App\Models\Package::class, 'customer_package')
            ->withPivot(['start_date', 'end_date', 'status'])
            ->withTimestamps();
    }

    // ========== ACTIVITY HELPERS ==========

    /**
     * Get the customer's last activity date (from ads)
     */
    public function getLastActivityAt(): ?Carbon
    {
        $lastAd = $this->ads()->latest('created_at')->first();
        return $lastAd ? Carbon::parse($lastAd->created_at) : null;
    }

    /**
     * Check if customer is active (had activity within last 20 days)
     */
    public function getIsActive(): bool
    {
        $lastActivity = $this->getLastActivityAt();
        if (!$lastActivity) {
            return false;
        }
        return $lastActivity->gte(Carbon::now()->subDays(20));
    }

    // ========== BOOT ==========

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->created_by)) {
                $model->created_by = auth('admin')->id() ?? auth()->id();
            }
        });
    }
}
