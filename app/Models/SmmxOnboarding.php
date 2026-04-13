<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SmmxOnboarding extends Model
{
    use HasFactory;

    protected $table = 'smmx_onboardings';

    protected $fillable = [
        'customer_id',
        'package_id',
        'business_name',
        'brand_name',
        'contact_person',
        'phone',
        'email',
        'business_address',
        'facebook_link',
        'instagram_link',
        'tiktok_link',
        'website_link',
        'page_access_status',
        'business_manager_status',
        'primary_goal',
        'target_location',
        'target_age_group',
        'target_gender',
        'target_interests',
        'competitors',
        'brand_colors',
        'preferred_language',
        'content_preferences',
        'monthly_budget',
        'approval_required',
        'approval_contact',
        'notes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'approval_required' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customer::class, 'customer_id');
    }

    public function package()
    {
        return $this->belongsTo(\App\Models\Package::class, 'package_id');
    }
}