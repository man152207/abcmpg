<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RecpBatch extends Model
{
    protected $fillable = ['course_id', 'name', 'start_date', 'end_date', 'is_active'];
    protected $casts = ['start_date' => 'date', 'end_date' => 'date', 'is_active' => 'boolean'];

    public function course() { return $this->belongsTo(RecpCourse::class); }
    public function enrollments() { return $this->hasMany(RecpEnrollment::class, 'batch_id'); }
}