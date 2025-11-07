<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmFollowUp extends Model
{
    protected $fillable = [
        'crm_contact_id','contact_channel','planned_at','done_at','outcome','note',
        'reminder_set','snooze_until','created_by'
    ];

    protected $casts = [
        'planned_at' => 'datetime',
        'done_at'    => 'datetime',
        'reminder_set' => 'boolean',
        'snooze_until' => 'datetime',
    ];

    public function contact(): BelongsTo {
        return $this->belongsTo(CrmContact::class, 'crm_contact_id');
    }
}
