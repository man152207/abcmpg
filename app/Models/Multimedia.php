<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Multimedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'multimedia';

    protected $fillable = [
        'date', 'whatsapp', 'customer_name', 'project', 'status', 'project_by', 'project_type', 'notes',
        'asset_link', 'asset_provider', 'asset_access', 'asset_type', 'asset_version', 'asset_size_mb',
        'client_id', 'assigned_to', 'priority', 'due_date', 'platforms', 'caption_link', 'publish_url',
        'revision_count', 'approved_by_client', 'qa_checked', 'billing_code', 'estimate_hours', 'actual_hours', 'cost_npr',
        'created_by', 'updated_by',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'platforms' => 'array',
        'approved_by_client' => 'boolean',
        'qa_checked' => 'boolean',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(Admin::class, 'assigned_to');
    }
}