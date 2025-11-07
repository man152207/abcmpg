<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecpStudent extends Model
{
    use SoftDeletes;
    protected $fillable = ['full_name', 'phone', 'email', 'guardian_name', 'address', 'dob', 'status', 'remarks', 'photo_path'];
    protected $casts = ['dob' => 'date'];

    public function enrollments() { return $this->hasMany(RecpEnrollment::class, 'student_id'); }
    public function payments() { return $this->hasManyThrough(RecpPayment::class, RecpEnrollment::class); }
    public function documents() { return $this->hasMany(RecpDocument::class, 'student_id'); }
}