<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CrmContact extends Model
{
    protected $fillable = [
        'name','phone_primary','phone_alt','whatsapp_opt_in','fb_profile_url','messenger_username',
        'service_interest','budget_range','city','preferred_language','source','tags',
        'status','priority','assigned_to','last_contact_at','next_followup_at',
        'notes_summary','consent_notes','created_by','updated_by'
    ];

    protected $casts = [
        'whatsapp_opt_in' => 'boolean',
        'last_contact_at' => 'datetime',
        'next_followup_at' => 'datetime',
    ];

    public function followUps(): HasMany {
        return $this->hasMany(CrmFollowUp::class, 'crm_contact_id');
    }

    public function latestFollowUp(): HasOne {
        return $this->hasOne(CrmFollowUp::class, 'crm_contact_id')->latestOfMany();
    }
}
